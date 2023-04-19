@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                <div class="card-header">
                    <h1>Accounts List</h1>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($accounts as $account)
                        <li class="list-group-item">
                            <div class="client-line">
                                <div class="client-info">
                                    {{$account->accountClient->name}}
                                    {{$account->accountClient->surname}}
                                </div>
                                <div class="buttons">
                                    <div class="client-funds">
                                        {{$account->funds}}€
                                    </div>
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
                        </li>
                        @empty
                        <li class="list-group-item">
                        <div class="client-line">No accounts in the bank</div>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection