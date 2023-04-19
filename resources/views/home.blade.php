@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div>Total number of clients: {{'k'}}</div>
                    <div>Total number of accounts: {{0}}</div>
                    <div>Total amount of funds in bank: {{0}}</div>
                    <div>Biggest amount of funds in a single account: {{0}}</div>
                    <div>Average amount of funds in an account: {{0}}</div>
                    <div>Total number of empty accounts: {{0}}</div>
                    <div>Total number of accounts in debt: {{0}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
