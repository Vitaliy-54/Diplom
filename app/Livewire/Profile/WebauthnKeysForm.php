<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WebauthnKeysForm extends Component
{
    public $credentials = [];
    public $isSupported = true;

    public function mount()
    {
        $this->loadCredentials();
    }

    public function loadCredentials()
    {
        if (DB::table('web_authn_credentials')->where('user_id', Auth::id())->exists()) {
            $this->credentials = DB::table('web_authn_credentials')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($credential) {
                    return [
                        'id' => $credential->id,
                        'created_at' => date('d.m.Y H:i', strtotime($credential->created_at)),
                        'device_name' => $credential->name ?? 'Устройство',
                    ];
                });
        } else {
            $this->credentials = [];
        }
    }

    public function deleteKey($id)
    {
        DB::table('web_authn_credentials')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();
        $this->loadCredentials();
        session()->flash('message', 'Ключ удален');
    }

    public function render()
    {
        return view('livewire.profile.webauthn-keys-form');
    }
}