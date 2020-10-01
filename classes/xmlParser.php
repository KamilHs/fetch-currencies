<?php


class xmlParser
{
    static function parse($xml)
    {
        $date = $xml->attributes()->{"Date"};
        $currencies = array();
        foreach ($xml->ValType[1] as $_ => $v) {
            array_push($currencies, array(
                "code" => $v->attributes()->{"Code"},
                "nominal" => $v->Nominal,
                "name" => $v->Name,
                "value" => floatval($v->Value)
            ));
        };

        return array("date" => $date, "currencies" => $currencies);
    }
}
