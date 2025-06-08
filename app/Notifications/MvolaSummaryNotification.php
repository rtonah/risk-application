<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MvolaSummaryNotification extends Notification
{
    public $successCount;
    public $failCount;
    public $duplicateCount;

    public function __construct($successCount, $failCount, $duplicateCount)
    {
        $this->successCount = $successCount;
        $this->failCount = $failCount;
        $this->duplicateCount = $duplicateCount;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Résumé des transactions mVola')
            ->greeting('Bonjour équipe Taratra,')
            ->line("✅ Transactions réussies : {$this->successCount}")
            ->line("❌ Transactions échouées : {$this->failCount}")
            ->line("♻️ Transactions en doublon : {$this->duplicateCount}")
            ->line('Ceci est une notification automatique après traitement des transactions mVola.')
            ->line(' ')
            ->line('---')
            ->line('Designed and Developed by Rija.')
            ->from('noreply@votreapp.com', 'Système de Demande d\'Achat');
    }
}
