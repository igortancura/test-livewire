<?php

return [
    'container' => [
        'root' => 'Root',
        'actions' => [
            \App\Enums\Tree\FormType::ADD->value => '+',
            \App\Enums\Tree\FormType::EDIT->value => '🗘',
            \App\Enums\Tree\FormType::DELETE->value => '-',
        ]
    ],
    'forms' => [
        'labels_name' => [
            \App\Enums\Tree\FormType::ADD->value => 'Enter a name to add an item',
            \App\Enums\Tree\FormType::EDIT->value => 'Change the name of the element',
            \App\Enums\Tree\FormType::DELETE->value => 'To delete an element and its descendants, enter the ":name" of the element to be deleted',
        ],
        'buttons' => [
            'cancel' => 'Cancel',
            'actions' => [
                \App\Enums\Tree\FormType::ADD->value => 'Add',
                \App\Enums\Tree\FormType::EDIT->value => 'Update',
                \App\Enums\Tree\FormType::DELETE->value => 'Delete',
            ]
        ],
        'errors' => [
            'wrong_name_delete' => 'Wrong name',
            'not_find_element' => 'No element found',
            'not_form_type' => 'Wrong form type',
            'error_add' => 'Error adding item',
            'error_update' => 'Error updating item',
            'error_delete' => 'Error deleting item',

        ]
    ]
];
