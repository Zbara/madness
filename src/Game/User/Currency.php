<?php

namespace App\Game\User;

use App\Entity\Users;

class Currency
{
    const CURRENCY = ['money', 'bills', 'pills', 'other'];

    public function getCurrency(array $currency, string $type = 'all'): array
    {
        $items = [];

        foreach ($currency as $i =>  $item) {
            $items[self::CURRENCY[$i]] = $item;
        }
        return $items;
    }
}
