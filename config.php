<?php
include_once('src/db.php');
include_once('src/model.php');
include_once('src/order_list.php');
include_once('src/order_item.php');
include_once('src/firebaseLib.php');

$server_sql 	= 'localhost';
$user_sql 		= 'root';
$pass_sql 		= '';
$database 		= '';

$db 			= new db ();
$db->setServer   ( $server_sql );
$db->setUsername ( $user_sql   );
$db->setPassword ( $pass_sql   );
$db->setDatabase ( $database   );

//firebase
const DEFAULT_URL 		= 'https://demoproject-d1e73.firebaseio.com';
const DEFAULT_TOKEN 	= 'n9resAy6zuZEo0JxTyUqD39tqfgGCrSAvsxh0soq';
$firebase 				= new \Firebase\FirebaseLib( DEFAULT_URL, DEFAULT_TOKEN );

// $firebase->set('/staff/order/refresh', time());