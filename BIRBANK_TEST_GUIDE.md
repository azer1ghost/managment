# Birbank Integration - Test və Yoxlama Qaydaları

## 1. Status Yoxlama

### Şirkət üçün status:
```bash
php artisan birbank:check-status {company_id} --environment=test
```

### Bütün şirkətlər üçün:
```bash
php artisan birbank:check-status --environment=test
```

## 2. Login Test

```bash
php artisan birbank:test-login {company_id} --username="USERNAME" --password="PASSWORD" --environment=test
```

## 3. Database-də Yoxlama

### Tinker ilə:
```bash
php artisan tinker
```

Sonra:
```php
// Credentials yoxlama
$cred = App\Models\BirbankCredential::where('company_id', 1)->where('env', 'test')->first();
$cred->username;
$cred->last_login_at;
$cred->hasValidToken();

// Transactions yoxlama
App\Models\BirbankTransaction::where('company_id', 1)->count();
App\Models\BirbankTransaction::where('company_id', 1)->latest()->first();
```

## 4. Log Fayllarını Yoxlama

```bash
tail -f storage/logs/laravel.log | grep Birbank
```

Və ya:
```bash
cat storage/logs/laravel-$(date +%Y-%m-%d).log | grep Birbank
```

## 5. API Endpoint-ləri Test

### Login:
```bash
curl -X POST http://your-app.test/api/birbank/1/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "0185231PORTAL",
    "password": "123456Aa!",
    "env": "test"
  }'
```

### Accounts (stub):
```bash
curl http://your-app.test/api/birbank/1/accounts?env=test
```

### Transactions (stub):
```bash
curl "http://your-app.test/api/birbank/1/transactions?account=AZ28AIIB40060019440185231221&from=2024-01-01&to=2024-01-31&env=test"
```

## 6. Database Query-ləri

### SQL ilə:
```sql
-- Credentials yoxlama
SELECT company_id, env, username, auth_type, last_login_at, 
       CASE WHEN access_token IS NOT NULL THEN 'Yes' ELSE 'No' END as has_token
FROM birbank_credentials
WHERE company_id = 1 AND env = 'test';

-- Transactions sayı
SELECT COUNT(*) as total, 
       MIN(booked_at) as earliest, 
       MAX(booked_at) as latest
FROM birbank_transactions
WHERE company_id = 1 AND env = 'test';
```

## 7. Sync Command Test

```bash
php artisan birbank:sync-transactions 1 --environment=test --days=30
```

## 8. Real-time Monitoring

### Log monitoring:
```bash
# Yeni log-ları izlə
tail -f storage/logs/laravel.log

# Birbank log-larını filter et
tail -f storage/logs/laravel.log | grep -i birbank
```

## 9. Database-də Direct Yoxlama

### phpMyAdmin / TablePlus / DBeaver:
- `birbank_credentials` cədvəli - credentials və token-lar
- `birbank_transactions` cədvəli - sync olunmuş transaction-lar

## 10. Code Review

### Əsas fayllar:
- `app/Services/Birbank/BirbankClient.php` - API client
- `app/Http/Controllers/Api/BirbankController.php` - API endpoints
- `app/Models/BirbankCredential.php` - Credentials model
- `app/Models/BirbankTransaction.php` - Transactions model
- `config/birbank.php` - Konfiqurasiya

## Quick Check Commands

```bash
# 1. Status
php artisan birbank:check-status 1 --environment=test

# 2. Login test
php artisan birbank:test-login 1 --username="USER" --password="PASS" --environment=test

# 3. Log yoxlama
tail -20 storage/logs/laravel-$(date +%Y-%m-%d).log | grep Birbank

# 4. Database count
php artisan tinker --execute="echo App\Models\BirbankCredential::count();"
php artisan tinker --execute="echo App\Models\BirbankTransaction::count();"
```

