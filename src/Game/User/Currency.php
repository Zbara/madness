<?php

namespace App\Game\User;

use App\Entity\Users;

class Currency
{

    const CURRENCY = ['money', 'bills', 'pills'];

    const TYPE_1 = 'money';
    const TYPE_2 = 'bills';
    const TYPE_3 = 'bills';

    const START = [
        'money' => 700,
        'bills' => 3,
        'pills' => 700
    ];

    const MINUS = 1;
    const PLUS = 2;

    public function getCurrency(array $currency, string $type = 'all'): array
    {
        $items = [];

        foreach ($currency as $i => $item) {
            $items[$i] = $item;
        }
        return $items;
    }

    public function startCurrency(): array
    {
        $items = [];

        foreach (self::START as $i => $item) {
            $items[$i] = $item;
        }
        return $items;

    }

    public function calculator(array $currency, int $operation = -1, int $number = 0, string $type = 'default'): array
    {
        switch ($operation) {
            case self::MINUS:
                $currency[$type] -= $number;
                break;
            case self::PLUS:
                $currency[$type] += $number;
                break;
            default:
                return $currency;
        }
        return $currency;
    }
}
