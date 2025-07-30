<?php
// Autoload para MyCLabs\Enum
spl_autoload_register(function ($class) {
    $prefix = 'MyCLabs\\Enum\\';
    $base_dir = __DIR__ . '/../MyCLabs/Enum/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Autoload para PhpSpreadsheet
spl_autoload_register(function ($class) {
    $prefix = 'PhpOffice\\PhpSpreadsheet\\';
    $base_dir = __DIR__ . '/src/PhpSpreadsheet/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Autoload para Psr\SimpleCache
spl_autoload_register(function ($class) {
    $prefix = 'Psr\\SimpleCache\\';
    $base_dir = __DIR__ . '/../Psr/SimpleCache/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Autoload para Composer\Pcre
spl_autoload_register(function ($class) {
    $prefix = 'Composer\\Pcre\\';
    $base_dir = __DIR__ . '/../Composer/Pcre/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Autoload para ZipStream (v2.x)
spl_autoload_register(function ($class) {
    $prefix = 'ZipStream\\';
    $base_dir = __DIR__ . '/../ZipStream/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});