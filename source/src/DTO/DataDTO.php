<?php declare(strict_types=1);

namespace App\DTO;

final class DataDTO
{
    private string $bin;
    private float $amount;
    private string $currency;

    public function getBin(): string
    {
        return $this->bin;
    }

    public function setBin(string $bin): DataDTO
    {
        $this->bin = $bin;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): DataDTO
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): DataDTO
    {
        $this->currency = $currency;

        return $this;
    }
}
