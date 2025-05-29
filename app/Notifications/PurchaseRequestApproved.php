<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PurchaseRequest;

class PurchaseRequestApproved extends Notification
{
    public $request;

    public function __construct(PurchaseRequest $request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //         ->subject('Nouvelle demande d\'achat approuvée')
    //         ->line('Une nouvelle demande d\'achat a été approuvée.')
    //         ->action('Voir la demande', url('/purchase-requests/' . $this->request->id . '/review'))
    //         ->line('Merci de traiter cette demande rapidement.');
    // }

    public function toMail($notifiable)
    {
        $url = route('purchase-requests.review', $this->request->id);

        return (new MailMessage)
            ->subject('Demande d\'achat approuvée')
            ->greeting('Bonjour équipe Achat,')
            ->line('Une nouvelle demande d\'achat vient d\'être approuvée par un superviseur.')
            ->line("Titre de la demande : **{$this->request->title}**")
            ->action('Consulter la demande', $url)
            ->line('Merci de prendre en charge cette demande dès que possible.')
            ->line('Cordialement,')
            ->line(' ')
            ->line('---')
            ->line('Designed and Developed by Rija.')
            ->from('noreply@votreapp.com', 'Système de Demande d\'Achat');
    }
}

