<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        
        return view('accounts.create', [
            'clients' => $clients
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iban' => 'required|size:20|regex:/^LT\d{18}$/',
            'client_id' => 'not_regex:/^[0]{1}$/'
        ],
        [
            'client_id.not_regex' => 'Select a client from the list'
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

    public function edit(Account $account)
    {
        return view('accounts.edit', [
            'account' => $account
        ]);
    }

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
        ->route('clients-show', ['client' => $account->accountClient])
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
    ->route('clients-show', ['client' => $account->accountClient])
    ->with('ok', $request->funds.'€ were added to '.$account->accountClient->name.' '.$account->accountClient->surname. ' account: '. $account->iban);
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
    ->route('clients-show', ['client' => $account->accountClient])
    ->with('ok', $request->funds.'€ were withdrawn from '.$account->accountClient->name.' '.$account->accountClient->surname. ' account: '. $account->iban);
}

    //mano transfer
    public function transfer(Request $request)
    {
        
        $accounts = Account::all();
        
        return view('accounts.transfer', [
            'accounts' => $accounts,
        ]);
    }

    public function transferUpdate(Request $request)
    {
        if ($request->account_id_1 == 0 || $request->account_id_2 == 0) {
            $request->flash();
            return redirect()
            ->back()
            ->with('error', 'Select both accounts to proceed!');
        }


        $account1 = Account::where('id', $request->account_id_1)->first();
        $account2 = Account::where('id', $request->account_id_2)->first();

        $validator1 = Validator::make($request->all(), [
            'funds' => 'required|numeric|decimal:0,2|gte:0',
        ]);
    
        if ($validator1->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator1);
        }

        if ($request->funds > $account1->funds) {
            $request->flash();
            return redirect()
            ->back()
            ->with('error', 'Cannot withraw more funds then there is in account');
        }
        $account1->funds -= $request->funds;
        $account2->funds += $request->funds;
        $account1->save();
        $account2->save();
        return redirect()
        ->route('accounts-index')
        ->with('ok', $request->funds.'€ were trasfered from '.$account1->accountClient->name.' '.$account1->accountClient->surname. ' to '.$account2->accountClient->name.' '.$account2->accountClient->surname);
    }

    public function destroy(Account $account)
    {
        if ($account->funds != 0) {
            return redirect()
            ->back()
            ->with('error', 'The account with funds cannot be deleted!');
        }
        $account->delete();
        return redirect()
        ->route('clients-show', ['client' => $account->accountClient])
        ->with('info', 'The account was deleted');
    }
}
