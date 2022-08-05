<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domains extends Model
{
    use HasFactory;
    
    protected $table = 'domains';
    protected $id = 'id';
    protected $fillable = [
        'name',
        'tld',
    ];
    public $timestamps = true;
}
