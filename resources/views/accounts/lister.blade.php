<label class="form-label">Account to withdraw from</label>
<select class="form-select" name="account_id_{{$id}}">
    <option value="0">Accounts list</option>
    @foreach($accounts as $account)
    <option value="{{$account->id}}">
    {{$account->iban}} {{$account->funds}}€</option>
    @endforeach
</select>
<div class="form-text">Please select account</div>