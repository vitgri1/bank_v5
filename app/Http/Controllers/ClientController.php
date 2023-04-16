<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    function isValidPID(string $id) :bool
    {
        $control_sum = 0;
        foreach (array_slice((str_split($id)), 0, 9) as $index => $num){
            $control_sum += ($index + 1) * (int) $num;
        }
        $control_sum += (int) substr($id, 9 , 1);

        if ($control_sum % 11 !== 10){
            $control_coef = $control_sum % 11;
        } else {
            $control_sum = 0;
            foreach (array_slice((str_split($id)), 0, 7) as $index => $num){
                $control_sum += ($index + 3) * (int) $num;
            }
            foreach (array_slice((str_split($id)), 7, 3) as $index => $num){
                $control_sum += ($index + 1) * (int) $num;
            }
            if ($control_sum % 11 !== 10){
                $control_coef = $control_sum % 11;
            } else {
                $control_coef = 0;
            }
        }

        if((int) substr($id, 10 , 1) !== $control_coef) {
            return false;
        }

        return true;
    }

    function isUniquePID(int $id) :bool
    {
        $clients = Client::all();
        
        if ($clients->filter(fn ($c) => $c['pid'] == $id)->count() !== 0){
            return false;
        }
        return true;
    } 

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'surname' => 'required|min:3',
            'pid' => 'required|size:11',
        ]);

        if ($validator->fails()) {
            $request->flash();
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        if (!ClientController::isValidPID($request->pid)) {
            $request->flash();
            return redirect()
            ->back()
            ->with('error', 'Personal ID is not valid');   
        }
        if (!ClientController::isUniquePID($request->pid)) {
            $request->flash();
            return redirect()
            ->back()
            ->with('error', 'Personal ID is not unique');   
        }
        
        $client = new Client;
        $client->name = $request->name;
        $client->surname = $request->surname;
        $client->pid = $request->pid;
        $client->funds = 0;
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
        if ($client->funds != 0) {
            return redirect()
            ->route('clients-index')
            ->with('error', 'The client with funds cannot be deleted!');
        }
        $client->delete();
        return redirect()
        ->route('clients-index')
        ->with('info', 'The client was deleted');
    }
}
