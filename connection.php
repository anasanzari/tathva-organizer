<?php

require_once 'config.php';
require_once './classes/Database.php';
require_once './classes/Event.php';
require_once './classes/EventCategory.php';
require_once './classes/User.php';
require_once './classes/Session.php';
require_once './classes/Location.php';


$db = new Database(DB_SERVER,DB_USER,DB_PASS,DB_DATABASE);

?>
