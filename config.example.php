<?php

return (object) [
  "BASE_URL" => '/', // IF APPLICATION ARE IN SUBFOLDERS
  "PUBLIC_PATH" => __DIR__ . '/public/',
  "APP_PATH" => __DIR__ . '/src/',
  "ROOT_PATH" => __DIR__ . '/',
  
  "debug" => true,
  "log" => true,
  'db' => [
      'driver'    => '',
      'host'      => '',
      'database'  => '',
      'username'  => '',
      'password'  => '',
      'port'      => '',
      'charset'   => '',
      'collation' => '_unicode_ci',
      'prefix'    => ''
  ]
  
];
