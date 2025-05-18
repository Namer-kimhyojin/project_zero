<?php

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) return;

    $relative_class = substr($class, strlen($prefix));
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        echo "<pre>⛔ 클래스 파일을 찾을 수 없습니다: $file</pre>";
    }
});
