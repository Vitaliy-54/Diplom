<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Note;

class NotesList extends Component
{
    public $notes;

    public function mount()
    {
        // Загружаем только публичные заметки
        $this->notes = Note::where('is_public', true)
                           ->orderBy('created_at', 'DESC')
                           ->get();
    }

    public function render()
    {
        return view('livewire.notes-list');
    }
}
