<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'surname', 'pid'];
    public $timestamps = false;
    const SORT = [
        'surname_asc' => 'By surname A-Z',
        'surname_desc' => 'By surname Z-A',
        'name_asc' => 'By name A-Z',
        'name_desc' => 'By name Z-A',        
    ];

    // reikia pakeisti
    const FILTER = [
        'default' => 'Show all',
        'with_acc' => 'With accounts',
        'no_acc' => 'With no accounts',
    ];

    const PER = [
        '5' => '5',
        '10' => '10',
        '15' => '15',
        '20' => '20',
        '50' => '50',
        '100' => '100',
    ];

    public function account()
    {
        return $this->hasMany(Account::class);
    }
}
