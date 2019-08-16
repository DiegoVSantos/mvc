<?php
    define('SERVER', "localhost");
    define('DB', "biblioteca_publica");
    define('USER', "root", true);
    define('PASS', "", true);

    define('SGBD', "mysql:host=".SERVER.";dbname=".DB);

    define('METHOD', 'AES-256-CBC', true);
    define('SECRET_KEY', 'Trakinas!@#', true);
    define('SECRET_IV', '134679', true);
