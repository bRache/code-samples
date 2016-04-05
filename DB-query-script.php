<?php
/*
*	The script uses PDO statements to retrieve product and image data from a database.
*	Additional data to display is selected through a case statement based on a unique 
*	type value for each product.
*/

//----------- Required Files ---------
require_once('../connectionsfolder/yourconnections.php');
require_once('../connectionsfolder/yourconfiguration.php');

//----------- Connect to Database -----------
try
{
	$connect = new PDO($dsn, $username, $password, $options);
}
catch(PDOException $exc)
{
	echo "<p class='msgWarning'>Unable to connect to database: jumps.php: 1</p>";	
	//echo $exc->getMessage().$exc->getMessage()."\n";
	//$mytrace = $exc->getTrace();
	//print_r($mytrace);
	//echo "<p>" .$exc->getTraceAsString(). "<p>";
}

//----------- Retrieve product data from product table -----------
try
{
	//Get prodcut id from list page
	$mjid = $_GET["mjid"];
	
	//Get prodcut id from products table
	$jumpsString = "SELECT * FROM mjjumps WHERE id = :mjid";
	$statementJumpsString = $connect->prepare($jumpsString);
	$statementJumpsString->bindValue(':mjid', $mjid);
	$statementJumpsString->execute();
	
	if($statementJumpsString->rowCount() != 0)		//If row exists issue SELECT statement to database
	{
		foreach($statementJumpsString as $row)
		{
			$type = $row["type"];
			$name = $row["name"];
			$description = $row["description"];
			$size = $row["size"];
			$description = $row["description"];			
			$area = $row["area"];
			$halfday = $row["halfday"];
			$fullday = $row["fullday"];
			$overnight = $row["overnight"];
			$nextday = $row["nextday"];
			$twonights = $row["twonights"];
			$title = $row["title"];
			$metakeywords = $row["metakeywords"];	
			$metadescription = $row["metadescription"];		
		}
		
		$statementJumpsString->closecursor();	
	}
	else {
		header('Location:productError.html');
	}
}	

catch(PDOException $exc)
{
	echo "<p class='msgWarning'>Database error: jumps.php: 2</p>";
	//echo $exc->getMessage().$exc->getMessage()."\n";
	//$mytrace = $exc->getTrace();
	//print_r($mytrace);
	//echo "<p>" .$exc->getTraceAsString(). "<p>";
}

//----------- Retrieve image data from images table-----------

try
{
	$imageString = "SELECT * FROM mjjumpimages WHERE id = :mjid";
	$statementImageString = $connect->prepare($imageString);
	$statementImageString->bindValue(':mjid', $mjid);
	$statementImageString->execute();
	
	foreach($statementImageString as $row)
	{
		$imagepath = $row["imagepath"];	
		$alt = $row["alt"];
		$width = $row["width"];
		$height = $row["height"];
	}
	
	$statementImageString->closeCursor();
}
catch(PDOException $exc)
{
	echo "<p class='msgWarning'>Database error: jumps.php: 3</p>";
	//echo $exc->getMessage().$exc->getMessage()."\n";
	//$mytrace = $exc->getTrace();
	//print_r($mytrace);
	//echo "<p>" .$exc->getTraceAsString(). "<p>";
}


