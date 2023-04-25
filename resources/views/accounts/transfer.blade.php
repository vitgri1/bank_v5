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
                            <label class="form-label">Client to withdraw from</label>
                            <select class="form-select --client1--id" name="client_id_1" data-url="{{route('accounts-accountsList')}}">
                                <option value="0">Clients list</option>
                                @foreach($clients as $client1)
                                <option value="{{$client1->id}}" @if($client1->id == old('client_id_1')) selected @endif>
                                {{$client1->name}} {{$client1->surname}}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Please select client</div>
                        </div>
                        <div class="mb-3 test-selectors --test--selector1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Client to withdraw from</label>
                            <select class="form-select --client2--id" name="client_id_2" data-url="{{route('accounts-accountsList')}}">
                                <option value="0">Clients list</option>
                                @foreach($clients as $client2)
                                <option value="{{$client2->id}}" @if($client2->id == old('client_id_2')) selected @endif>
                                {{$client2->name}} {{$client2->surname}}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Please select client</div>
                        </div>
                        <div class="mb-3 test-selectors --test--selector2">
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