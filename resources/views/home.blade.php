@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div>Total number of clients: {{$totalClients}}</div>
                    <div>Total number of accounts: {{$totalAccounts}}</div>
                    <div>Total amount of funds in bank: {{$totalFunds}}</div>
                    <div>Biggest amount of funds in a single account: {{$maxFunds}}</div>
                    <div>Average amount of funds in an account: {{$avgFunds}}</div>
                    <div>Total number of empty accounts: {{$totalEmpty}}</div>
                    <div>Total number of accounts in debt: {{$totalNegative}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