//----------- Format page data -----------
switch (strval($type)) {
	case "1": //Jumps
		if($fullday != 0)
		{
			$price1 = "$" . $fullday . " bounce house rental for all day*";
		}
		else {
			$price1 = "";
		}
		if($overnight != 0)
		{
			$price2 = "Keep overnight for just $" . $overnight;
		}
		else {
			$price2 = "";	
		}
		if($nextday != 0)
		{
			$price3 = "Keep overnight and next day for only $" . $nextday;
		}
		else {
			$price3 = "";	
		}
		if($twonights != 0)
		{
			$price4 = "Keep overnight for 2 nights for $" . $twonights;
		}
		else {
			$price4 = "";		
		}
		$price5 = "";
		$terms1 = "* All day means any 8 hours of the day.";
		$terms2 = "** Overnight rentals must be picked up between 8 a.m. and noon.";
		$terms3 = "";
		$terms4 = "";
		break;	
	case "2": //Obstacle Courses
		if($halfday != 0)
		{
			$price1 = "$" . $halfday . " rental for 4 hours";
		}
		else {
			$price1 = "";		
		}		
		if($fullday != 0)
		{
			$price2 = "$" . $fullday . " for all day*";
		}
		else {
			$price2 = "";		
		}				
		$price3 = "";
		$price4 = "";
		$price5 = "";		
		$terms1 = "* All day means any 8 hours of the day.";
		$terms2 = "This unit cannot be kept overnight.";
		$terms3 = "";
		$terms4 = "";
		break;
	case "3": //Interactive
		if($fullday != 0)
		{
			$price1 = "$" . $fullday . " for all day*";
		}
		else {
			$price1 = "";		
		}			
		if($overnight != 0)
		{
			$price2 = "Keep overnight for just $" . $overnight;
		}
		else {
			$price2 = "";		
		}		
		$price3 = "";
		$price4 = "";		
		$price5 = "";
		$terms1 = "* All day means any 8 hours of the day.";
		$terms2 = "Overnight rentals must be picked up between 8 a.m. and noon.";		
		$terms3 = "";
		$terms4 = "";
		break;
	case "4": //Dunk Tank
		if($fullday != 0)
		{
			$price1 = "$" . $fullday . " for all day*";
		}
		else {
			$price1 = "";		
		}	
		$price2 = "";
		$price3 = "";	
		$price4 = "";				
		$price5 = "";
		$terms1 = "* All day means any 8 hours of the day.";
		$terms2 = "";	
		$terms3 = "";
		$terms4 = "";
		break;
	case "5": //Outdoor Theater
		if($fullday != 0)
		{
			$price1 = "$" . $fullday . " for all night*";
		}
		else {
			$price1 = "";		
		}	
		$price2 = "";
		$price3 = "";	
		$price4 = "";				
		$price5 = "";
		$terms1 = "* All night means the customer keeps the equipment overnight. The customer is required to store the electrical equipment in a secure location until it is picked up sometime after 8 a.m. the next day.";
		$terms2 = "Moon Jump will provide projector, speakers, and DVD player. The customer must provide their own movies. 
				  <p><a href='../outdoorTheater/outdoorTheater.html'>Additional Terms and Conditions</a></p>";	
		$terms3 = "";
		$terms4 = "";
		break;
	case "6": //Water Slides & Slip n' Slides
		if($halfday != 0)
		{
			$price1 = "$" . $halfday . " rental for 4 hours";
		}
		else {
			$price1 = "";		
		}		
		if($fullday != 0)
		{
			$price2 = "$" . $fullday . " for all day*";
		}
		else {
			$price2 = "";		
		}				
		$price3 = "";
		$price4 = "";
		$price5 = "";
		$terms1 = "* All day means any 8 hours of the day.";
		$terms2 = "This unit cannot be set up on concrete.";
		
		if($mjid == 52) {		
			$terms3 = "This unit cannot be kept overnight.";
			$terms4 = "This unit will not fit through fences or gates less than 4ft. wide.";
		}
		else {
			$terms3 = "";
			$terms4 = "";			
		}
		break;	
	case "7": //Combos
		if($halfday != 0)
		{
			$price1 = "$" . $halfday . " rental for 4 hours";
		}
		else {
			$price1 = "";		
		}		
		if($fullday != 0)
		{
			$price2 = "$" . $fullday . " for all day*";
		}
		else {
			$price2 = "";		
		}
		if($overnight != 0)
		{
			$price3 = "Keep overnight for just $" . $overnight;
		}
		else {
			$price3 = "";	
		}				
		if($nextday != 0)
		{
			$price4 = "Keep overnight and next day for only for $" . $nextday;
		}
		else {
			$price4 = "";		
		}
		$price5 = "";
		$terms1 = "* All day means any 8 hours of the day.";
		$terms2 = "Overnight rentals must be picked up between 8 a.m. and noon.";
		$terms3 = "";
		$terms4 = "";
		break;														
	case "8": //Dry SLides
		if($halfday != 0)
		{
			$price1 = "$" . $halfday . " rental for 4 hours";
		}
		else {
			$price1 = "";		
		}		
		if($fullday != 0)
		{
			$price2 = "$" . $fullday . " for all day*";
		}
		else {
			$price2 = "";		
		}
		$price3 = "";
		$price4 = "";
		$price5 = "";
		$terms1 = "* All day means any 8 hours of the day.";
		$terms2 = "This unit cannot be set up on concrete.";
		
		if($mjid == 70) {	
			$terms3 = "This unit will not fit through fences or gates less than 4ft. wide.";
		}
		else {
			$terms3 = "";
		}
		$terms4 = "";
		break;														
	default:	
		header('Location:productError.html');
}

?>