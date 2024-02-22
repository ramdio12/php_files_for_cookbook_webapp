<?php


class Utilities
{
    // sanitize input values
    public static function sanitizeInput($input)
    {
        $input = trim($input);
        $input = htmlspecialchars($input);
        $input = stripslashes($input);
        return $input;
    }
}
