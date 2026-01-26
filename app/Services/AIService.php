<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Work;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * AI Service for analytical questions
 * 
 * READ-ONLY SERVICE - NO DATABASE WRITES ALLOWED
 * 
 * This service only performs:
 * - SELECT queries with aggregation (COUNT, SUM, GROUP BY)
 * - Statistical data gathering
 * 
 * NEVER performs:
 * - INSERT, UPDATE, DELETE queries
 * - Raw record retrieval (only aggregated stats)
 * - Any data modifications
 */
class AIService
{
    private string $openAiApiKey;
    private string $openAiApiUrl = 'https://api.openai.com/v1/chat/completions';
    private string $model = 'gpt-4o-mini';

    public function __construct()
    {
        $apiKey = config('services.openai.api_key', env('OPENAI_API_KEY', ''));
        
        // Don't throw exception in constructor - check when actually needed
        $this->openAiApiKey = $apiKey ?: '';
    }

    /**
     * Analyze and answer questions about works, invoices, and payments
     * READ-ONLY operations only - no database writes allowed
     *
     * @param string $question
     * @return array Returns array with answer, intent, confidence, period
     */
    public function answerQuestion(string $question): array
    {
        try {
            // Detect user intent
            $intent = $this->detectIntent($question);
            $intentConfidence = $this->calculateIntentConfidence($question, $intent);

            // If intent is unclear, return clarifying question
            if ($intent === 'unclear') {
                return [
                    'answer' => $this->askClarifyingQuestion($question),
                    'intent' => null,
                    'confidence' => 'low',
                    'period' => null,
                ];
            }

            // Extract time range from question
            $timeRange = $this->extractTimeRange($question);
            
            // If time range is ambiguous, return clarification
            if ($timeRange === 'ambiguous') {
                return [
                    'answer' => $this->askTimeClarification($question),
                    'intent' => $intent,
                    'confidence' => 'medium',
                    'period' => null,
                ];
            }

            // Gather context data (READ-ONLY) with time filtering
            $context = $this->gatherContext($timeRange);

            // Calculate data confidence based on availability
            $dataConfidence = $this->calculateDataConfidence($question, $context, $timeRange);

            // Build system prompt with intent-specific instructions
            $systemPrompt = $this->buildSystemPrompt($intent);

            // Build user prompt with context, intent, and time range
            $userPrompt = $this->buildUserPrompt($question, $context, $intent, $timeRange);

            // Call OpenAI API
            $answer = $this->callOpenAI($systemPrompt, $userPrompt);

            // Calculate overall confidence (combine intent and data confidence)
            $overallConfidence = $this->calculateOverallConfidence($intentConfidence, $dataConfidence);

            // Format period information
            $period = $this->formatPeriod($timeRange);

            return [
                'answer' => $answer,
                'intent' => $intent,
                'confidence' => $overallConfidence,
                'period' => $period,
            ];
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage(), [
                'question' => $question,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'answer' => 'Xəta baş verdi: ' . $e->getMessage(),
                'intent' => null,
                'confidence' => 'low',
                'period' => null,
            ];
        }
    }

