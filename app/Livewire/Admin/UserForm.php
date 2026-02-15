<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserForm extends Component
{
    public ?User $user = null;
    public $name, $email, $password;

    public function mount(?User $user = null)
    {
        if ($user && $user->exists) {
            $this->user = $user;
            $this->name = $user->name;
            $this->email = $user->email;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . ($this->user->id ?? ''),
            'password' => $this->user ? 'nullable|min:8' : 'required|min:8',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->user) {
            $this->user->update($data);
        } else {
            User::create($data);
        }

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.user-form');
    }
}