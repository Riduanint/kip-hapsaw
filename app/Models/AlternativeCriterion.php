<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlternativeCriterion extends Model
{
    use HasFactory;

    protected $table = 'alternative_criteria';

    protected $fillable = [
        'alternative_id',
        'criterion_id',
        'value',
    ];

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }

    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}
