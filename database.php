<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login_register";

// Csatlakozás a MySQL szerverhez
$conn = mysqli_connect($hostName, $dbUser, $dbPassword);

// Ellenőrizzük a kapcsolatot
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ellenőrizzük, hogy létezik-e az adatbázis, ha nem, létrehozzuk
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if (mysqli_query($conn, $sql)) {
    echo "Database created or already exists.<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

// Most csatlakozzunk a létrehozott adatbázishoz
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

// Ellenőrizzük, hogy sikerült-e a csatlakozás az adatbázishoz
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Létrehozzuk a users táblát, ha még nem létezik
$tableSql = "CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if (mysqli_query($conn, $tableSql)) {
    echo "Table 'users' created or already exists.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

?>