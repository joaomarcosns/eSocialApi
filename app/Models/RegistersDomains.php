<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistersDomains extends Model
{
    use HasFactory;

    protected $table = 'registers_domains';
    protected $id = 'id';
    protected $fillable = [
        'fk_registers_id',
        'fk_domains_id',
    ];
    public $timestamps = true;
}
