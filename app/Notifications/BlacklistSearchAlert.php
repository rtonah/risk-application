<?php
namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BlacklistSearchAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public string $searchTerm;
    public $results;
    public User $user;

    public function __construct(string $searchTerm, $results, User $user)
    {
        $this->searchTerm = $searchTerm;
        $this->results = $results;
        $this->user = $user;
    }
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('⚠️ Alerte de recherche sur la Blacklist')
            ->greeting('Bonjour,')
            ->line("Une recherche sur la blacklist a été effectuée avec le terme : '{$this->searchTerm}'")
            ->line("👤 Agent : {$this->user->first_name} {$this->user->last_name}")
            ->line('Résultats trouvés :');
        foreach ($this->results as $result) {
            $mail->line("• {$result->full_name} — {$result->national_id}");
        }

        $mail->line('Merci de vérifier ces entrées.')
            ->line('Designed and Developed by Rija.')
            ->from('noreply@votreapp.com', 'Système FAHOMBIAZANA 2.0')
        ;

        return $mail;
    }
}

