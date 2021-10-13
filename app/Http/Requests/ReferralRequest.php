<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReferralRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $key = $this->isMethod('POST') ? 'required|string|unique:referrals,key,' . $this->user_id : '';

        return [
            'key' => $key,
            'user_id' => 'numeric',
            'referral_bonus_percentage' => 'numeric'
        ];
    }
}
