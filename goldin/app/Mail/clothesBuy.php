<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class clothesBuy extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $items;
    public $total;

    public function __construct(User $user, array $items, $total)
    {
        $this->user = $user;
        $this->items = $items;
        $this->total = $total;
    }
    

    public function build()
    {
        return $this->view('emails.clothes_withdrawn')
                    ->with([
                        'userName' => $this->user->name,
                        'items' => $this->items,
                        'total' => $this->total,
                    ]);
    }
}
