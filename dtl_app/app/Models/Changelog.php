<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Changelog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'product_changelog';

    protected $fillable = [
        'action',
        'new_payload',
        'old_payload',
    ];
}
