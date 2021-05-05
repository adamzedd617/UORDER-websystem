<?php

	function error(){
		global $link;
	 	if($link === false){
	    die("ERROR: Could not connect. " . mysqli_connect_error());
		}
	}
	
	function delete(){
		global $link;
		global $sql;
		unset($link);
		unset($sql);
	}

	//Create Database for FYPDatabase
	$link = mysqli_connect("localhost","root","");
	error();
	$sql = "CREATE DATABASE FYPDatabase";
	mysqli_query($link,$sql);
	delete();



	//Create table for FYPDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE customers(
			customerid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(255) NOT NULL,
			password VARCHAR(255) NOT NULL,
			email VARCHAR(255) NOT NULL,
			phonenum VARCHAR(100) NOT NULL,
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP
			)";
	mysqli_query($link,$sql);
	delete();


	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE chair_table(
			v_id INT NOT NULL,
			tableid INT NOT NULL,
			chairofthetable INT NOT NULL,
			availability boolean DEFAULT true,
			date_time DATETIME DEFAULT CURRENT_TIMESTAMP
			)";
	mysqli_query($link,$sql);
	delete();


	
	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE walkin(
			walk_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			customerid INT NOT NULL,
			restaurant_name VARCHAR(255) NOT NULL,
			tableid INT NOT NULL,
			date_time DATETIME DEFAULT CURRENT_TIMESTAMP
			)";
	mysqli_query($link,$sql);
	delete();




	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE bookin(
			book_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			customerid INT NOT NULL,
			restaurant_name VARCHAR(255) NOT NULL,
			chairtaken INT NOT NULL,
			date_time DATETIME DEFAULT CURRENT_TIMESTAMP
			)";
	mysqli_query($link,$sql);
	delete();



	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE vendors(
			v_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(255) NOT NULL,
			password VARCHAR(255) NOT NULL,
			email VARCHAR(255) NOT NULL,
			phonenum VARCHAR(100) NOT NULL,
			created_at DATETIME DEFAULT CURRENT_TIMESTAMP
			)";
	mysqli_query($link,$sql);
	delete();


	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE aboutrestaurant(
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			v_id INT NOT NULL,
			background LONGBLOB NOT NULL,
			restaurant_name VARCHAR(255) NOT NULL,
			address VARCHAR(255) NOT NULL,
			location VARCHAR(255) NOT NULL,
			description VARCHAR(500) NOT NULL
			)";
	mysqli_query($link,$sql);
	delete();



	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE menu(
			menu_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			v_id INT NOT NULL,
			typed INT(10) NOT NULL,
			picture LONGBLOB NOT NULL,
			name VARCHAR(255) NOT NULL,
			description VARCHAR(255) NOT NULL,
			price DECIMAL DEFAULT '0.00' NOT NULL
			)";
	mysqli_query($link,$sql);
	delete();



	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE qrcodes(
			v_id INT NOT NULL,
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			qrUsename VARCHAR(255) NOT NULL,
			qrContent VARCHAR(255) NOT NULL,
			qrImg VARCHAR(255) NOT NULL,
			qrlink VARCHAR(255) NOT NULL
			)";
	mysqli_query($link,$sql);
	delete();
	
	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE staff(
			v_id INT(10) NOT NULL,
			staffid INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			staffname VARCHAR(255) NOT NULL,
			work_in VARCHAR(20) NOT NULL,
			gender enum('male', 'female') NOT NULL,
            email VARCHAR(20) NOT NULL,
            phonenumber VARCHAR(10) NOT NULL,
            secureqa VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
			)";
	mysqli_query($link,$sql);
	delete();


	  class RaviKoQr
		{
		  public $server = "localhost";
		  public $user = "root";
		  public $pass = "";
		  public $dbname = "FYPDatabase";
			public $conn;
		  public function __construct()
		  {
		  	$this->conn= new mysqli($this->server,$this->user,$this->pass,$this->dbname);
		  	if($this->conn->connect_error)
		  	{
		  		die("connection failed");
		  	}
		  }
		 	public function insertQr($qrUname,$final,$qrimage,$qrlink)
		 	{
		 			$sql = "INSERT INTO qrcodes(qrUsername,qrContent,qrImg,qrlink) VALUES('$qrUname','$final','$qrimage','$qrlink')";
		 			$query = $this->conn->query($sql);
		 			return $query;

		 	
		 	}
		 	public function displayImg()
		 	{
		 		$sql = "SELECT qrimg,qrlink from qrcodes where qrimg='$qrimage'";

		 	}
		}
		$meravi = new RaviKoQr();


	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE invoice(
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			customerid INT NOT NULL,
			restaurant_name VARCHAR(255) NOT NULL,
			tableid INT NOT NULL,
			pay INT NOT NULL,
			availability boolean DEFAULT false
			)";
	mysqli_query($link,$sql);
	delete();


	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE receipt(
			receipt_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			customerid INT NOT NULL,
			restaurant_name VARCHAR(255) NOT NULL,
			tableid INT NOT NULL,
			payed INT NOT NULL,
			date_time DATETIME DEFAULT CURRENT_TIMESTAMP
			)";
	mysqli_query($link,$sql);
	delete();


	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE kitchenview(
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			item_name VARCHAR(255) NOT NULL,
			quantity INT NOT NULL,
			tableid INT NOT NULL,
			availability boolean DEFAULT false
			)";
	mysqli_query($link,$sql);
	delete();


	//Create table for ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
	$sql = "CREATE TABLE feedback(
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			restaurant_name VARCHAR(255) NOT NULL,
			feedback VARCHAR(255) NOT NULL
			)";
	mysqli_query($link,$sql);
	delete();


	//connecting to ProjectDatabase database
	$link = mysqli_connect("localhost","root","","FYPDatabase");
	error();
?>
