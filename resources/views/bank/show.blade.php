@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                <div class="card-header">
                    <h1>Client:
                        {{$client->name}}
                        {{$client->surname}}
                        {{$client->pid}}</h1>
                </div>
                <div class="card-body">
                    <div class="client-accounts">
                        @forelse ($client->account as $account)
                        <div class="client-account">
                            <div class="client-account-info">
                                <div class="client-account-iban">
                                    {{$account->iban}}
                                </div>
                                <div class="client-account-funds">
                                    {{$account->funds}}€
                                </div>
                            </div>
                            <div class="buttons">
                                <a href="{{route('accounts-edit', $account)}}" class="btn btn-primary">Edit</a>
                                <a href="{{route('accounts-add', $account)}}" class="btn btn-success">Add</a>
                                <a href="{{route('accounts-withdraw', $account)}}" class="btn btn-warning">Withdraw</a>
                                <form action="{{route('accounts-delete', $account)}}" method="post">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                    @csrf
                                    @method('delete')
                                </form>
                            </div>
                        </div>
                        @empty
                            <li class="list-group-item">
                                <div class="client-line">Client has no accounts</div>
                            </li>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection