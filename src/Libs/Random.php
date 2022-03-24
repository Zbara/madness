<?php

namespace App\Libs;

class Random
{
    public function fortune(int $count = 4): array
    {
        $cell = [];

        for ($i = 1; $i < $count; $i++) {
            $r = rand(1, 6);

            if ($r > 3) {
                $r2 = rand(0, 3);
                $r = $r - $r2;
            }
            $cell[$i] = $r;
        }
        return $cell;
    }
}
