<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newborn extends Model
{
    protected $fillable = [
        'MedicalHistoryID',
        'FAMILY',
        'Name',
        'OT',
        'BD',
        'Sex',
    ];
}
