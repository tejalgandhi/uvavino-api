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
                    'id' => $enum->value($enum),
                    'name' => $enum->value,
                ];
            })->toArray();

        return $values;
    }


    public function getFilter($filter = []): array
    {
        $values = collect($this->enum::cases())
            ->map(function ($enum) {
                return [
                    'name' => $enum->value,
                    'value' => $enum->value($enum),
                ];
            })->toArray();
        dd($values);
//       dd('dd');
    }


}
