@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card mt-5">
                <div class="card-header">
                    <h1>Edit account</h1>
                </div>
                <div class="card-body">
                    <form action="{{route('accounts-update', $account)}}" method="post">
                        <div class="mb-3">
                            <label class="form-label">New IBAN</label>
                            <input type="text" class="form-control" name="iban" value="{{ old('iban', $account->iban) }}">
                            <div class="form-text">Please add IBAN here</div>
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