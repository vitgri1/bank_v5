<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\ValidPID;

class ClientController extends Controller
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

        $clients = match($filter) {
            'with_acc' => Client::whereExists(function ($query) {
                $query->from('accounts')
                      ->whereColumn('accounts.client_id', 'clients.id');
            }),
            'no_acc' => Client::whereNotExists(function ($query) {
                $query->from('accounts')
                      ->whereColumn('accounts.client_id', 'clients.id');
            }),
            default => Client::where('id', '>', 0),
        };

        $clients = match($sort) {
            'surname_asc' => $clients->orderBy('surname'),
            'surname_desc' => $clients->orderBy('surname', 'desc'),
            'name_asc' => $clients->orderBy('name'),
            'name_desc' => $clients->orderBy('name', 'desc'),
            default => $clients->orderBy('surname')
        };

        $request->session()->put('last-client-view', [
            'sort' => $sort,
            'filter' => $filter,
            'page' => $page,
            'per' => $per
        ]);

        $clients = $clients->paginate($per)->withQueryString();


        return view('bank.index', [
            'clients' => $clients,
            'sortSelect' => Client::SORT,
            'sort' => $sort,
            'filterSelect' => Client::FILTER,
            'filter' => $filter,
            'perSelect' => Client::PER,
            'per' => $per,
            'page' => $page
        ]);
    }

    public function create()
    {
        return view('bank.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'surname' => 'required|min:3',
            'pid' => ['required','size:11','unique:clients,pid',new ValidPID],
        ]);

        if ($validator->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator);
        }
        
        $client = new Client;
        $client->name = ucfirst($request->name);
        $client->surname = ucfirst($request->surname);
        $client->pid = $request->pid;
        $client->save();
        return redirect()
        ->route('clients-index')
        ->with('ok', 'New client was created');
    }

    public function show(Client $client)
    {
        return view('bank.show', [
            'client' => $client
        ]);
    }

    public function edit(Client $client)
    {
        return view('bank.edit', [
            'client' => $client
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'surname' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator);
        }
        
        $client->name = $request->name;
        $client->surname = $request->surname;
        $client->save();
        return redirect()
        ->route('clients-index')
        ->with('ok', 'The client was updated');
    }

    public function destroy(Client $client)
    {
        foreach ($client->account as $account){      
            if ($account->funds != 0) {
                return redirect()
                ->route('clients-index')
                ->with('error', 'The client with funds in any account cannot be deleted!');
            }
        }
        $client->delete();
        return redirect()
        ->route('clients-index')
        ->with('info', 'The client was deleted');
    }
}