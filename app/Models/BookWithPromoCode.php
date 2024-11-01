<?php

namespace App\Models;

class BookWithPromoCode
{
    public function getPromoCodes(): array
    {
        return [
            'PROMO10%Off' => 10,
            'PROMO20%Off' => 20,
            'PROMO30%Off' => 30,
            'PROMO40%Off' => 40,
            'PROMO50%Off' => 50,
            'PROMO60%Off' => 60,
            'PROMO70%Off' => 70,
            'PROMO80%Off' => 80,
            'PROMO90%Off' => 90,
            'PROMO100%Off' => 100,
        ];
    }

    public function hasPromoCode(string $promoCode): bool
    {
        return array_key_exists($promoCode, $this->getPromoCodes());
    }
}
