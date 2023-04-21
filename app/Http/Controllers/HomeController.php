<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Account;

class HomeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $clients = Client::all();
        $accounts = Account::all();
        
        $totalClients = $clients->count();
        $totalAccounts = $accounts->count();
        $totalFunds = $accounts->sum('funds').'€';
        $maxFunds = $accounts->max('funds').'€';
        $avgFunds = round($accounts->avg('funds'), 2).'€';
        $totalEmpty = $accounts->where('funds', 0)->count();
        $totalNegative = $accounts->where('funds','<', 0)->count();

        return view('home', [
            'totalClients' => $totalClients,
            'totalAccounts' => $totalAccounts,
            'totalFunds' => $totalFunds,
            'maxFunds' => $maxFunds,
            'avgFunds' => $avgFunds,
            'totalEmpty' => $totalEmpty,
            'totalNegative' => $totalNegative,
        ]);
    }
}
