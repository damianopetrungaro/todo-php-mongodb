<?php

require __DIR__ . '/../vendor/autoload.php';
(new \Dotenv\Dotenv(__DIR__ . '/../'))->load();
return require __DIR__ . '/container.php';