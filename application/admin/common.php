<?php

function removeXSS($val) {
    static $obj = null;

    if ($obj === null) {
        $obj = new HTMLPurifier();
    }
    return $obj->purify($val);
}