<?php

use Stevebauman\Purify\Facades\Purify;

     function cleanInputs(array &$array): void
    {
        foreach ($array as $field => &$value) {
            if (!is_array($value)) {
                $value = Purify::clean($value);
            }
        }
    }

