<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HikeInviteEmail extends Mailable
{
  use Queueable;
  use SerializesModels;

  public $data;

  public function __construct(array $data)
  {
    $this->data = $data;
  }

  public function envelope(): Envelope
  {
    return new Envelope(
      subject: $this->data['subject'],
    );
  }

  public function content(): Content
  {
    return new Content(view: 'emails.hike_invite');
  }

  public function attachments(): array
  {
    return [];
  }
}
