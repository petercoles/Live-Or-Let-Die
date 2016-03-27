<?php

function redirect($route) {
    return $route;
}

function response($param1, $param2 = 200) {
    return [$param1, $param2];
}
