<?php

use Guedes\Challenges\Manager;

include __DIR__ . '/../vendor/autoload.php';

$options = getopt("c:f:");

$manager = new Manager($options);
$solution = $manager->resolve();
$manager->show($solution);