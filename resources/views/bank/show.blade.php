@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                <div class="card-header">
                    <h1>Client</h1>
                </div>
                <div class="card-body">
                    <div class="client-line">
                        <div class="client-info">
                            {{$client->name}}
                            {{$client->surname}}
                            {{$client->pid}}
                        </div>
                        <div class="buttons">
                            <div class="client-funds">
                                {{$client->funds}}€
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection