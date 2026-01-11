# Birbank İnteqrasiyası - Deployment Checklist

## ✅ Push etmədən əvvəl yoxla:

### 1. Faylların mövcudluğu
```bash
# Config
ls config/birbank.php

# Migrations
ls database/migrations/*birbank*.php

# Models
ls app/Models/Birbank*.php

# Services
ls app/Services/Birbank/*.php

# Controllers
ls app/Http/Controllers/Modules/BirbankController.php
ls app/Http/Controllers/Api/BirbankController.php

# Views
ls resources/views/pages/birbank/*.blade.php

# Commands
ls app/Console/Commands/Birbank*.php

# Routes
grep -n "birbank" routes/web.php
grep -n "birbank" routes/api.php
```

### 2. Database Migration
```bash
# Migration-ları run et
php artisan migrate

# Yoxla
php artisan tinker --execute="
    echo Schema::hasTable('birbank_credentials') ? 'OK' : 'FAIL';
    echo PHP_EOL;
    echo Schema::hasTable('birbank_transactions') ? 'OK' : 'FAIL';
"
```

### 3. Route-ları yoxla
```bash
php artisan route:clear
php artisan route:list | grep birbank
```

### 4. Config yoxla
```bash
php artisan tinker --execute="
    echo config('birbank.base_url_test');
    echo PHP_EOL;
    echo config('birbank.default_env');
"
```

### 5. Cache təmizlə
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 🚀 Push etdikdən sonra (Production/Server-də):

### 1. Git pull
```bash
git pull origin main  # və ya branch adınız
```

### 2. Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Migration
```bash
php artisan migrate --force
```

### 4. Cache təmizlə
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize
```

### 5. Environment variables (.env)
```env
BIRBANK_BASE_URL_PROD=https://my.birbank.business
BIRBANK_BASE_URL_TEST=https://pre-my.birbank.business
BIRBANK_ENV=test
BIRBANK_TIMEOUT=30
BIRBANK_CONNECT_TIMEOUT=10
BIRBANK_VERIFY_SSL=true
```

### 6. Permissions (əgər lazımsa)
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 7. Test
```bash
# Route-ları yoxla
php artisan route:list | grep birbank

# Browser-də test
# http://your-domain.com/birbank
```

## 📝 Yeni fayllar (Git-ə əlavə edilməlidir):

```
config/birbank.php
database/migrations/*_create_birbank_*.php
app/Models/BirbankCredential.php
app/Models/BirbankTransaction.php
app/Services/Birbank/BirbankApiException.php
app/Services/Birbank/BirbankClient.php
app/Http/Controllers/Modules/BirbankController.php
app/Http/Controllers/Api/BirbankController.php
app/Console/Commands/BirbankTestLogin.php
app/Console/Commands/BirbankCheckStatus.php
app/Console/Commands/BirbankSyncTransactions.php
app/View/Components/Sidebar.php (dəyişiklik)
resources/views/pages/birbank/index.blade.php
resources/views/pages/birbank/show.blade.php
routes/web.php (dəyişiklik)
routes/api.php (dəyişiklik)
```

## ⚠️ Diqqət:

1. **Credentials:** `.env` faylına credentials əlavə etməyin, onlar database-də saxlanılır
2. **Migration:** Production-də migration run edərkən backup alın
3. **Testing:** İlk dəfə production-də test edərkən test credentials istifadə edin
4. **Logs:** Error-ları izləmək üçün log fayllarını yoxlayın

## 🔍 Test üçün:

1. Browser: `http://your-domain.com/birbank`
2. Şirkət seç: `http://your-domain.com/birbank/1?env=test`
3. Login formunu doldur
4. Nəticəni yoxla

