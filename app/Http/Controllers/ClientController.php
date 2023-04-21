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

    public function index()
    {
        $clients = Client::all()->sortBy('surname');

        return view('bank.index', [
            'clients' => $clients
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