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
        'default' => 'No sort',
        'name_asc' => 'By name A-Z',
        'name_desc' => 'By name Z-A',
        'surname_asc' => 'By surname A-Z',
        'surname_desc' => 'By surname Z-A',
    ];

    // reikia pakeisti
    const FILTER = [
        'default' => 'Show all',
        'tt' => 'Tik Tok users',
        'fb' => 'Facebook users',
    ];

    const PER = [
        '10' => '10',
        '17' => '17',
        '33' => '33',
        '3' => 'tiny view',
    ];

    public function account()
    {
        return $this->hasMany(Account::class);
    }
}
