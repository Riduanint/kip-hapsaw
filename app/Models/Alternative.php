<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'nisn',
        'email',
        'phone',
        'status',
        'final_score',
        'rank',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function criteria()
    {
        return $this->belongsToMany(Criterion::class, 'alternative_criteria', 'alternative_id', 'criterion_id')
                    ->withPivot('value')
                    ->withTimestamps();
    }
}
