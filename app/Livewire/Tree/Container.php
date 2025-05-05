<?php

namespace App\Livewire\Tree;

use App\Enums\Tree\FormType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;
use App\Models\Tree;
use Livewire\Attributes\On;

class Container extends Component
{
    /**
     * @var int
     */
    public int $parentId;
    public bool $isOpen;
    public Collection $elements;

    public function mount(int $parentId = 0): void
    {
        $this->parentId = $parentId;
        $this->isOpen = empty($parentId);
        $this->setElements(empty($parentId));
    }

    #[On('element-toggle-{parentId}')]
    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
        $this->setElements($this->isOpen);
    }

    #[On('refresh-element-{parentId}')]
    public function refresh(int $type): void
    {
        if ($type != FormType::EDIT->value) {
            $this->isOpen = true;
        }
        $this->setElements(true);
    }

    protected function setElements(bool $readModel): void
    {
        $query = $this->parentId ? Tree::where('parent_id', $this->parentId) : Tree::whereNull('parent_id');
        $this->elements = $readModel ? $query->get() : new Collection();
    }

    public function render(): View
    {
        return view('livewire.tree.container');
    }
}
