<?php

require_once "classes/currency.php";

class DailyData
{
    private $currencies = array();
    private $date;
    public function __construct($data)
    {
        $this->date = $data["date"];
        foreach ($data["currencies"] as $_ => $currency) {
            array_push($this->currencies, new Currency(
                $currency["code"],
                $currency["nominal"],
                $currency["name"],
                $currency["value"]
            ));
        };
    }

    public function getData()
    {
        return array("Date" => $this->date, "Currencies" => $this->currencies);
    }
}
