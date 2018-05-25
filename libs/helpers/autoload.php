<?php
/**
 * Class Autoloader
 */
spl_autoload_register(function ($className) {
    // file
    $file =  DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    // find in helpers dir
    $helperDir = __DIR__;
    if (file_exists($helperDir . $file)) {
        require_once $helperDir . $file;
    }

    // find in traits dir
    if (file_exists($helperDir . DIRECTORY_SEPARATOR . 'Traits' . $file)) {
        require_once $helperDir . DIRECTORY_SEPARATOR . 'Traits' . $file;
    }

    // find in libs dir
    $libsDir = dirname(__DIR__);
    if (file_exists($libsDir . $file)) {
        require_once $libsDir . $file;
    }
});