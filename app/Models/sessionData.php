<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sessionData extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'raceId',
        'meetingName',
        'meetingLocation',
        'meetingCountry',
        'key',
        'type',
        'name',
        'startDate',
        'endDate',
        'driversData',
    ];
}
