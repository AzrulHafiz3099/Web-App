<?php
	$dbHost = "localHost";
	$dbUser = "root";
	$dbPass = "";
	
	$conn = mysqli_connect($dbHost, $dbUser, $dbPass);

	$dbName = "vendor";

	$sql = "DROP DATABASE IF EXISTS $dbName;
       		CREATE DATABASE IF NOT EXISTS $dbName;";
	mysqli_multi_query($conn, $sql);

	$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or die("Database Connection Failed!");

	$sql = 
	"
CREATE TABLE admin
(
	admin_id     INT(10)        	NOT NULL   		PRIMARY KEY,
	username     VARCHAR(30) 		NOT NULL   	 	UNIQUE,
	password     VARCHAR(20) 		NOT NULL
);
";

	mysqli_multi_query($conn, $sql);
?>