    /**
     * Calculate intent detection confidence
     *
     * @param string $question
     * @param string $intent
     * @return string
     */
    private function calculateIntentConfidence(string $question, string $intent): string
    {
        if ($intent === 'unclear') {
            return 'low';
        }

        $questionLower = mb_strtolower($question);

        // Count keyword matches for each intent
        $financeKeywords = ['məbləğ', 'qaimə', 'ödəniş', 'gəlir', 'xərc', 'maliyyə'];
        $operationsKeywords = ['iş', 'status', 'proses', 'şöbə', 'xidmət', 'müştəri'];
        $riskKeywords = ['risk', 'problem', 'xəta', 'gecikmə', 'qayıtma'];
        $performanceKeywords = ['statistika', 'trend', 'artım', 'müqayisə', 'məhsuldarlıq'];

        $keywords = match($intent) {
            'finance' => $financeKeywords,
            'operations' => $operationsKeywords,
            'risk' => $riskKeywords,
            'performance' => $performanceKeywords,
            default => [],
        };

        $matchCount = $this->countKeywordMatches($questionLower, $keywords);

        // High: 3+ matches, Medium: 2 matches, Low: 1 match
        if ($matchCount >= 3) {
            return 'high';
        } elseif ($matchCount >= 2) {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Calculate data availability confidence
     *
     * @param string $question
     * @param array $context
     * @param array|null $timeRange
     * @return string
     */
    private function calculateDataConfidence(string $question, array $context, ?array $timeRange): string
    {
        $questionLower = mb_strtolower($question);
        $hasRequiredData = true;
        $hasAllData = true;

        // Check for works data
        if (mb_strpos($questionLower, 'iş') !== false || mb_strpos($questionLower, 'work') !== false) {
            $hasWorks = isset($context['works']['total']) && $context['works']['total'] > 0;
            if (!$hasWorks) {
                $hasRequiredData = false;
            }
        }

        // Check for invoice data
        if (mb_strpos($questionLower, 'qaimə') !== false || mb_strpos($questionLower, 'invoice') !== false) {
            $hasInvoices = isset($context['invoices']['total']) && $context['invoices']['total'] > 0;
            if (!$hasInvoices) {
                $hasRequiredData = false;
            }
        }

        // Check for transaction data
        if (mb_strpos($questionLower, 'ödəniş') !== false || 
            mb_strpos($questionLower, 'payment') !== false ||
            mb_strpos($questionLower, 'gəlir') !== false ||
            mb_strpos($questionLower, 'xərc') !== false) {
            $hasTransactions = isset($context['transactions']['total']) && $context['transactions']['total'] > 0;
            if (!$hasTransactions) {
                $hasRequiredData = false;
            }
        }

        // Check for comparison/trend data
        if (mb_strpos($questionLower, 'trend') !== false || 
            mb_strpos($questionLower, 'müqayisə') !== false ||
            mb_strpos($questionLower, 'artım') !== false ||
            mb_strpos($questionLower, 'azalma') !== false) {
            $hasComparison = false;
            foreach (['works', 'invoices', 'transactions'] as $key) {
                if (isset($context[$key]['previous_period']) && !empty($context[$key]['previous_period'])) {
                    $hasComparison = true;
                    break;
                }
            }
            if (!$hasComparison) {
                $hasAllData = false;
            }
        }

        if (!$hasRequiredData) {
            return 'low';
        }

        if (!$hasAllData) {
            return 'medium';
        }

        return 'high';
    }

    /**
     * Calculate overall confidence combining intent and data confidence
     *
     * @param string $intentConfidence
     * @param string $dataConfidence
     * @return string
     */
    private function calculateOverallConfidence(string $intentConfidence, string $dataConfidence): string
    {
        // If either is low, overall is low
        if ($intentConfidence === 'low' || $dataConfidence === 'low') {
            return 'low';
        }

        // If both are high, overall is high
        if ($intentConfidence === 'high' && $dataConfidence === 'high') {
            return 'high';
        }

        // Otherwise medium
        return 'medium';
    }

    /**
     * Format period information for response
     *
     * @param array|null $timeRange
     * @return array|null
     */
    private function formatPeriod(?array $timeRange): ?array
    {
        if (!$timeRange) {
            return [
                'type' => 'all_time',
                'label' => 'Bütün dövr',
                'from' => null,
                'to' => null,
                'field' => 'created_at',
            ];
        }

        return [
            'type' => 'range',
            'label' => $timeRange['label'] ?? 'Müəyyən edilmiş dövr',
            'from' => $timeRange['from']->format('Y-m-d'),
            'to' => $timeRange['to']->format('Y-m-d'),
            'field' => $timeRange['field'] ?? 'created_at',
        ];
    }

    /**
     * Detect user intent from question
     * Categories: finance, operations, risk, performance, unclear
     *
     * @param string $question
     * @return string
     */
    private function detectIntent(string $question): string
    {
        $questionLower = mb_strtolower($question);

        // Finance intent keywords
        $financeKeywords = [
            'məbləğ', 'qaimə', 'ödəniş', 'gəlir', 'xərc', 'maliyyə', 'pul', 'azn', 'valyuta',
            'invoice', 'payment', 'income', 'expense', 'amount', 'revenue', 'cost', 'budget',
            'hesab', 'balans', 'profit', 'mənfəət', 'zərər'
        ];

        // Operations intent keywords
        $operationsKeywords = [
            'iş', 'work', 'status', 'proses', 'əməliyyat', 'task', 'operation', 'şöbə', 'department',
            'xidmət', 'service', 'müştəri', 'client', 'təyinat', 'destination', 'verifikasiya',
            'tamamlanma', 'planlaşdırılan', 'pending', 'started', 'done'
        ];

        // Risk intent keywords
        $riskKeywords = [
            'risk', 'təhlükə', 'problem', 'xəta', 'gecikmə', 'delay', 'qayıtmış', 'returned',
            'imtina', 'rejected', 'lövhə', 'archive', 'qayıtma', 'problemli', 'issue'
        ];

        // Performance intent keywords
        $performanceKeywords = [
            'məhsuldarlıq', 'performance', 'sürət', 'statistika', 'trend', 'artım', 'azalma',
            'müqayisə', 'fərq', 'dəyişiklik', 'artım', 'growth', 'efficiency', 'səmərəlilik',
            'ortalama', 'average', 'cəmi', 'toplam', 'aylıq', 'monthly'
        ];

        // Count matches
        $financeMatches = $this->countKeywordMatches($questionLower, $financeKeywords);
        $operationsMatches = $this->countKeywordMatches($questionLower, $operationsKeywords);
        $riskMatches = $this->countKeywordMatches($questionLower, $riskKeywords);
        $performanceMatches = $this->countKeywordMatches($questionLower, $performanceKeywords);

        // Determine intent based on highest match count
        $matches = [
            'finance' => $financeMatches,
            'operations' => $operationsMatches,
            'risk' => $riskMatches,
            'performance' => $performanceMatches,
        ];

        $maxMatches = max($matches);
        $detectedIntent = array_search($maxMatches, $matches);

        // If no clear intent (all zeros or very low), mark as unclear
        if ($maxMatches === 0 || ($maxMatches === 1 && strlen($question) < 10)) {
            return 'unclear';
        }

        // If intent is ambiguous (multiple categories with same high score), ask for clarification
        $topMatches = array_filter($matches, fn($count) => $count >= $maxMatches - 1 && $count > 0);
        if (count($topMatches) > 1 && $maxMatches < 3) {
            return 'unclear';
        }

        return $detectedIntent;
    }

    /**
     * Count keyword matches in question
     *
     * @param string $text
     * @param array $keywords
     * @return int
     */
    private function countKeywordMatches(string $text, array $keywords): int
    {
        $count = 0;
        foreach ($keywords as $keyword) {
            if (mb_strpos($text, $keyword) !== false) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Ask clarifying question when intent is unclear
     *
     * @param string $question
     * @return string
     */
    private function askClarifyingQuestion(string $question): string
    {
        return "Sualınızı daha dəqiq izah edə bilərsinizmi? " .
               "Aşağıdakı kateqoriyalardan birini seçin:\n\n" .
               "• **Maliyyə**: Qaimələr, ödənişlər, gəlir/xərc, məbləğlər\n" .
               "• **Əməliyyatlar**: İş statusları, proseslər, şöbələr, müştərilər\n" .
               "• **Risk**: Problemli işlər, gecikmələr, qayıtmalar\n" .
               "• **Performans**: Statistikalar, trendlər, müqayisələr, məhsuldarlıq\n\n" .
               "Məsələn: 'Bu ay maliyyə göstəriciləri nədir?' və ya 'Neçə iş gözləyir?'";
    }

    /**
     * Gather analytical context data (READ-ONLY)
     * Only aggregated statistics - no raw records
     * OPTIMIZED: Uses caching (5-15 min) to reduce DB queries
     *
     * @param array|null $timeRange
     * @return array
     */
    private function gatherContext(?array $timeRange = null): array
    {
        // Generate cache key based on time range
        $cacheKey = $this->generateCacheKey($timeRange);
        
        // Cache TTL: 10 minutes (middle of 5-15 min range)
        $cacheTtl = 600; // 10 minutes in seconds

        return Cache::remember($cacheKey, $cacheTtl, function () use ($timeRange) {
            return [
                'works' => $this->getWorksStats($timeRange),
                'invoices' => $this->getInvoicesStats($timeRange),
                'transactions' => $this->getTransactionsStats($timeRange),
                'time_range' => $timeRange ? [
                    'from' => $timeRange['from']->format('Y-m-d'),
                    'to' => $timeRange['to']->format('Y-m-d'),
                    'label' => $timeRange['label'] ?? null,
                ] : null,
                'cached_at' => now()->toDateTimeString(),
            ];
        });
    }

    /**
     * Generate cache key based on time range
     * Different time ranges get different cache keys
     *
     * @param array|null $timeRange
     * @return string
     */
    private function generateCacheKey(?array $timeRange): string
    {
        if ($timeRange) {
            $dateField = $timeRange['field'] ?? 'created_at';
            $from = $timeRange['from']->format('Y-m-d');
            $to = $timeRange['to']->format('Y-m-d');
            return "ai_stats:{$dateField}:{$from}:{$to}";
        }

        return 'ai_stats:all_time';
    }

    /**
     * Extract time range from question
     * Returns array with 'from', 'to' dates and 'field' (created_at, paid_at, vat_date) or 'ambiguous'
     *
     * @param string $question
     * @return array|string
     */
    private function extractTimeRange(string $question): array|string
    {
        $questionLower = mb_strtolower($question);
        $now = now();

        // Time expressions and their date ranges
        $timePatterns = [
            // Current period
            'this month' => fn() => [
                'from' => $now->copy()->startOfMonth(),
                'to' => $now->copy()->endOfMonth(),
                'label' => 'bu ay',
                'field' => $this->detectDateField($question),
            ],
            'bu ay' => fn() => [
                'from' => $now->copy()->startOfMonth(),
                'to' => $now->copy()->endOfMonth(),
                'label' => 'bu ay',
                'field' => $this->detectDateField($question),
            ],
            'this week' => fn() => [
                'from' => $now->copy()->startOfWeek(),
                'to' => $now->copy()->endOfWeek(),
                'label' => 'bu həftə',
                'field' => $this->detectDateField($question),
            ],
            'bu həftə' => fn() => [
                'from' => $now->copy()->startOfWeek(),
                'to' => $now->copy()->endOfWeek(),
                'label' => 'bu həftə',
                'field' => $this->detectDateField($question),
            ],
            'this year' => fn() => [
                'from' => $now->copy()->startOfYear(),
                'to' => $now->copy()->endOfYear(),
                'label' => 'bu il',
                'field' => $this->detectDateField($question),
            ],
            'bu il' => fn() => [
                'from' => $now->copy()->startOfYear(),
                'to' => $now->copy()->endOfYear(),
                'label' => 'bu il',
                'field' => $this->detectDateField($question),
            ],
            'today' => fn() => [
                'from' => $now->copy()->startOfDay(),
                'to' => $now->copy()->endOfDay(),
                'label' => 'bu gün',
                'field' => $this->detectDateField($question),
            ],
            'bu gün' => fn() => [
                'from' => $now->copy()->startOfDay(),
                'to' => $now->copy()->endOfDay(),
                'label' => 'bu gün',
                'field' => $this->detectDateField($question),
            ],

            // Previous period
            'last month' => fn() => [
                'from' => $now->copy()->subMonth()->startOfMonth(),
                'to' => $now->copy()->subMonth()->endOfMonth(),
                'label' => 'keçən ay',
                'field' => $this->detectDateField($question),
            ],
            'keçən ay' => fn() => [
                'from' => $now->copy()->subMonth()->startOfMonth(),
                'to' => $now->copy()->subMonth()->endOfMonth(),
                'label' => 'keçən ay',
                'field' => $this->detectDateField($question),
            ],
            'last week' => fn() => [
                'from' => $now->copy()->subWeek()->startOfWeek(),
                'to' => $now->copy()->subWeek()->endOfWeek(),
                'label' => 'keçən həftə',
                'field' => $this->detectDateField($question),
            ],
            'keçən həftə' => fn() => [
                'from' => $now->copy()->subWeek()->startOfWeek(),
                'to' => $now->copy()->subWeek()->endOfWeek(),
                'label' => 'keçən həftə',
                'field' => $this->detectDateField($question),
            ],
            'last year' => fn() => [
                'from' => $now->copy()->subYear()->startOfYear(),
                'to' => $now->copy()->subYear()->endOfYear(),
                'label' => 'keçən il',
                'field' => $this->detectDateField($question),
            ],
            'keçən il' => fn() => [
                'from' => $now->copy()->subYear()->startOfYear(),
                'to' => $now->copy()->subYear()->endOfYear(),
                'label' => 'keçən il',
                'field' => $this->detectDateField($question),
            ],
            'previous year' => fn() => [
                'from' => $now->copy()->subYear()->startOfYear(),
                'to' => $now->copy()->subYear()->endOfYear(),
                'label' => 'keçən il',
                'field' => $this->detectDateField($question),
            ],

            // Days range
            'last 7 days' => fn() => [
                'from' => $now->copy()->subDays(7)->startOfDay(),
                'to' => $now->copy()->endOfDay(),
                'label' => 'son 7 gün',
                'field' => $this->detectDateField($question),
            ],
            'son 7 gün' => fn() => [
                'from' => $now->copy()->subDays(7)->startOfDay(),
                'to' => $now->copy()->endOfDay(),
                'label' => 'son 7 gün',
                'field' => $this->detectDateField($question),
            ],
            'last 30 days' => fn() => [
                'from' => $now->copy()->subDays(30)->startOfDay(),
                'to' => $now->copy()->endOfDay(),
                'label' => 'son 30 gün',
                'field' => $this->detectDateField($question),
            ],
            'son 30 gün' => fn() => [
                'from' => $now->copy()->subDays(30)->startOfDay(),
                'to' => $now->copy()->endOfDay(),
                'label' => 'son 30 gün',
                'field' => $this->detectDateField($question),
            ],
            'last 90 days' => fn() => [
                'from' => $now->copy()->subDays(90)->startOfDay(),
                'to' => $now->copy()->endOfDay(),
                'label' => 'son 90 gün',
                'field' => $this->detectDateField($question),
            ],
            'son 90 gün' => fn() => [
                'from' => $now->copy()->subDays(90)->startOfDay(),
                'to' => $now->copy()->endOfDay(),
                'label' => 'son 90 gün',
                'field' => $this->detectDateField($question),
            ],
        ];

        // Check for ambiguous time expressions
        $ambiguousPatterns = [
            'ay', 'month', 'il', 'year', 'həftə', 'week', 'gün', 'day',
            'son', 'last', 'previous', 'keçən', 'bu', 'this'
        ];
        
        $ambiguousCount = 0;
        foreach ($ambiguousPatterns as $pattern) {
            if (mb_strpos($questionLower, $pattern) !== false) {
                $ambiguousCount++;
            }
        }

        // If multiple ambiguous patterns found without clear context, return ambiguous
        if ($ambiguousCount > 2) {
            // Check if there's a clear pattern match
            $foundPattern = false;
            foreach ($timePatterns as $pattern => $callback) {
                if (mb_strpos($questionLower, $pattern) !== false) {
                    $foundPattern = true;
                    return $callback();
                }
            }
            if (!$foundPattern) {
                return 'ambiguous';
            }
        }

        // Try to match patterns
        foreach ($timePatterns as $pattern => $callback) {
            if (mb_strpos($questionLower, $pattern) !== false) {
                return $callback();
            }
        }

        // No time range specified
        return null;
    }

    /**
     * Detect which date field is relevant based on question context
     * Returns whitelisted field name to prevent SQL injection
     *
     * @param string $question
     * @return string
     */
    private function detectDateField(string $question): string
    {
        $questionLower = mb_strtolower($question);

        // Allowed date fields (whitelist)
        $allowedFields = [
            'created_at' => ['yaradılmış', 'created', 'yaratılmış'],
            'paid_at' => ['ödəniş', 'paid', 'ödenmiş', 'ödənmiş'],
            'vat_date' => ['ədv', 'vat', 'vergi'],
        ];

        // Payment/paid related - use paid_at
        if (mb_strpos($questionLower, 'ödəniş') !== false || 
            mb_strpos($questionLower, 'paid') !== false ||
            mb_strpos($questionLower, 'ödenmiş') !== false ||
            mb_strpos($questionLower, 'ödənmiş') !== false) {
            return 'paid_at';
        }

        // VAT related - use vat_date
        if (mb_strpos($questionLower, 'ədv') !== false || 
            mb_strpos($questionLower, 'vat') !== false ||
            mb_strpos($questionLower, 'vergi') !== false) {
            return 'vat_date';
        }

        // Default - use created_at (always safe)
        return 'created_at';
    }

    /**
     * Validate date field name to prevent SQL injection
     *
     * @param string $field
     * @return string
     */
    private function validateDateField(string $field): string
    {
        $allowedFields = ['created_at', 'paid_at', 'vat_date', 'transaction_date', 'invoiced_date'];
        
        if (!in_array($field, $allowedFields)) {
            return 'created_at'; // Default to safe field
        }

        return $field;
    }

    /**
     * Ask for time clarification when ambiguous
     *
     * @param string $question
     * @return string
     */
    private function askTimeClarification(string $question): string
    {
        return "Zaman dövrü birmənalı deyil. Xahiş edirəm daha dəqiq göstərəsiniz:\n\n" .
               "Məsələn:\n" .
               "• \"Bu ay\" - cari ayın statistikaları\n" .
               "• \"Keçən ay\" - ötən ayın statistikaları\n" .
               "• \"Son 30 gün\" - son 30 günün məlumatları\n" .
               "• \"Bu il\" - cari ilin məlumatları\n" .
               "• \"Keçən il\" - ötən ilin məlumatları\n\n" .
               "Həmçinin, hansı tarixə əsasən soruşursunuz?\n" .
               "• Yaranma tarixi (created_at)\n" .
               "• Ödəniş tarixi (paid_at)\n" .
               "• ƏDV tarixi (vat_date)\n\n" .
               "Məsələn: \"Bu ay ödənilmiş işlərin statistikası\" və ya \"Keçən ay yaradılmış qaimələr\"";
    }

    /**
     * Get aggregated works statistics
     * READ-ONLY - only SELECT queries with aggregation
     * OPTIMIZED: Uses direct DB queries, no joins, selects only needed columns
     *
     * @param array|null $timeRange
     * @return array
     */
    private function getWorksStats(?array $timeRange = null): array
    {
        $dateField = $this->validateDateField($timeRange['field'] ?? 'created_at');
        
        // OPTIMIZATION: Single query with CASE statements for multiple aggregations
        // This reduces from 4+ queries to 1 query
        if ($timeRange) {
            $combinedStats = DB::selectOne("
                SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN status IS NOT NULL THEN 1 END) as status_total,
                    COUNT(CASE WHEN department_id IS NOT NULL THEN 1 END) as dept_total,
                    COUNT(CASE WHEN payment_method IS NOT NULL THEN 1 END) as payment_total
                FROM works 
                WHERE {$dateField} BETWEEN ? AND ?
            ", [
                $timeRange['from']->format('Y-m-d H:i:s'),
                $timeRange['to']->format('Y-m-d H:i:s')
            ]);
        } else {
            $combinedStats = DB::selectOne("
                SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN status IS NOT NULL THEN 1 END) as status_total,
                    COUNT(CASE WHEN department_id IS NOT NULL THEN 1 END) as dept_total,
                    COUNT(CASE WHEN payment_method IS NOT NULL THEN 1 END) as payment_total
                FROM works
            ");
        }

        $totalCount = $combinedStats[0]->total ?? 0;

        // Status distribution - optimized: single query with GROUP BY
        if ($timeRange) {
            $statusResults = DB::select("
                SELECT status, COUNT(*) as count 
                FROM works 
                WHERE {$dateField} BETWEEN ? AND ?
                AND status IS NOT NULL
                GROUP BY status
            ", [
                $timeRange['from']->format('Y-m-d H:i:s'),
                $timeRange['to']->format('Y-m-d H:i:s')
            ]);
        } else {
            $statusResults = DB::select("
                SELECT status, COUNT(*) as count 
                FROM works 
                WHERE status IS NOT NULL
                GROUP BY status
            ");
        }
        $statusCounts = array_column($statusResults, 'count', 'status');

        // Works by department - no join needed, just department_id grouping
        if ($timeRange) {
            $deptResults = DB::select("
                SELECT department_id, COUNT(*) as count 
                FROM works 
                WHERE {$dateField} BETWEEN ? AND ?
                AND department_id IS NOT NULL
                GROUP BY department_id
            ", [
                $timeRange['from']->format('Y-m-d H:i:s'),
                $timeRange['to']->format('Y-m-d H:i:s')
            ]);
        } else {
            $deptResults = DB::select("
                SELECT department_id, COUNT(*) as count 
                FROM works 
                WHERE department_id IS NOT NULL
                GROUP BY department_id
            ");
        }
        $byDepartment = array_column($deptResults, 'count', 'department_id');

        // Works by payment method
        if ($timeRange) {
            $paymentResults = DB::select("
                SELECT payment_method, COUNT(*) as count 
                FROM works 
                WHERE {$dateField} BETWEEN ? AND ?
                AND payment_method IS NOT NULL
                GROUP BY payment_method
            ", [
                $timeRange['from']->format('Y-m-d H:i:s'),
                $timeRange['to']->format('Y-m-d H:i:s')
            ]);
        } else {
            $paymentResults = DB::select("
                SELECT payment_method, COUNT(*) as count 
                FROM works 
                WHERE payment_method IS NOT NULL
                GROUP BY payment_method
            ");
        }
        $byPaymentMethod = array_column($paymentResults, 'count', 'payment_method');

        // Comparison with previous period if time range is specified
        $previousPeriod = null;
        if ($timeRange) {
            $daysDiff = $timeRange['from']->diffInDays($timeRange['to']);
            $previousFrom = $timeRange['from']->copy()->subDays($daysDiff + 1);
            $previousTo = $timeRange['from']->copy()->subDay();
            
            // Single query for previous period count
            $prevCount = DB::selectOne("
                SELECT COUNT(*) as count 
                FROM works 
                WHERE {$dateField} BETWEEN ? AND ?
            ", [$previousFrom->format('Y-m-d H:i:s'), $previousTo->format('Y-m-d H:i:s')]);
            
            $previousPeriod = [
                'from' => $previousFrom->format('Y-m-d'),
                'to' => $previousTo->format('Y-m-d'),
                'count' => $prevCount->count ?? 0,
            ];
        } else {
            // Default: current and last month - optimized with single query using CASE
            $currentMonth = now()->startOfMonth();
            $lastMonthStart = now()->subMonth()->startOfMonth();
            $lastMonthEnd = now()->subMonth()->endOfMonth();
            
            $monthlyStats = DB::selectOne("
                SELECT 
                    COUNT(CASE WHEN {$dateField} >= ? THEN 1 END) as current_month,
                    COUNT(CASE WHEN {$dateField} BETWEEN ? AND ? THEN 1 END) as last_month
                FROM works
            ", [
                $currentMonth->format('Y-m-d H:i:s'),
                $lastMonthStart->format('Y-m-d H:i:s'),
                $lastMonthEnd->format('Y-m-d H:i:s')
            ]);
            
            $previousPeriod = [
                'current_month' => $monthlyStats->current_month ?? 0,
                'last_month' => $monthlyStats->last_month ?? 0,
            ];
        }

        return [
            'total' => $totalCount,
            'by_status' => $statusCounts,
            'by_department' => $byDepartment,
            'by_payment_method' => $byPaymentMethod,
            'date_field' => $dateField,
            'previous_period' => $previousPeriod,
        ];
    }

    /**
     * Get aggregated invoices statistics
     * READ-ONLY - only SELECT queries with aggregation
     * OPTIMIZED: Single query for counts/amounts, no query cloning
     *
     * @param array|null $timeRange
     * @return array
     */
    private function getInvoicesStats(?array $timeRange = null): array
    {
        $dateField = $this->validateDateField($timeRange['field'] ?? 'created_at');
        
        // OPTIMIZATION: Single query with CASE statements for all counts and amounts
        if ($timeRange) {
            $stats = DB::selectOne("
                SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN is_signed = 1 THEN 1 END) as signed,
                    COUNT(CASE WHEN is_signed = 0 THEN 1 END) as unsigned,
                    COALESCE(SUM(total_amount), 0) as total_amount,
                    COALESCE(SUM(CASE WHEN is_signed = 1 THEN total_amount END), 0) as signed_amount,
                    COALESCE(SUM(CASE WHEN is_signed = 0 THEN total_amount END), 0) as unsigned_amount
                FROM invoices 
                WHERE {$dateField} BETWEEN ? AND ?
            ", [
                $timeRange['from']->format('Y-m-d H:i:s'),
                $timeRange['to']->format('Y-m-d H:i:s')
            ]);
        } else {
            $stats = DB::selectOne("
                SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN is_signed = 1 THEN 1 END) as signed,
                    COUNT(CASE WHEN is_signed = 0 THEN 1 END) as unsigned,
                    COALESCE(SUM(total_amount), 0) as total_amount,
                    COALESCE(SUM(CASE WHEN is_signed = 1 THEN total_amount END), 0) as signed_amount,
                    COALESCE(SUM(CASE WHEN is_signed = 0 THEN total_amount END), 0) as unsigned_amount
                FROM invoices
            ");
        }

        $total = $stats->total ?? 0;
        $totalAmount = (float)($stats->total_amount ?? 0);
        $signedAmount = (float)($stats->signed_amount ?? 0);
        $unsignedAmount = (float)($stats->unsigned_amount ?? 0);

        // Amount by company - single query with GROUP BY
        if ($timeRange) {
            $byCompany = DB::select("
                SELECT company, COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total_amount
                FROM invoices 
                WHERE {$dateField} BETWEEN ? AND ?
                AND company IS NOT NULL
                GROUP BY company
            ", [
                $timeRange['from']->format('Y-m-d H:i:s'),
                $timeRange['to']->format('Y-m-d H:i:s')
            ]);
        } else {
            $byCompany = DB::select("
                SELECT company, COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total_amount
                FROM invoices 
                WHERE company IS NOT NULL
                GROUP BY company
            ");
        }
        $byCompany = array_map(function ($item) {
            return [
                'count' => $item->count,
                'total_amount' => (float)$item->total_amount,
            ];
        }, $byCompany);

        // Comparison with previous period
        $previousPeriod = null;
        if ($timeRange) {
            $daysDiff = $timeRange['from']->diffInDays($timeRange['to']);
            $previousFrom = $timeRange['from']->copy()->subDays($daysDiff + 1);
            $previousTo = $timeRange['from']->copy()->subDay();
            
            // Single query for previous period
            $prevStats = DB::selectOne("
                SELECT 
                    COUNT(*) as count,
                    COALESCE(SUM(total_amount), 0) as amount
                FROM invoices 
                WHERE {$dateField} BETWEEN ? AND ?
            ", [$previousFrom->format('Y-m-d H:i:s'), $previousTo->format('Y-m-d H:i:s')]);
            
            $previousPeriod = [
                'from' => $previousFrom->format('Y-m-d'),
                'to' => $previousTo->format('Y-m-d'),
                'count' => $prevStats->count ?? 0,
                'amount' => (float)($prevStats->amount ?? 0),
            ];
        }

        return [
            'total' => $total,
            'signed' => $stats->signed ?? 0,
            'unsigned' => $stats->unsigned ?? 0,
            'total_amount' => $totalAmount,
            'signed_amount' => $signedAmount,
            'unsigned_amount' => $unsignedAmount,
            'avg_amount' => $total > 0 ? round($totalAmount / $total, 2) : 0,
            'by_company' => $byCompany,
            'date_field' => $dateField,
            'previous_period' => $previousPeriod,
        ];
    }

    /**
     * Get aggregated transactions (payments) statistics
     * READ-ONLY - only SELECT queries with aggregation
     * OPTIMIZED: Single queries with CASE statements, no joins
     *
     * @param array|null $timeRange
     * @return array
     */
    private function getTransactionsStats(?array $timeRange = null): array
    {
        // Transactions use transaction_date field
        $dateField = $this->validateDateField($timeRange['field'] ?? 'transaction_date');

        // OPTIMIZATION: Single query for all basic aggregations
        if ($timeRange) {
            $stats = DB::selectOne("
                SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN type = ? THEN 1 END) as income_count,
                    COUNT(CASE WHEN type = ? THEN 1 END) as expense_count,
                    COALESCE(SUM(CASE WHEN type = ? THEN amount END), 0) as income_total,
                    COALESCE(SUM(CASE WHEN type = ? THEN amount END), 0) as expense_total,
                    COUNT(CASE WHEN work_id IS NOT NULL THEN 1 END) as with_work,
                    COUNT(CASE WHEN work_id IS NULL THEN 1 END) as without_work
                FROM transactions 
                WHERE {$dateField} BETWEEN ? AND ?
            ", [
                Transaction::INCOME,
                Transaction::EXPENSE,
                Transaction::INCOME,
                Transaction::EXPENSE,
                $timeRange['from']->format('Y-m-d H:i:s'),
                $timeRange['to']->format('Y-m-d H:i:s')
            ]);
        } else {
            $stats = DB::selectOne("
                SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN type = ? THEN 1 END) as income_count,
                    COUNT(CASE WHEN type = ? THEN 1 END) as expense_count,
                    COALESCE(SUM(CASE WHEN type = ? THEN amount END), 0) as income_total,
                    COALESCE(SUM(CASE WHEN type = ? THEN amount END), 0) as expense_total,
                    COUNT(CASE WHEN work_id IS NOT NULL THEN 1 END) as with_work,
                    COUNT(CASE WHEN work_id IS NULL THEN 1 END) as without_work
                FROM transactions
            ", [
                Transaction::INCOME,
                Transaction::EXPENSE,
                Transaction::INCOME,
                Transaction::EXPENSE
            ]);
        }

        $incomeTotal = (float)($stats->income_total ?? 0);
        $expenseTotal = (float)($stats->expense_total ?? 0);
        $incomeCount = $stats->income_count ?? 0;
        $expenseCount = $stats->expense_count ?? 0;

        // By type with status - single query
        if ($timeRange) {
            $typeStatusResults = DB::select("
                SELECT 
                    type,
                    status,
                    COUNT(*) as count,
                    COALESCE(SUM(amount), 0) as total
                FROM transactions 
                WHERE {$dateField} BETWEEN ? AND ?
                GROUP BY type, status
            ", [
                $timeRange['from']->format('Y-m-d H:i:s'),
                $timeRange['to']->format('Y-m-d H:i:s')
            ]);
        } else {
            $typeStatusResults = DB::select("
                SELECT 
                    type,
                    status,
                    COUNT(*) as count,
                    COALESCE(SUM(amount), 0) as total
                FROM transactions 
                GROUP BY type, status
            ");
        }
        $byTypeAndStatus = array_map(function ($t) {
            return [
                'type' => $t->type === Transaction::INCOME ? 'income' : 'expense',
                'status' => $t->status === Transaction::SUCCESSFUL ? 'successful' : 'returned',
                'count' => $t->count,
                'total' => (float)$t->total,
            ];
        }, $typeStatusResults);

        // By currency - single query
        if ($timeRange) {
            $byCurrency = DB::select("
                SELECT 
                    currency,
                    COUNT(*) as count,
                    COALESCE(SUM(amount), 0) as total
                FROM transactions 
                WHERE {$dateField} BETWEEN ? AND ?
                AND currency IS NOT NULL
                GROUP BY currency
            ", [
                $timeRange['from']->format('Y-m-d H:i:s'),
                $timeRange['to']->format('Y-m-d H:i:s')
            ]);
        } else {
            $byCurrency = DB::select("
                SELECT 
                    currency,
                    COUNT(*) as count,
                    COALESCE(SUM(amount), 0) as total
                FROM transactions 
                WHERE currency IS NOT NULL
                GROUP BY currency
            ");
        }
        $byCurrency = array_map(function ($t) {
            return [
                'currency' => $t->currency,
                'count' => $t->count,
                'total' => (float)$t->total,
            ];
        }, $byCurrency);

        // Comparison with previous period
        $previousPeriod = null;
        if ($timeRange) {
            $daysDiff = $timeRange['from']->diffInDays($timeRange['to']);
            $previousFrom = $timeRange['from']->copy()->subDays($daysDiff + 1);
            $previousTo = $timeRange['from']->copy()->subDay();
            
            // Single query for previous period
            $prevStats = DB::selectOne("
                SELECT 
                    COALESCE(SUM(CASE WHEN type = ? THEN amount END), 0) as income,
                    COALESCE(SUM(CASE WHEN type = ? THEN amount END), 0) as expense
                FROM transactions 
                WHERE {$dateField} BETWEEN ? AND ?
            ", [
                Transaction::INCOME,
                Transaction::EXPENSE,
                $previousFrom->format('Y-m-d H:i:s'),
                $previousTo->format('Y-m-d H:i:s')
            ]);
            
            $prevIncome = (float)($prevStats->income ?? 0);
            $prevExpense = (float)($prevStats->expense ?? 0);
            
            $previousPeriod = [
                'from' => $previousFrom->format('Y-m-d'),
                'to' => $previousTo->format('Y-m-d'),
                'income' => $prevIncome,
                'expense' => $prevExpense,
                'net' => (float)($prevIncome - $prevExpense),
            ];
        }

        return [
            'total' => $stats->total ?? 0,
            'income' => [
                'count' => $incomeCount,
                'total' => $incomeTotal,
                'avg' => $incomeCount > 0 ? round($incomeTotal / $incomeCount, 2) : 0,
            ],
            'expense' => [
                'count' => $expenseCount,
                'total' => $expenseTotal,
                'avg' => $expenseCount > 0 ? round($expenseTotal / $expenseCount, 2) : 0,
            ],
            'net' => (float)($incomeTotal - $expenseTotal),
            'by_type_and_status' => $byTypeAndStatus,
            'by_currency' => $byCurrency,
            'with_work' => $stats->with_work ?? 0,
            'without_work' => $stats->without_work ?? 0,
            'date_field' => $dateField,
            'previous_period' => $previousPeriod,
        ];
    }

    /**
     * Build system prompt with rules and intent-specific focus
     *
     * @param string $intent
     * @return string
     */
    private function buildSystemPrompt(string $intent): string
    {
        $intentInstructions = $this->getIntentInstructions($intent);

        return "Sən yüksək səviyyəli idarəetmə analitikisidir.

ROLU:
- Məlumatları aydın və sadə biznes dilində izah et
- Strukturlaşdırılmış və qısa cavablar ver
- Yalnız verilən statistik məlumatlara əsaslan

FOKUS SAHƏSİ: {$intentInstructions}

KRİTİK TƏHLÜKƏSİZLİK QAYDALARI (MƏCБУRİ):
1. YALNIZ VERİLƏN MƏLUMATLAR: Sualı cavablandırmaq üçün lazım olan məlumatlar verilən kontekstdə YOXDURSA, sadəcə \"Məlumat mövcud deyil\" və ya \"Bu məlumat verilən statistikada yoxdur\" deyə cavab ver.

2. HEÇ VAXT RƏQƏM UYDURMA: Verilən kontekstdə olmayan rəqəm, statistik və ya məlumat YARATMƏ və ya TƏXMİN ETMƏ. Yalnız JSON-də olan dəqiq rəqəmlərdən istifadə et.

3. TREND TƏXMİNİ YASAQ: Məlumatlar olmadan trend, artım, azalma və ya dəyişiklik haqqında FƏRZ ETMƏ. Yalnız verilən previous_period məlumatı varsa müqayisə et.

4. KONTEKST XARİCİNDƏ CAVAB VERMƏ: Verilən JSON kontekstində olmayan məlumat haqqında heç nə yazma. Yalnız və yalnız kontekstdə olan məlumatlardan istifadə et.

5. VERİLƏNLƏR BAZASI DƏYİŞİKLİKLƏRİ: Heç bir yazma əməliyyatı təklif etmə və ya yerinə yetirmə.

QAYDALAR:
- Əgər sual üçün kifayət qədər məlumat yoxdursa: Bu məlumat verilən statistikada yoxdur de
- Əgər rəqəm lazımdır amma kontekstdə yoxdursa: Bu rəqəm mövcud deyil de
- Əgər trend soruşulursa amma müqayisə məlumatı yoxdursa: Trend müəyyənləşdirmək üçün kifayət qədər məlumat yoxdur de

CAVAB FORMATI (MƏCБУRİ):
1. MADDƏLƏR (BULLET POINTS):
   - İstifadə et: • və ya - simvolları
   - Hər maddəni qısa tut (1-2 cümlə)
   - Uzun abzaslardan qaçın

2. ƏSAS RƏQƏMLƏRİ VURĞULAMA:
   - Mühüm rəqəmləri **qalın** və ya dırnaq işarəsi içində göstər
   - Məsələn: Ümumi məbləğ **15,450.50 AZN**-dir və ya İş sayı: **127**

3. QISA NƏTİCƏ:
   - Cavabın sonunda qısa nəticə bölməsi əlavə et
   - Format: Nəticə: və ya Xülasə:
   - 1-2 cümlədə əsas nəticəni ümumiləşdir

4. UZUN ABZASLARDAN QAÇINMA:
   - Hər abzas maksimum 3-4 cümlə
   - Əvəzinə maddələrlə təqdim et
   - Oxunuşu asanlaşdır

NÜMUNƏ FORMAT:
• Birinci əsas məlumat: **123** (qısa izah)
• İkinci məlumat: **456** (qısa izah)
• Üçüncü məlumat: **789** (qısa izah)

Nəticə: [Qısa ümumiləşdirmə 1-2 cümlədə] (yalnız kontekstdə olanları)

VERİLƏN MƏLUMATLAR:
- İşlər (Works): ümumi say, status paylanması, şöbə, ödəniş metodu, aylıq statistikalar
- Qaimələr (Invoices): say, imza statusu, məbləğ statistikaları, şirkət paylanması, aylıq məlumatlar
- Ödənişlər (Transactions/Payments): gəlir/xərc, status, valyuta, işlərlə əlaqə, aylıq statistikalar

XATIRLATMA: Yalnız JSON kontekstində olan məlumatlardan istifadə et. Heç bir məlumat uydurma və ya təxmin etmə.

Cavablarını Azərbaycan dilində, biznes rəhbərləri üçün anlaşıqlı formada ver.";
    }

    /**
     * Get intent-specific instructions
     *
     * @param string $intent
     * @return string
     */
    private function getIntentInstructions(string $intent): string
    {
        return match($intent) {
            'finance' => 'Maliyyə suallarına cavab verərkən qaimələr, ödənişlər, gəlir/xərc məbləğləri, maliyyə statistikaları və trendlərinə diqqət yetir.',
            'operations' => 'Əməliyyat suallarına cavab verərkən iş statusları, şöbə paylanması, proses statistikaları, iş axını və əməliyyat məlumatlarına fokuslan.',
            'risk' => 'Risk suallarına cavab verərkən problemli işlər, qayıtmalar, gecikmələr, problem statusları və risk göstəricilərinə diqqət yetir.',
            'performance' => 'Performans suallarına cavab verərkən statistikalar, trendlər, müqayisələr, artım/azalma tempi, məhsuldarlıq göstəricilərinə fokuslan.',
            default => 'Sualın növünə uyğun cavab ver.',
        };
    }

    /**
     * Build user prompt with question, context, intent, and time range
     *
     * @param string $question
     * @param array $context
     * @param string $intent
     * @param array|null $timeRange
     * @return string
     */
    private function buildUserPrompt(string $question, array $context, string $intent, ?array $timeRange = null): string
    {
        // Filter context based on intent for more focused responses
        $filteredContext = $this->filterContextByIntent($context, $intent);

        // Validate context has required data
        $dataAvailability = $this->checkDataAvailability($question, $filteredContext);

        // Format context as compact JSON for shorter prompt
        $contextJson = json_encode($filteredContext, JSON_UNESCAPED_UNICODE);

        $intentLabel = match($intent) {
            'finance' => 'Maliyyə',
            'operations' => 'Əməliyyatlar',
            'risk' => 'Risk',
            'performance' => 'Performans',
            default => 'Ümumi',
        };

        $timeRangeInfo = '';
        if ($timeRange) {
            $timeRangeInfo = "\n\nZAMAN DÖVRÜ: " . ($timeRange['label'] ?? 'Müəyyən edilmiş dövr') .
                           " ({$timeRange['from']->format('Y-m-d')} - {$timeRange['to']->format('Y-m-d')})" .
                           "\nTarix sahəsi: " . ($timeRange['field'] ?? 'created_at');
        }

        return "Sual ({$intentLabel}): {$question}{$timeRangeInfo}\n\n" .
               "MÖVCUD STATİSTİK MƏLUMATLAR (YALNIZ BU MƏLUMATLARDAN İSTİFADƏ ET):\n" .
               "{$contextJson}\n\n" .
               "MƏLUMAT MÖVCUDLUĞU YOXLAMASI:\n" .
               "{$dataAvailability}\n\n" .
               "MƏCБУRİ TƏLƏBLƏR:\n" .
               "1. YALNIZ JSON-DƏ OLAN RƏQƏM VƏ STATİSTİKALAR: JSON kontekstində olmayan heç bir rəqəm yaratma və ya təxmin etmə\n" .
               "2. MƏLUMAT YOXDURSA AÇIQ BİLDİR: Əgər sual üçün lazım olan məlumat JSON-də yoxdursa, sadəcə \"Məlumat mövcud deyil\" de\n" .
               "3. TREND YASAQDIR: previous_period məlumatı yoxdursa trend, artım, azalma haqqında heç nə yazma\n" .
               "4. KONTEKST XARİCİNDƏ MƏLUMAT VERMƏ: JSON-də olmayan məlumat haqqında heç nə yazma\n" .
               "5. DƏQİQ RƏQƏMLƏR: Yalnız JSON-də olan dəqiq rəqəmləri göstər, yuvarlaqlaşdırma və ya təxmin etmə\n\n" .
               "Cavab formatı (MƏCБУRİ):\n" .
               "1. Maddələrlə (bullet points) təqdim et:\n" .
               "   • Hər məqamı ayrı maddədə göstər\n" .
               "   • Məsələn: \"• Ümumi iş sayı: **150**\"\n" .
               "   • Uzun abzaslardan qaçın (maksimum 3-4 cümlə)\n\n" .
               "2. Əsas rəqəmləri vurğula:\n" .
               "   • **qalın** yazı ilə və ya \"dırnaq işarəsi\" içində\n" .
               "   • Məsələn: **15,450.50 AZN** və ya \"127 iş\"\n\n" .
               "3. Sonunda qısa nəticə əlavə et:\n" .
               "   • Format: \"Nəticə: [1-2 cümlədə ümumiləşdirmə]\"\n" .
               "   • Əsas nəticələri ümumiləşdir\n\n" .
               "4. {$intentLabel} perspektivindən cavab ver" .
               ($timeRange ? "\n5. Zaman dövrü məlumatlarına diqqət yetir" : '') .
               "\n6. Əgər məlumat yoxdursa, sadəcə \"Məlumat mövcud deyil\" de";
    }

    /**
     * Check data availability for the question
     * Returns a summary of what data is available/not available
     *
     * @param string $question
     * @param array $context
     * @return string
     */
    private function checkDataAvailability(string $question, array $context): string
    {
        $questionLower = mb_strtolower($question);
        $checks = [];

        // Check for works data
        if (mb_strpos($questionLower, 'iş') !== false || mb_strpos($questionLower, 'work') !== false) {
            $hasWorks = isset($context['works']['total']) && $context['works']['total'] > 0;
            $checks[] = $hasWorks ? "✓ İşlər statistikası mövcuddur" : "✗ İşlər statistikası mövcud deyil";
        }

        // Check for invoice data
        if (mb_strpos($questionLower, 'qaimə') !== false || mb_strpos($questionLower, 'invoice') !== false) {
            $hasInvoices = isset($context['invoices']['total']) && $context['invoices']['total'] > 0;
            $checks[] = $hasInvoices ? "✓ Qaimələr statistikası mövcuddur" : "✗ Qaimələr statistikası mövcud deyil";
        }

        // Check for transaction/payment data
        if (mb_strpos($questionLower, 'ödəniş') !== false || 
            mb_strpos($questionLower, 'payment') !== false || 
            mb_strpos($questionLower, 'transaction') !== false ||
            mb_strpos($questionLower, 'gəlir') !== false ||
            mb_strpos($questionLower, 'xərc') !== false) {
            $hasTransactions = isset($context['transactions']['total']) && $context['transactions']['total'] > 0;
            $checks[] = $hasTransactions ? "✓ Ödənişlər statistikası mövcuddur" : "✗ Ödənişlər statistikası mövcud deyil";
        }

        // Check for comparison/trend data
        if (mb_strpos($questionLower, 'trend') !== false || 
            mb_strpos($questionLower, 'müqayisə') !== false ||
            mb_strpos($questionLower, 'artım') !== false ||
            mb_strpos($questionLower, 'azalma') !== false ||
            mb_strpos($questionLower, 'dəyişiklik') !== false) {
            $hasComparison = false;
            foreach (['works', 'invoices', 'transactions'] as $key) {
                if (isset($context[$key]['previous_period']) && !empty($context[$key]['previous_period'])) {
                    $hasComparison = true;
                    break;
                }
            }
            $checks[] = $hasComparison ? "✓ Müqayisə məlumatı (previous_period) mövcuddur" : "✗ Müqayisə məlumatı mövcud deyil - trend haqqında heç nə yazma";
        }

        return empty($checks) ? "Məlumat yoxlanılır..." : implode("\n", $checks);
    }

    /**
     * Filter context data based on intent to focus on relevant information
     *
     * @param array $context
     * @param string $intent
     * @return array
     */
    private function filterContextByIntent(array $context, string $intent): array
    {
        // Always include all data, but we can prioritize certain sections in the future
        // For now, return full context but the prompt will guide focus
        return $context;
    }

    /**
     * Call OpenAI API
     *
     * @param string $systemPrompt
     * @param string $userPrompt
     * @return string
     */
    private function callOpenAI(string $systemPrompt, string $userPrompt): string
    {
        if (empty($this->openAiApiKey)) {
            throw new \RuntimeException('OpenAI API key is not configured. Please set OPENAI_API_KEY in your .env file.');
        }
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openAiApiKey,
            'Content-Type' => 'application/json',
        ])->post($this->openAiApiUrl, [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt
                ]
            ],
            'temperature' => 0.3, // Lower temperature for more factual, less creative responses
            'max_tokens' => 1000,
        ]);

        if (!$response->successful()) {
            $error = $response->json();
            throw new \RuntimeException(
                'OpenAI API Error: ' . ($error['error']['message'] ?? 'Unknown error')
            );
        }

        $responseData = $response->json();
        
        return $responseData['choices'][0]['message']['content'] ?? 'Cavab alına bilmədi.';
    }
}
