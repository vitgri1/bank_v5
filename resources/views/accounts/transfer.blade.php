@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                <div class="card-header">
                    <h1>Transfer funds</h1>
                </div>
                <div class="card-body">
                    <form action="{{route('accounts-transferUpdate')}}" method="post">
                        <div class="mb-3">
                            <label class="form-label">Account to withdraw from</label>
                            <select class="form-select" name="account_id_1">
                                <option value="0">Accounts list</option>
                                @foreach($accounts as $account1)
                                <option value="{{$account1->id}}" @if($account1->id == old('account_id_1')) selected @endif>
                                {{$account1->accountClient->name}} {{$account1->accountClient->surname}} {{$account1->funds}} {{$account1->iban}}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Please select account 1</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Account to add to</label>
                            <select class="form-select" name="account_id_2">
                                <option value="0">Accounts list</option>
                                @foreach($accounts as $account2)
                                <option value="{{$account2->id}}" @if($account2->id == old('account_id_2')) selected @endif>
                                {{$account2->accountClient->name}} {{$account2->accountClient->surname}} {{$account2->funds}} {{$account2->iban}}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Please select account 2</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trasfer Funds</label>
                            <input type="text" class="form-control" name="funds" value={{old('funds')}}>
                            <div class="form-text">Amount of funds to transfer</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        @csrf
                        @method('put')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection