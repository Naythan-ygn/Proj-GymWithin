<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public ?User $userToDelete = null; // Track user for the modal

    public ?User $selectedUser = null; // Current User

    public function showPreview(User $user)
    {
        $this->selectedUser = $user;
        $this->dispatch('modal-show', name: 'user-preview-drawer');
    }

    public function confirmDelete(User $user)
    {
        $this->userToDelete = $user;
        $this->dispatch('modal-show', name: 'delete-user-modal'); // Trigger Flux Modal
    }

    public function deleteUser()
    {
        if ($this->userToDelete) {
            $this->userToDelete->delete();
            $this->userToDelete = null;
            $this->dispatch('modal-close', name: 'delete-user-modal');
        }
    }

    public function render()
    {
        return view('livewire.admin.user-index', [
            'users' => User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->paginate(10),
        ]);
    }
}
