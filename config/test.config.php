<?php
return array(
    'db' => array (
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=zf2napratica_test;host=localhost',
        'username' => 'teste',
        'password' => '1234',
        'driver_options' => array (
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        )
    )
);