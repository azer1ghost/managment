# AI Assistant Floating Button Implementation

## Overview
A floating AI assistant button has been added to the management panel, visible across all admin pages. The assistant allows authorized users to ask analytical questions about works, invoices, and payments.

## Features

### UI Components
- **Floating Button**: Fixed at bottom-right corner with AI/robot icon
- **Modal Chat Interface**: Clean, responsive modal with:
  - Textarea for questions (max 1000 characters)
  - Character counter
  - Submit button
  - Scrollable answer area with formatted responses
  - Metadata display (intent, confidence, period)
  - Loading indicators
  - Error handling

### Backend
- **Endpoint**: `POST /module/ai/ask`
- **Controller**: `App\Http\Controllers\Modules\AIController`
- **Service**: Uses existing `App\Services\AIService`
- **Security**: Dual-layer authorization (Blade + Controller)

## Security Implementation

### Permission Rules
- **Allowed User IDs**: `15`, `78`, `123` (hardcoded)
- **Enforcement**: 
  1. **Blade Component**: Button only renders for authorized users
  2. **Controller**: 403 response if unauthorized user calls endpoint

### Authorization Checks

#### Frontend (Blade Component)
```php
@php
    $allowedUserIds = [15, 78, 123];
    $hasAccess = auth()->check() && in_array(auth()->id(), $allowedUserIds);
@endphp

@if($hasAccess)
    {{-- Button and Modal --}}
@endif
```

#### Backend (Controller)
```php
private const ALLOWED_USER_IDS = [15, 78, 123];

public function ask(Request $request): JsonResponse
{
    if (!Auth::check() || !in_array(Auth::id(), self::ALLOWED_USER_IDS, true)) {
        return response()->json([
            'success' => false,
            'error' => 'Unauthorized',
            'message' => 'Bu funksiyaya giriş imkanınız yoxdur.'
        ], 403);
    }
    // ... rest of the logic
}
```

## Files Created/Modified

### New Files
1. **`resources/views/components/ai-assistant.blade.php`**
   - Floating button component
   - Modal UI with form
   - JavaScript for AJAX calls
   - CSS styling

### Modified Files
1. **`app/Http/Controllers/Modules/AIController.php`**
   - Added authorization check
   - Added `ALLOWED_USER_IDS` constant
   - Returns 403 for unauthorized users

2. **`resources/views/layouts/main.blade.php`**
   - Added `<x-ai-assistant/>` component inclusion
   - Component only renders for authenticated users

## Usage

### For Authorized Users (IDs: 15, 78, 123)
1. The floating AI button appears at bottom-right of all admin pages
2. Click the button to open the modal
3. Type a question in Azerbaijani or English (examples):
   - "Bu ay ümumi iş sayı neçədir?"
   - "Son 30 gündə qazanc nə qədərdir?"
   - "İmzalanmış qaimələrin sayı neçədir?"
4. Click "Göndər" or press Ctrl+Enter / Shift+Enter
5. View the formatted answer with metadata

### For Unauthorized Users
- The button is **not visible** in the UI
- If they somehow call the endpoint directly, they receive a 403 error

## Technical Details

### Request Format
```json
POST /module/ai/ask
{
    "question": "Bu ay ümumi iş sayı neçədir?"
}
```

### Response Format
```json
{
    "success": true,
    "question": "Bu ay ümumi iş sayı neçədir?",
    "answer": "Bu ay ümumi iş sayı 150-dir...",
    "intent": "works_count",
    "confidence": "high",
    "period": {
        "type": "this_month",
        "label": "bu ay",
        "from": "2025-01-01",
        "to": "2025-01-31",
        "field": "created_at"
    }
}
```

### Error Responses
- **403 Unauthorized**: User not in allowed list
- **422 Validation Error**: Invalid question (empty, too long)
- **500 Server Error**: AI service error

## Styling

### Floating Button
- Position: Fixed, bottom-right (30px from edges)
- Size: 60x60px circle
- Gradient background: Purple-blue gradient
- Hover effect: Scale up with shadow
- Z-index: 1050 (above most content)

### Modal
- Size: Large (modal-lg)
- Centered
- Bootstrap 4 styling
- Responsive design

### Answer Area
- Scrollable (max-height: 400px)
- Formatted text with:
  - Bullet points
  - Bold text support
  - Line breaks preserved
  - Readable typography

## JavaScript Features

1. **AJAX Integration**: Uses jQuery AJAX with CSRF token
2. **Character Counter**: Real-time count display
3. **Loading States**: Shows spinner during AI processing
4. **Error Handling**: Displays user-friendly error messages
5. **Keyboard Shortcuts**: 
   - Ctrl+Enter or Shift+Enter to submit
6. **Auto-scroll**: Scrolls to answer when received
7. **Clear Function**: Resets form and answers

## AI Service Integration

The component uses the existing `AIService` which:
- **READ-ONLY**: Never executes UPDATE, DELETE, or INSERT queries
- **Aggregated Data Only**: Returns counts, sums, grouped stats
- **Cached Results**: 10-minute cache for performance
- **Safe Analysis**: No raw record exposure

## Customization

### To Change Allowed Users
1. **Blade Component** (`resources/views/components/ai-assistant.blade.php`):
   ```php
   $allowedUserIds = [15, 78, 123]; // Add/remove IDs here
   ```

2. **Controller** (`app/Http/Controllers/Modules/AIController.php`):
   ```php
   private const ALLOWED_USER_IDS = [15, 78, 123]; // Add/remove IDs here
   ```

### To Change Button Position
Edit CSS in `ai-assistant.blade.php`:
```css
.ai-assistant-btn {
    bottom: 30px; /* Change vertical position */
    right: 30px;  /* Change horizontal position */
}
```

### To Change Cache Duration
Edit `AIService.php`:
```php
$cacheTtl = 600; // 10 minutes in seconds
```

## Security Notes

✅ **Implemented Security Measures:**
- Double authorization (UI + Backend)
- CSRF protection (Laravel default)
- Input validation (max 1000 characters)
- SQL injection prevention (parameterized queries)
- READ-ONLY AI service (no database writes)

⚠️ **Important:**
- Keep user IDs synchronized between Blade component and Controller
- Review allowed user IDs periodically
- Monitor unauthorized access attempts via logs

## Testing

### Test Cases
1. **Authorized User (ID: 15, 78, or 123)**:
   - ✅ Button visible
   - ✅ Modal opens
   - ✅ Questions submit successfully
   - ✅ Answers display correctly

2. **Unauthorized User**:
   - ✅ Button NOT visible
   - ✅ Direct endpoint call returns 403

3. **Error Handling**:
   - ✅ Empty question shows validation error
   - ✅ Network errors show user-friendly message
   - ✅ 403 errors show authorization message

## Troubleshooting

### Button Not Visible
- Check if user ID is in allowed list (15, 78, 123)
- Verify user is authenticated
- Check browser console for JavaScript errors

### 403 Error
- Verify user ID matches allowed list
- Check controller authorization logic
- Review Laravel logs

### AJAX Errors
- Verify CSRF token is present
- Check route is accessible
- Review network tab in browser DevTools

## Future Enhancements (Optional)

- User-specific question history
- Quick question templates
- Export answers as PDF
- Voice input support
- Multi-language support
- Analytics on questions asked
