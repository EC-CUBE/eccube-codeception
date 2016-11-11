<?php
$_SERVER['HTTP_X_CODECEPTION_CODECOVERAGE'] = 1;

require __DIR__.'/../autoload.php';
require __DIR__.'/../c3.php';
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
$app = \Eccube\Application::getInstance(array('output_config_php' => false));
$app->initialize();
$app->initializePlugin();
$app->run();
