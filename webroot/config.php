<?php
require_once __DIR__ . '/includes/mongo-library/autoload.php';
$client = new MongoDB\Client("mongodb://appdev:Apricot@Sunset#9@localhost:27017");
$db = $client->phantomdb;
$collection = $db->users;
