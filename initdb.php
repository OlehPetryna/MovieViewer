<?php
array_shift($argv);

list($dbHost,$dbName,$dbUser,$dbPass) = $argv;

$pdo = new \PDO("mysql:host=$dbHost;dbname=$dbName;",$dbUser,$dbPass);
$pdo->exec("DROP TABLE IF EXISTS `Movie`;");

echo "dropped table Movie if exists\n";

$sql ="CREATE TABLE Movie(
     id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR( 150 ) NOT NULL, 
     releaseYear INT( 10 ) NOT NULL, 
     format ENUM ('VHS','DVD','Blu-ray') NOT NULL, 
     actors TEXT NOT NULL)
     CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
$pdo->exec($sql);

echo "created table Movie\n";

die;

