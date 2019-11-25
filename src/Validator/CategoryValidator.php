<?php

namespace App\Validator;

use App\Table\CategoryTable;

class CategoryValidator extends AbstractValidator
{
    protected $data;
    protected $validator;

    public function __construct(array $data, CategoryTable $categoryTable, ?int $categoryId = null)
    {
        parent::__construct($data);
        $this->validator->rule('required',['name', 'slug']);
        $this->validator->rule('lengthBetween',['name', 'slug'],3,200);
        $this->validator->rule('slug','slug');
        $this->validator->rule(function($field, $value) use($categoryTable, $categoryId) {
            return !$categoryTable->exists($field, $value, $categoryId);
        }, ['slug','name'])->message('Cette valeur existe déjà pour ce champ');
    }
}