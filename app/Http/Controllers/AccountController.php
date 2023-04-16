<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $accounts = Account::all();

        return view('accounts.index', [
            'accounts' => $accounts
        ]);
    }

    public function create()
    {
        $clients = Client::all();

        $id = $request->id ?? 0;
        
        return view('accounts.create', [
            'clients' => $clients,
            'id' => $id
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iban' => 'required|size:20|regex:/^LT\d{18}$/'
        ]);

        if ($validator->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator);
        }
      
        $account = new Account;
        $account->client_id = $request->client_id;
        $account->iban = $request->iban;
        $account->funds = 0;
        $account->save();
        return redirect()
        ->route('accounts-index')
        ->with('ok', 'New account was created');
    }

    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return view('accounts.edit', [
            'account' => $account
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validator = Validator::make($request->all(), [
            'iban' => 'required|size:20|regex:/^LT\d{18}$/'
        ]);

        if ($validator->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        $account->iban = $request->iban;
        $account->save();
        return redirect()
        ->route('accounts-index')
        ->with('ok', 'An account was updated');
    }

//mano add
public function add(Account $account)
{
    return view('accounts.add', [
        'account' => $account
    ]);
}
public function addUpdate(Request $request, Account $account)
{
    $validator = Validator::make($request->all(), [
        'funds' => 'numeric|decimal:0,2|gte:0',
    ]);

    if ($validator->fails()) {
        $request->flash();
        return redirect()
            ->back()
            ->withErrors($validator);
    }
    
    $account->funds += $request->funds;
    $account->save();
    return redirect()
    ->route('accounts-index')
    ->with('ok', $request->funds.'€ were added to '.$account->accountClient->name.' '.$account->accountClient->surname);
}

//mano withdraw
public function withdraw(Account $account)
{
    return view('accounts.withdraw', [
        'account' => $account
    ]);
}
public function withdrawUpdate(Request $request, Account $account)
{
    $validator = Validator::make($request->all(), [
        'funds' => 'numeric|decimal:0,2|gte:0',
    ]);

    if ($validator->fails()) {
        $request->flash();
        return redirect()
            ->back()
            ->withErrors($validator);
    }
    
    if ($request->funds > $account->funds) {
        $request->flash();
        return redirect()
        ->back()
        ->with('error', 'Cannot withraw more funds then there is in account');
    }
    $account->funds -= $request->funds;
    $account->save();
    return redirect()
    ->route('accounts-index')
    ->with('ok', $request->funds.'€ were withdrawn from '.$account->accountClient->name.' '.$account->accountClient->surname);
}

    public function destroy(Account $account)
    {
        if ($account->funds != 0) {
            return redirect()
            ->route('accounts-index')
            ->with('error', 'The account with funds cannot be deleted!');
        }
        $account->delete();
        return redirect()
        ->route('accounts-index')
        ->with('info', 'The account was deleted');
    }
}
