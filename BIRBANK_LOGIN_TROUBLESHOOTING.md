# Birbank Login Problemi - Troubleshooting Guide

## Problem
Giriş edə bilmirsən. Aşağıdakı addımları izləyərək problemi tap və həll et.

## Test Credentials (Şəkillərdən)
- **Username:** `0185231PORTAL`
- **Password:** `123456Aa!`
- **Environment:** `test` (default)

## Yoxlanılmalı Addımlar

### 1. Konfiqurasiya Yoxlaması
```bash
php artisan tinker --execute="echo 'Base URL Test: ' . config('birbank.base_url_test') . PHP_EOL; echo 'Login Endpoint: ' . config('birbank.endpoints.login') . PHP_EOL;"
```

Gözlənilən nəticə:
- Base URL Test: `https://pre-my.birbank.business`
- Login Endpoint: `/api/b2b/login`

### 2. Test Login Command ilə Test
```bash
php artisan birbank:test-login 1 --username="0185231PORTAL" --password="123456Aa!" --environment=test
```

Bu command:
- Login cəhdini edəcək
- Tam error mesajını göstərəcək
- API response strukturunu göstərəcək

### 3. Log-ları Yoxla
```bash
tail -100 storage/logs/laravel-$(date +%Y-%m-%d).log | grep -i birbank
```

Log-larda axtarılmalı:
- `[Birbank] Login attempt` - login cəhdi
- `[Birbank] Login response received` - API cavabı
- `[Birbank] Login failed` - xəta varsa

### 4. Mümkün Problemlər və Həlləri

#### Problem 1: SSL Certificate Xətası
**Simptom:** `cURL error 60` və ya SSL xətası

**Həll:**
`.env` faylına əlavə et:
```
BIRBANK_VERIFY_SSL=false
```

**Qeyd:** Bu yalnız test üçündür. Production-da `true` olmalıdır.

#### Problem 2: API Response Strukturu Uyğun Gəlmir
**Simptom:** "JWT token not found in login response"

**Həll:** 
- Log-larda `response_structure`-u yoxla
- API dokumentasiyası ilə müqayisə et
- `BirbankClient.php`-də token extract edən hissəni uyğunlaşdır

#### Problem 3: Network/Connection Xətası
**Simptom:** "Bağlantı xətası" və ya timeout

**Həll:**
- İnternet bağlantısını yoxla
- Firewall/proxy ayarlarını yoxla
- API URL-in düzgün olduğunu yoxla

#### Problem 4: Invalid Credentials
**Simptom:** "Invalid username or password" və ya 401 status

**Həll:**
- Username və password-u yenidən yoxla
- Test environment-da olduğunu təsdiq et
- Credentials-ların aktiv olduğunu yoxla

### 5. Manual Test (cURL ilə)
```bash
curl -X POST https://pre-my.birbank.business/api/b2b/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "username": "0185231PORTAL",
    "password": "123456Aa!"
  }' \
  -v
```

Bu command:
- API-yə birbaşa sorğu göndərir
- Response strukturunu göstərir
- Network problemlərini aşkar edir

### 6. Database Yoxlaması
```bash
php artisan tinker --execute="
\$cred = App\Models\BirbankCredential::where('company_id', 1)->where('env', 'test')->first();
if (\$cred) {
    echo 'Username: ' . \$cred->username . PHP_EOL;
    echo 'Has Token: ' . (\$cred->access_token ? 'Yes' : 'No') . PHP_EOL;
    echo 'Last Login: ' . (\$cred->last_login_at ? \$cred->last_login_at : 'Never') . PHP_EOL;
} else {
    echo 'No credentials found' . PHP_EOL;
}
"
```

## Yeni Dəyişikliklər

### Error Handling Yaxşılaşdırıldı
1. **Daha detallı error mesajları** - indi konkret problemləri göstərir
2. **Müxtəlif response strukturları** - fərqli API formatlarını handle edir
3. **Connection xətaları** - SSL və network problemlərini aydınlaşdırır
4. **Response structure logging** - debug üçün tam struktur log edilir

### Test Üçün
1. Browser-də aç: `/birbank/1?env=test`
2. Username və password daxil et
3. Error mesajını oxu (indi daha detallıdır)
4. Log-ları yoxla

## Növbəti Addımlar

1. **Test login command-i işlədirək:**
   ```bash
   php artisan birbank:test-login 1 --username="0185231PORTAL" --password="123456Aa!" --environment=test
   ```

2. **Nəticəni göndər:**
   - Error mesajı nədir?
   - Log-larda nə görünür?
   - cURL test nəticəsi nədir?

3. **Əgər API response struktur fərqlidirsə:**
   - Response strukturunu göndər
   - Mən kodda düzəliş edəcəm

## Əlavə Məlumat Lazımdırsa

Aşağıdakı məlumatları göndər:
1. Test login command nəticəsi
2. Log faylından Birbank ilə bağlı hissələr
3. cURL test nəticəsi
4. Browser-də görünən error mesajı
