<!-- resources/views/livewire/process-mvola-transactions.blade.php -->
<div>
    <form wire:submit.prevent="process">
        <input type="text" wire:model.defer="login" placeholder="Login CBS Musoni">
        <input type="password" wire:model.defer="password" placeholder="Password CBS Musoni">
        <input type="text" wire:model.defer="token" placeholder="API Key">
        <input type="text" wire:model.defer="domaine" placeholder="URL Musoni">

        <button type="submit">Lancer les transactions</button>
    </form>

    @if ($message)
        <div>{{ $message }}</div>
    @endif
</div>
