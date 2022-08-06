<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registers extends Model
{
    use HasFactory;

    protected $table = 'registers';
    protected $id = 'id';
    protected $fillable = [
        'name',
    ];
    public $timestamps = true;

}
