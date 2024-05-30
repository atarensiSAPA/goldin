<?php

namespace App\Mail;

use App\Models\User;
use App\Models\weapons;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeaponWithdrawn extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $weapon;

    public function __construct(User $user, weapons $weapon)
    {
        $this->user = $user;
        $this->weapon = $weapon;
    }

    public function build()
    {
        return $this->view('emails.weapon_withdrawn')
                    ->subject('Weapon Withdrawn')
                    ->with([
                        'userName' => $this->user->name,
                    ]);
    }
}
