<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaComparison extends Model
{
    use HasFactory;

    protected $table = 'criteria_comparisons';

    protected $fillable = [
        'criteria_id_1',
        'criteria_id_2',
        'value',
    ];

    public function criterion1()
    {
        return $this->belongsTo(Criterion::class, 'criteria_id_1');
    }

    public function criterion2()
    {
        return $this->belongsTo(Criterion::class, 'criteria_id_2');
    }
}
