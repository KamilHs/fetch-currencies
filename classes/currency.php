<?php

class  Currency
{
    public function __construct(string $code, string $nominal, string $name, float $value)
    {
        $this->code = $code;
        $this->nominal = $nominal;
        $this->name = $name;
        $this->value = $value;
    }
}
