<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fund extends Model implements Recordable, DocumentableInterface

{
    use Documentable, \Altek\Accountant\Recordable,  Eventually;

    protected $fillable = ['user_id', 'company_id', 'voen', 'main_activity', 'asan_imza', 'code', 'adress', 'voen_code', 'voen_pass', 'pass', 'respublika_code', 'respublika_pass', 'kapital_code', 'kapital_pass'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function companies(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function getMainColumn(): string
    {
        return $this->getAttribute('id');
    }
}
