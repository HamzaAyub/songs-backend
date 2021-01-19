<?php
	//database configuration
	$host 		= "localhost";
	$user 		= "romawuiv_root";
	$pass 		= "public!195!";
	$database	= "romawuiv_netflox";
//	$host 		= "localhost";
//	$user 		= "root";
//	$pass 		= "";
//	$database	= "ytlist";
	$connect 	= new mysqli($host, $user, $pass,$database) or die("Error : ".mysql_error());


	//set path url for your video uploaded
	$video_base_url = "upload/"

?>