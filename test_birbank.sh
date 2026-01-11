#!/bin/bash

echo "=========================================="
echo "  Birbank İnteqrasiyası - Test Script"
echo "=========================================="
echo ""

echo "✅ 1. Route-ları yoxlayıram..."
ROUTE_COUNT=$(php artisan route:list 2>/dev/null | grep -c birbank || echo "0")
if [ "$ROUTE_COUNT" -gt "0" ]; then
    echo "   ✓ $ROUTE_COUNT route tapıldı"
else
    echo "   ✗ Route-lar tapılmadı"
fi
echo ""

echo "✅ 2. Database cədvəllərini yoxlayıram..."
php artisan tinker --execute="
    echo Schema::hasTable('birbank_credentials') ? '   ✓ birbank_credentials cədvəli var' : '   ✗ birbank_credentials cədvəli yoxdur';
    echo PHP_EOL;
    echo Schema::hasTable('birbank_transactions') ? '   ✓ birbank_transactions cədvəli var' : '   ✗ birbank_transactions cədvəli yoxdur';
" 2>/dev/null
echo ""

echo "✅ 3. View fayllarını yoxlayıram..."
if [ -f "resources/views/pages/birbank/index.blade.php" ]; then
    echo "   ✓ index.blade.php var"
else
    echo "   ✗ index.blade.php yoxdur"
fi

if [ -f "resources/views/pages/birbank/show.blade.php" ]; then
    echo "   ✓ show.blade.php var"
else
    echo "   ✗ show.blade.php yoxdur"
fi
echo ""

echo "✅ 4. Config faylını yoxlayıram..."
if [ -f "config/birbank.php" ]; then
    echo "   ✓ config/birbank.php var"
    php artisan tinker --execute="echo '   Base URL (test): ' . config('birbank.base_url_test');" 2>/dev/null
else
    echo "   ✗ config/birbank.php yoxdur"
fi
echo ""

echo "✅ 5. Controller-i yoxlayıram..."
if [ -f "app/Http/Controllers/Modules/BirbankController.php" ]; then
    echo "   ✓ BirbankController.php var"
else
    echo "   ✗ BirbankController.php yoxdur"
fi
echo ""

echo "✅ 6. Model-ləri yoxlayıram..."
if [ -f "app/Models/BirbankCredential.php" ]; then
    echo "   ✓ BirbankCredential.php var"
else
    echo "   ✗ BirbankCredential.php yoxdur"
fi

if [ -f "app/Models/BirbankTransaction.php" ]; then
    echo "   ✓ BirbankTransaction.php var"
else
    echo "   ✗ BirbankTransaction.php yoxdur"
fi
echo ""

echo "✅ 7. Artisan command-ları yoxlayıram..."
php artisan list 2>/dev/null | grep -q "birbank:check-status" && echo "   ✓ birbank:check-status command var" || echo "   ✗ birbank:check-status command yoxdur"
php artisan list 2>/dev/null | grep -q "birbank:test-login" && echo "   ✓ birbank:test-login command var" || echo "   ✗ birbank:test-login command yoxdur"
php artisan list 2>/dev/null | grep -q "birbank:sync-transactions" && echo "   ✓ birbank:sync-transactions command var" || echo "   ✗ birbank:sync-transactions command yoxdur"
echo ""

echo "✅ 8. Sidebar komponentini yoxlayıram..."
if grep -q "Birbank İnteqrasiyası" app/View/Components/Sidebar.php 2>/dev/null; then
    echo "   ✓ Sidebar-da Birbank linki var"
else
    echo "   ✗ Sidebar-da Birbank linki yoxdur"
fi
echo ""

echo "=========================================="
echo "  Test tamamlandı!"
echo "=========================================="
echo ""
echo "📌 Browser-də test etmək üçün:"
echo "   http://your-app.test/birbank"
echo ""
echo "📌 Şirkət detalları üçün:"
echo "   http://your-app.test/birbank/1?env=test"
echo ""

