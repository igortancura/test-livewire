<?php

namespace App\Livewire\Forms\Tree;

use App\Enums\Tree\FormType;
use App\Models\Tree;
use Livewire\Form;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Tree\ActionException;


class EditForm extends Form
{
    /**
     * @var string
     */
    public string $name = '';

    /**
     * @var int
     */
    private int $type;

    /**
     * @var int
     */
    private int $elementId;

    /**
     * @var Tree|null
     */
    private Tree|null $element;
    /**
     * @var int|null
     */
    private int|null $parentDeletedElementId = null;

    /**
     * @return void
     */
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

    /**
     * @param int $type
     * @param int $elementId
     * @return void
     * @throws ActionException
     * @throws \Illuminate\Validation\ValidationException
     */
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

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|regex:/^[а-яА-ЯёЁa-zA-Z0-9\s]+$/|min:3|max:50',
        ];
    }

    /**
     * @param array $data
     * @return void
     * @throws ActionException
     */
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

    /**
     * @param array $data
     * @return void
     * @throws ActionException
     */
    private function update(array $data): void
    {
        try {
            $this->element->update($data);
        } catch (\Exception $exception) {
            throw new ActionException('error_update');
        }
    }

    /**
     * @return void
     * @throws ActionException
     */
    private function delete(): void
    {
        try {
            $this->parentDeletedElementId = $this->element->parent_id;
            $this->element->delete();
            if (!empty($this->parentDeletedElementId)) {
                if (Tree::where(['parent_id' => $this->parentDeletedElementId])->count() <= 0) {
                    Tree::where(['id' => $this->parentDeletedElementId])->update(['is_parent' => false]);
                }
            }
        } catch (\Exception $exception) {
            throw new ActionException('error_delete');
        }
    }

    /**
     * @return int
     */
    public function getParentDeletedElementId(): int
    {
        return empty($this->parentDeletedElementId) ? 0 : $this->parentDeletedElementId;
    }
}
