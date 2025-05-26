<?php
namespace App\Notifications;

use App\Models\Blacklist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BlacklistStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $blacklist;
    protected $action;

    public function __construct(Blacklist $blacklist, string $action)
    {
        $this->blacklist = $blacklist;
        $this->action = $action; // 'blacklisted' or 'unblocked'
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // You can remove 'mail' or 'database' if needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Client {$this->action}: {$this->blacklist->full_name}")
            ->line("National ID: {$this->blacklist->national_id}")
            ->line("Reason: {$this->blacklist->reason}")
            ->line("Status: {$this->blacklist->status}")
            ->action('View Blacklist', url('/blacklists/' . $this->blacklist->id));
    }

    public function toArray($notifiable)
    {
        return [
            'blacklist_id' => $this->blacklist->id,
            'full_name' => $this->blacklist->full_name,
            'national_id' => $this->blacklist->national_id,
            'status' => $this->blacklist->status,
            'action' => $this->action,
        ];
    }
}

