<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyBankAccount extends Model
{
    protected $fillable = [
        'slug',
        'label',
        'company_id',
        'company_display_name',
        'voen',
        'hh',
        'mh',
        'bank_name',
        'bank_kod',
        'bank_voen',
        'swift',
        'who',
        'who_footer',
        'representer',
        'stamp',
    ];

    // Company bağlanmamış hesablar üçün köhnə ƏDV-li siyahı (fallback)
    const LEGACY_VAT_SLUGS = [
        'mbrokerRespublika',
        'mtechnologiesRespublika',
        'garantRespublika',
        'garantKapital',
        'mbrokerKapital',
        'mtechnologiesKapital',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * ƏDV statusu Companies bölməsindəki has_no_vat-dan götürülür,
     * company bağlanmayıbsa köhnə siyahıya baxılır
     */
    public function hasVat(): bool
    {
        if ($this->company_id && $this->company) {
            return !$this->company->hasNoVat();
        }

        return in_array($this->slug, self::LEGACY_VAT_SLUGS);
    }

    public static function slugHasVat(?string $slug): bool
    {
        $account = $slug ? static::with('company')->where('slug', $slug)->first() : null;

        return $account ? $account->hasVat() : in_array($slug, self::LEGACY_VAT_SLUGS);
    }

    /**
     * Hesab-fakturada göstərilən şirkət adı
     */
    public function displayName(): string
    {
        return $this->company_display_name
            ?: ('"' . optional($this->company)->name . '" MMC');
    }

    /**
     * slug => rekvizitlər (hesab-faktura JS-i üçün).
     * Rekvizitlər yalnız DB-dən götürülür ki, Bank hesabları bölməsində
     * əlavə/redaktə olunan hesablar seçiləndə də düzgün dolsun.
     */
    public static function requisitesMap(): array
    {
        return static::with('company')->get()
            ->mapWithKeys(fn ($account) => [$account->slug => [
                'name'        => $account->displayName(),
                'voen'        => $account->voen ?? '',
                'hh'          => $account->hh ?? '',
                'mh'          => $account->mh ?? '',
                'bank'        => $account->bank_name ?? '',
                'kod'         => $account->bank_kod ?? '',
                'bvoen'       => $account->bank_voen ?? '',
                'swift'       => $account->swift ?? '',
                'who'         => $account->who ?? '',
                'whoFooter'   => $account->who_footer ?? '',
                'representer' => $account->representer ?: 'Gömrük Təmsilçisi',
                'stamp'       => asset($account->stamp ?: 'assets/images/finance/default-stamp.jpeg'),
                'vat'         => $account->hasVat(),
            ]])
            ->all();
    }

    /**
     * slug => ƏDV-li olub-olmaması (JS üçün)
     */
    public static function vatMap(): array
    {
        return static::with('company')->get()
            ->mapWithKeys(fn ($account) => [$account->slug => $account->hasVat()])
            ->all();
    }
}
