<?php
declare(strict_types=1);
spl_autoload_register(static function (string $class): void {
    if (strncmp($class, 'SMSPVA\\', 7) === 0) {
        $file = __DIR__ . '/src/' . str_replace('\\', '/', substr($class, 7)) . '.php';
        if (is_file($file)) require $file;
    }
});
