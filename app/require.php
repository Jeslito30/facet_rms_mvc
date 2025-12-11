<?php
// Load config
require_once 'config.php';

// Load libraries
require_once 'libraries/Core.php';
require_once 'libraries/Controller.php';
require_once 'libraries/Database.php';

// Load helpers
foreach (glob(__DIR__ . '/helpers/*_helper.php') as $filename) {
    require_once $filename;
}

// Instantiate core class
$init = new Core();
