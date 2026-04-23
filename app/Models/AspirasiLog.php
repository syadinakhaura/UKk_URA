<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AspirasiLog extends Model
{
    protected $fillable = [
        'aspirasi_id',
        'actor_user_id',
        'actor_role',
        'action',
        'description',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
