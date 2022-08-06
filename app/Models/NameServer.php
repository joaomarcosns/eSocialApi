<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NameServer extends Model
{
    use HasFactory;
    protected $table = 'names_server';
    protected $id = 'id';
    protected $fillable = [
        'names_server',
        'fk_domains_id'
    ];
    public $timestamps = true;
}
