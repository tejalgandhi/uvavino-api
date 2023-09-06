<?php
namespace App\Services\Filters;

class BaseFilter
{
    protected $model;
    protected $fields;
    protected $searables = [];

    public function getData(): array
    {
        $query = $this->model::query()
            ->select($this->fields);

        if(count($this->searables) && request()->has('query')) {
            $searables = $this->searables;
            $query = $query->where(function($q) use ($searables) {
                foreach($searables as $search_field) {
                    $q->orWhereLike($search_field, '%'.request()->get('query').'%');
                }
            });
        }

        return $query->get()->toArray();
    }
}
