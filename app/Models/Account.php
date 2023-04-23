<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['iban', 'funds', 'client_id'];
    public $timestamps = false;
    // reikia pakeisti
    const SORT = [
        'default' => 'No sort',
        'funds_asc' => 'By funds decreasing',
        'funds_desc' => 'By funds increasing',
        'cid_asc' => 'By client ID up',
        'cid_desc' => 'By client ID down',   
    ];

    
    const FILTER = [
        'default' => 'Show all',
        'neg' => 'Debt accounts',
        'pos' => 'Positive accounts',
        'zero' => 'Empty accounts'
    ];

    const PER = [
        '5' => '5',
        '10' => '10',
        '15' => '15',
        '20' => '20',
        '50' => '50',
        '100' => '100',
    ];

    public function accountClient() 
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
