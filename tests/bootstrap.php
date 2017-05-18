<?php

spl_autoload_register(
    function ($class) {
        global $pth;

        $parts = explode('\\', $class, 2);
        if ($parts[0] == 'Browserupdate') {
            include_once './classes/' . $parts[1] . '.php';
        }
    }
);

?>
