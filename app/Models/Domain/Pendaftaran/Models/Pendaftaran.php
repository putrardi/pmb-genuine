<?php

namespace App\Domain\Pendaftaran\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Pendaftaran extends Model
{
    protected $fillable = ['user_id','no_reg','status','biodata','submitted_at'];
    protected $casts = ['biodata' => 'array','submitted_at' => 'datetime'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}