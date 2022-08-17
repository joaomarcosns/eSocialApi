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
        'created_at',
        'updated_at',
        'expiration_date',
        'fk_registers_id',
        'status'
    ];
    public $timestamps = false;

    public function registers()
    {
        return $this->belongsTo(Registers::class, 'fk_registers_id', 'id');
    }

    public function names_servers()
    {
        return $this->hasMany(NameServer::class, 'fk_domains_id', 'id');
    }
}
