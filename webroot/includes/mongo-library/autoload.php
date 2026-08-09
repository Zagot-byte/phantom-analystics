<?php
spl_autoload_register(function ($class) {
    $prefix = 'MongoDB\\';
    if (strncmp($class, $prefix, 8) !== 0) {
        return;
    }
    $file = __DIR__ . '/src/' . str_replace('\\', '/', substr($class, 8)) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});
