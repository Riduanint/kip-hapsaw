<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    use HasFactory;

    protected $table = 'criteria';

    protected $fillable = [
        'code',
        'name',
        'type',
        'weight',
    ];

    public function alternatives()
    {
        return $this->belongsToMany(Alternative::class, 'alternative_criteria', 'criterion_id', 'alternative_id')
                    ->withPivot('value')
                    ->withTimestamps();
    }
}
