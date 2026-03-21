<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (!class_exists(Dotenv::class)) {
    throw new LogicException('Exécutez « composer install » pour installer les dépendances.');
}

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
