<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
    // dependencies were installed via composer - this is the main project
    require __DIR__ . "/../vendor/autoload.php";
} else {
    throw new \Exception('Can\'t find autoload.php. Did you install dependencies via composer?');
}
