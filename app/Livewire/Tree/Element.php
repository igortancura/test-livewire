<?php

namespace App\Livewire\Tree;

use App\Enums\Tree\FormType;
use App\Models\Tree;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Element extends Component
{
    /**
     * @var Tree|null
     */
    public Tree|null $element;

    /**
     * @var bool
     */
    public bool $isParent = false;

    /**
     * @var bool
     */
    public bool $isOpen = false;

    /**
     * @var int
     */
    public int $elementId = 0;

    /**
     * @param Tree|null $element
     * @return void
     */
    public function mount(Tree|null $element = null): void
    {
        $this->element = $element;
        if (!empty($this->element->id)) {
            $this->elementId = $this->element->id;
        }
        $this->isParent = (bool)$this->element->is_parent;
    }

    /**
     * @return void
     */
    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
        $this->dispatch("element-toggle-{$this->element->id}");
    }

    /**
     * @param int $type
     * @return void
     */
    #[On('refresh-element-{elementId}')]
    public function refresh(int $type): void
    {
        $this->isParent = (bool)$this->element->is_parent;
        if ($type != FormType::EDIT->value) {
            $this->isOpen = true;
        }
    }

    /**
     * @param int $type
     * @param int $elementId
     * @return void
     */
    public function openForm(int $type, int $elementId): void
    {
        $this->dispatch("element-open-form", $type, $elementId);
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.tree.element');
    }
}
