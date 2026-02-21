<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserForm extends Component
{
    public ?User $user = null;
    public $name, $email, $password, $role; // Added $role

    public function mount(?User $user = null)
    {
        if ($user && $user->exists) {
            $this->user = $user;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role; // Load existing role
        } else {
            $this->role = 'user'; // Default for new accounts
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . ($this->user->id ?? ''),
            'password' => $this->user ? 'nullable|min:8' : 'required|min:8',
            'role' => 'required|in:admin,user', // Validation for role
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role, // Save the role
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->user && $this->user->exists) {
            $this->user->update($data);
            session()->flash('status', 'Member updated successfully.');
        } else {
            User::create($data);
            session()->flash('status', 'New member created.');
        }

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.user-form');
    }
}
