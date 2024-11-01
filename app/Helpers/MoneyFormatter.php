<?php

namespace App\Helpers;

class MoneyFormatter
{
    public static function format($number): float
    {
        // Convert to string to handle float numbers
        $number_str = strval($number);

        // Find the position of the decimal point
        $decimal_pos = strpos($number_str, '.');

        // If decimal point exists
        if ($decimal_pos !== false) {
            // Get the integer part
            $integer_part = substr($number_str, 0, $decimal_pos);
            // Get the decimal part
            $decimal_part = substr($number_str, $decimal_pos + 1);
            // If decimal part has more than 2 digits, truncate it
            if (strlen($decimal_part) > 2) {
                $decimal_part = substr($decimal_part, 0, 2);
            }
            // Pad the decimal part if it has less than 2 digits
            $decimal_part = str_pad($decimal_part, 2, '0', STR_PAD_RIGHT);
            // Concatenate integer part and decimal part with a dot
            $formatted_number = $integer_part . '.' . $decimal_part;
        } else {
            // If no decimal point, append '.00'
            $formatted_number = $number_str . '.00';
        }

        return (float) $formatted_number;
    }
}
