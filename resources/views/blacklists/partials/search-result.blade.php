@if($blacklist)
    <div class="alert alert-danger">
        <strong>Client is blacklisted!</strong><br>
        Name: {{ $blacklist->name }}<br>
        National ID: {{ $blacklist->national_id }}<br>
        Status: {{ $blacklist->status == 1 ? 'Blacklisted' : 'Unblocked' }}
    </div>
@else
    <div class="alert alert-success">
        Client not found in blacklist. You can proceed.
    </div>
@endif
