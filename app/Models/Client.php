<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'surname', 'pid'];
    public $timestamps = false;

    public function account()
    {
        return $this->hasMany(Account::class);
    }
}
