<?php

error_reporting(0);

// (A) PROCESS RESULT
$result = "";

// (B) CONNECT TO DATABASE - CHANGE SETTINGS TO YOUR OWN!
$dbhost = "localhost";
$dbname = "zuzu";
$dbchar = "utf8";
$dbuser = "root";
$dbpass = "";
try {
  $pdo = new PDO(
    "mysql:host=$dbhost;dbname=$dbname;charset=$dbchar",
    $dbuser, $dbpass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (Exception $ex) { $result = $ex->getMessage(); }

/**/
if ($result=="") {
  try {
    $stmt = $pdo->prepare("INSERT INTO `customer` (`name`, `email`, `address`, `zipcode`, `city`) VALUES (?,?,?,?,?)");
    $stmt->execute([$_POST["name"], $_POST["email"], $_POST["address"], $_POST["zipcode"], $_POST["city"]]);
    include_once("/index.php");
  } catch (Exception $ex) { $result = $ex->getMessage(); }
}