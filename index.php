<?php
require 'vendor/autoload.php';

use Application\Application;

$application = new Application();

$code = $application->getCode();

var_dump($code);
