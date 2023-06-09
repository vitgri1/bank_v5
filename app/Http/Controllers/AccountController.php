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

    public function index(Request $request)
    {
        $sort = $request->sort ?? '';
        $filter = $request->filter ?? '';
        $per = (int) ($request->per ?? 10);
        $page = $request->page ?? 1;

        $accounts = match($filter) {
            'neg' => Account::where('funds', '<', 0),
            'pos' => Account::where('funds', '>', 0),
            'zero' => Account::where('funds', '=', 0),
            default => Account::where('funds', '>', 0)->orWhere('funds', '<=', 0),
        };

        $accounts = match($sort) {
            'funds_asc' => $accounts->orderBy('funds'),
            'funds_desc' => $accounts->orderBy('funds', 'desc'),
            'cid_asc' => $accounts->orderBy('client_id'),
            'cid_desc' => $accounts->orderBy('client_id', 'desc'),
            default => $accounts
        };

        $request->session()->put('last-client-view', [
            'sort' => $sort,
            'filter' => $filter,
            'page' => $page,
            'per' => $per
        ]);

        $accounts = $accounts->paginate($per)->withQueryString();

        return view('accounts.index', [
            'accounts' => $accounts,
            'sortSelect' => Account::SORT,
            'sort' => $sort,
            'filterSelect' => Account::FILTER,
            'filter' => $filter,
            'perSelect' => Account::PER,
            'per' => $per,
            'page' => $page
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
    
    if (!$request->confirm && $request->funds > 1000) {
        return redirect()
        ->back()
        ->with('big-sum-modal', [
            [$account], $request->funds, 'accounts-addUpdate', 'Add'
        ]);
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

    if (!$request->confirm && $request->funds > 1000) {
        return redirect()
        ->back()
        ->with('big-sum-modal', [
            [$account], $request->funds, 'accounts-withdrawUpdate', 'Withdrawal'
        ]);
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
        $clients = Client::all();

        return view('accounts.transfer', [
            'clients' => $clients,
        ]);
    }

    public function clientsAccountsList(Request $request)
    {
        $accounts = Account::where('client_id', $request->cl)->get();

        $html = view('accounts.lister')
            ->with([
                'accounts' => $accounts,
                'id' => $request->acc,
            ])
            ->render();

        return response()->json([
            'html' => $html
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

        if (!$request->confirm && $request->funds > 1000) {
            return redirect()
            ->back()
            ->with('big-sum-modal', [
                [$account1, $account2], $request->funds, 'accounts-transferUpdate', 'Transfer'
            ]);
        }

        $account1->funds -= $request->funds;
        $account2->funds += $request->funds;
        $account1->save();
        $account2->save();
        return redirect()
        ->route('accounts-index')
        ->with('ok', $request->funds.'€ were trasfered from '.$account1->accountClient->name.' '.$account1->accountClient->surname. ' to '.$account2->accountClient->name.' '.$account2->accountClient->surname);
    }

    //mano fees
    public function fees()
    {
        $clients = Client::whereExists(function ($query) {
            $query->from('accounts')
                  ->whereColumn('accounts.client_id', 'clients.id');
        })->get();
        foreach ($clients as $client){
            $rand_acc_id = $client->account[rand(0,$client->account->count()-1)]->id;
            Account::where('id', $rand_acc_id)
            ->decrement('funds', 5);
        }
        return redirect()
        ->back()
        ->with('ok', 'Fees where applied to every client with bank account');
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
