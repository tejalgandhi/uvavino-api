<?php
namespace Database\Factories;

trait RandomizeTrait {
    private function randomize($cls, int $per = 10)
    {
        $r = rand(0,100);
        if($cls::count() == 0 || $r < $per) {
            return $cls::factory();
        }
        return $cls::inRandomOrder()->first()->id;
    }
}