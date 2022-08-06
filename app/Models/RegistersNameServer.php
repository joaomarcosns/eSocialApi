<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistersNameServer extends Model
{
    use HasFactory;

    protected $table = 'registers_name_server';
    protected $id = 'id';
    protected $fillable = [
        'fk_names_server_id',
        'fk_domains_id',
    ];
    public $timestamps = true;
}
