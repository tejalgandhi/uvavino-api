<?php
namespace App\Services\Filters\StaticFilter;

class BaseFilter
{
    protected $enum;
    protected $searables = [];
    protected static $values = [];

    public function getData(): array
    {
        $values = collect($this->enum::cases())
            ->map(function ($enum) {
                return [
                    'id' => $enum->value,
                    'name' => $enum->label($enum),
                ];
            })->toArray();

        return $values;
    }

}
