# OpenAI API Key Setup

## Problem
`OpenAI API key is not configured. Please set OPENAI_API_KEY in your .env file.`

## Solution

### 1. OpenAI API Key Alın
- [OpenAI Platform](https://platform.openai.com/) saytına gedin
- Hesab yaradın və ya daxil olun
- [API Keys](https://platform.openai.com/api-keys) səhifəsinə gedin
- "Create new secret key" düyməsini basın
- Key-i kopyalayın (yalnız bir dəfə göstərilir!)

### 2. .env Faylına Əlavə Edin

`.env` faylınızı açın (proyektin root qovluğunda) və aşağıdakı sətri əlavə edin:

```env
OPENAI_API_KEY=sk-your-actual-api-key-here
```

**Məsələn:**
```env
OPENAI_API_KEY=sk-proj-abc123def456ghi789jkl012mno345pqr678stu901vwx234yz
```

### 3. Config Cache Təmizləyin

Terminal-də aşağıdakı əmri icra edin:

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Test Edin

Səhifəni yeniləyin və chat-də sual yazın. Artıq işləməlidir.

## Qeyd
- API key-i heç vaxt git commit etməyin (`.env` faylı `.gitignore`-da olmalıdır)
- Key-in başında `sk-` olmalıdır
- Key uzunluğu təxminən 50+ simvoldur
