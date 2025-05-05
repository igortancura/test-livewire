<?php

namespace App\Livewire\Tree;

use App\Enums\Tree\FormType;
use App\Livewire\Forms\Tree\EditForm;
use App\Models\Tree;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Exceptions\Tree\ActionException;

class Form extends Component
{
    private const DELETE_TIMER = 100;
    public bool $isOpen = false;

    private bool $isStartTimer = false;

    public int $typeForm;

    public int $elementId;

    public EditForm $form;

    #[On('element-open-form')]
    public function openForm(int $type, int $elementId): void
    {
        $this->isOpen = true;
        $this->typeForm = $type;
        $this->elementId = $elementId;
        switch ($type) {
            case FormType::EDIT->value:
                $element = Tree::findOrFail($elementId);
                $this->form->name = $element->name;
                break;
            case FormType::DELETE->value:
                $this->isStartTimer = true;
                $this->dispatch("start-timer");
                break;
        }
    }

    public function closeForm(): void
    {
        $this->isOpen = false;
        $this->dispatch("close-form");
        $this->form->reset();
        $this->form->resetErrorBag();
    }

    public function submit(int $type, int $elementId): void
    {
        try {
            $this->form->save($type, $elementId);
        } catch (ActionException $exception) {
            session()->flash('action_error', trans('tree.forms.errors.' . $exception->getMessage()));
            $this->form->reset();
            $this->form->resetErrorBag();
            return;
        }

        $this->closeForm();
        if ($type == FormType::DELETE->value) {
            $this->dispatch("refresh-element-0", $type);
        } else {
            $this->dispatch("refresh-element-{$elementId}", $type);
        }

    }

    public function render()
    {
        return view('livewire.tree.form', [
            'startingTimer' => self::DELETE_TIMER,
            'isStartTimer' => $this->isStartTimer,
        ]);
    }
}
