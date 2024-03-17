<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class live_positions extends Model
{
    use HasFactory;

    protected $casts = [
        'pilotPositions' => 'object'
    ];
}
