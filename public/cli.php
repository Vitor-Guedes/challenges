<?php

use Guedes\Challenges\Manager;

include __DIR__ . '/../vendor/autoload.php';


$options = getopt("c:f:");


(new Manager($options))->resolve();