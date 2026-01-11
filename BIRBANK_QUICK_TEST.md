# Birbank İnteqrasiyası - Sürətli Test Qaydaları

## ✅ 1. Route-ları Yoxlamaq

```bash
php artisan route:list | grep birbank
```

**Gözlənilən nəticə:**
```
GET|HEAD  birbank ................ birbank.index
GET|HEAD  birbank/{company} ...... birbank.show
POST      birbank/{company}/login  birbank.login
POST      birbank/{company}/sync-transactions birbank.sync-transactions
```

## ✅ 2. Database Cədvəlləri Yoxlamaq

```bash
php artisan tinker
```

Sonra:
```php
// Cədvəllərin mövcudluğunu yoxla
Schema::hasTable('birbank_credentials'); // true olmalıdır
Schema::hasTable('birbank_transactions'); // true olmalıdır

// Model-ləri test et
App\Models\BirbankCredential::count();
App\Models\BirbankTransaction::count();
```

## ✅ 3. Browser-də Yoxlamaq

1. **Ana səhifə:**
   - URL: `http://your-app.test/birbank`
   - Gözlənilən: Statistika kartları və şirkətlər siyahısı

2. **Şirkət detalları:**
   - URL: `http://your-app.test/birbank/1?env=test`
   - Gözlənilən: Login form, sync form, transaction-lar cədvəli

## ✅ 4. Sidebar-da Yoxlamaq

1. Login olun
2. Sol sidebar-da "Maliyyə" bölməsinə baxın
3. "Birbank İnteqrasiyası" linki görünməlidir

## ✅ 5. Controller Test

```bash
php artisan tinker
```

```php
// Controller-i test et
$controller = new App\Http\Controllers\Modules\BirbankController();
$company = App\Models\Company::first();
$request = new Illuminate\Http\Request(['env' => 'test']);

// Index metodu
$controller->index($request);

// Show metodu
$controller->show($company, $request);
```

## ✅ 6. Artisan Command-ları Test

```bash
# Status yoxlama
php artisan birbank:check-status 1 --environment=test

# Login test (credentials ilə)
php artisan birbank:test-login 1 --username="test" --password="test" --environment=test
```

## ✅ 7. View-ları Yoxlamaq

```bash
# View fayllarının mövcudluğunu yoxla
ls -la resources/views/pages/birbank/
```

**Gözlənilən fayllar:**
- `index.blade.php`
- `show.blade.php`

## ✅ 8. Config Yoxlamaq

```bash
php artisan tinker
```

```php
config('birbank.base_url_test'); // https://pre-my.birbank.business
config('birbank.default_env'); // test
config('birbank.endpoints.login'); // /api/b2b/login
```

## ✅ 9. Full Test Skenari

1. **Browser-də aç:** `/birbank`
2. **Şirkət seç:** `/birbank/1?env=test`
3. **Login form doldur:**
   - Username: `0185231PORTAL`
   - Password: `123456Aa!`
4. **Login düyməsinə bas**
5. **Nəticəni yoxla:**
   - Uğurlu olsa: Token status "Aktiv" olmalıdır
   - Uğursuz olsa: Error mesajı görünməlidir

## ✅ 10. Common Issues və Həlləri

### Problem: Route tapılmır
**Həll:**
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### Problem: View tapılmır
**Həll:**
```bash
# View fayllarının mövcudluğunu yoxla
ls resources/views/pages/birbank/
```

### Problem: Database cədvəli yoxdur
**Həll:**
```bash
php artisan migrate
```

### Problem: Sidebar-da görünmür
**Həll:**
- Permission yoxla: `viewAny-financeClient`
- Cache təmizlə: `php artisan view:clear`

## ✅ 11. Quick Health Check Script

```bash
#!/bin/bash
echo "=== Birbank Health Check ==="
echo "1. Routes:"
php artisan route:list | grep birbank | wc -l
echo "2. Database tables:"
php artisan tinker --execute="echo Schema::hasTable('birbank_credentials') ? 'OK' : 'FAIL';"
echo "3. View files:"
ls resources/views/pages/birbank/ 2>/dev/null | wc -l
echo "4. Config:"
php artisan tinker --execute="echo config('birbank.default_env');"
```

## ✅ 12. Browser Console-da Yoxlamaq

Browser-də açıb Developer Tools-da:
```javascript
// AJAX request-ləri izlə
// Network tab-da /birbank request-lərini yoxla
```

## ✅ 13. Log Yoxlamaq

```bash
# Son log-ları gör
tail -50 storage/logs/laravel-$(date +%Y-%m-%d).log | grep -i birbank
```

## ✅ 14. Permission Yoxlamaq

```bash
php artisan tinker
```

```php
$user = Auth::user();
$user->can('viewAny-financeClient'); // true olmalıdır
```

---

## 🎯 Ən Asan Test Yolu

1. Browser-də aç: `http://your-app.test/birbank`
2. Görünürsə → ✅ İşləyir!
3. Görünmürsə → Route və permission yoxla

