<?php

namespace App\Livewire\Forms\Tree;

use App\Enums\Tree\FormType;
use App\Models\Tree;
use Livewire\Form;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Tree\ActionException;


class EditForm extends Form
{
    public string $name = '';
    private int $type;
    private int $elementId;

    private Tree|null $element;

    public function boot(): void
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                if (
                    $this->type == FormType::DELETE->value &&
                    !empty($this->element) &&
                    $this->name != $this->element->name
                ) {
                    $validator->errors()->add('name', trans('tree.forms.errors.wrong_name_delete'));
                }
            });
        });
    }

    public function save(int $type, int $elementId): void
    {
        $this->type = $type;
        $this->elementId = $elementId;
        if ($this->elementId) {
            try {
                $this->element = Tree::findOrFail($elementId);
            } catch (\Exception $exception) {
                throw new ActionException('not_find_element');
            }
        }

        $validated = $this->validate();
        switch ($type) {
            case FormType::ADD->value:
                $this->add($validated);
                break;
            case FormType::EDIT->value:
                $this->update($validated);
                break;
            case FormType::DELETE->value:
                $this->delete();
                break;
            default:
                throw new ActionException('not_form_type');
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
        ];
    }

    private function add(array $data): void
    {
        DB::beginTransaction();

        try {
            if (!empty($this->element) && !$this->element->is_parent) {
                $this->element->update(['is_parent' => true]);
            }
            Tree::create(empty($this->element) ? $data : array_merge($data, ['parent_id' => $this->elementId]));
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ActionException('error_add');
        }
        DB::commit();
    }

    private function update(array $data): void
    {
        try {
            $this->element->update($data);
        } catch (\Exception $exception) {
            throw new ActionException('error_update');
        }
    }

    private function delete(): void
    {
        try {
            $this->element->delete();
        } catch (\Exception $exception) {
            throw new ActionException('error_delete');
        }
    }
}
