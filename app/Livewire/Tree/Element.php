<?php

namespace App\Livewire\Tree;

use App\Enums\Tree\FormType;
use App\Models\Tree;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Element extends Component
{
    public Tree|null $element;

    public bool $isParent = false;
    public bool $isOpen = false;

    public int $elementId = 0;

    public function mount(Tree|null $element = null): void
    {
        $this->element = $element;
        if (!empty($this->element->id)) {
            $this->elementId = $this->element->id;
        }
        $this->isParent = (bool)$this->element->is_parent;
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
        $this->dispatch("element-toggle-{$this->element->id}");
    }

    #[On('refresh-element-{elementId}')]
    public function refresh(int $type): void
    {
        $this->isParent = (bool)$this->element->is_parent;
        if ($type != FormType::EDIT->value) {
            $this->isOpen = true;
        }
    }

    public function openForm(int $type, int $elementId): void
    {
        $this->dispatch("element-open-form", $type, $elementId);
    }

    public function render(): View
    {
        return view('livewire.tree.element');
    }
}
