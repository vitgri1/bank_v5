@if (Session::has('big-sum-modal'))
<div class="modal" tabindex="-1">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm {{ Session::get('big-sum-modal')[3]}} transaction</h5>
            </div>
            <div class="modal-body">
                <p>
                    @foreach (Session::get('big-sum-modal')[0] as $account)
                    {{ $account->accountClient->name }}
                    {{ $account->accountClient->surname }}
                    @endforeach
                    {{ Session::get('big-sum-modal')[1]}}
                </p>
            </div>
            <div class="modal-footer">
                <a href={{url()->full()}} class="btn btn-secondary">No</a>
                {{-- Add/Withdraw --}}
                @if (count(Session::get('big-sum-modal')[0]) == 1)
                <form action="{{ route(Session::get('big-sum-modal')[2], Session::get('big-sum-modal')[0])}}" method="post">
                    <button type="submit" class="btn btn-danger">Yes</button>
                    <input type="hidden" value="1" name="confirm">
                    <input type="hidden" value="{{ Session::get('big-sum-modal')[1]}}" name="funds">
                    @csrf
                    @method('put')
                </form>
                {{-- Transfer --}}
                @else
                <form action="{{ route(Session::get('big-sum-modal')[2])}}" method="post">
                    <button type="submit" class="btn btn-danger">Yes</button>
                    <input type="hidden" value="1" name="confirm">
                    <input type="hidden" value="{{ Session::get('big-sum-modal')[1]}}" name="funds">
                    <input type="hidden" value="{{ Session::get('big-sum-modal')[0][0]->id}}" name="account_id_1">
                    <input type="hidden" value="{{ Session::get('big-sum-modal')[0][1]->id}}" name="account_id_2">
                    @csrf
                    @method('put')
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endif