<?php
	/*
	* 	This code utilizes the trim(), stripslashes(), and htmlspecialcharacters() functions
	*	to sanitize email to prevent SQL injections. 
	*/
	
	//----------- Local Variables ---------
	$name = "";
	$email = "";
	$message = "";
	$confirmation = "";
	$nameError = "";
	$emailError = "";
	$nameOK = false;
	$emailOK = false;
	
	//----------- PHP Functions ---------
	function testInput($data) 
	{
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
	
	if(isset($_POST["submit"]))
	{
		//Validate name
		if (empty($_POST["name"]))
		{
			$nameError = "<span class='errorMessage'>Please tell me your name.</span>";
			$nameOK = false;
		}
		else 
		{
			$name = testInput($_POST["name"]);
			$nameOK = true;
		}
		
		//Validate email address
		if (empty($_POST["email"]))
		{
			$emailError = "<span class='errorMessage'>Please tell me your email address.</span>";
			$emailOK = false;
		}
		else
		{
			$email = testInput($_POST["email"]);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
 				$emailError = "<span class='errorMessage'>Please enter a valid email address.</span>"; 
				$emailOK = false;	
			}
			else
			{
				$emailOK = true;
			}
		}
		
		//Validate message
		if (empty($_POST["message"]))
		{
			$message = "";
		}
		else
		{
			$message = testInput($_POST["message"]);
		}
		
		//send email if all is OK  
		if ($nameOK == true && $emailOK == true)
		{			
			$headers = "From: $email" . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";  
			$emailbody = "<p>You have recieved a new message from your portfolio.</p> 
						  <p><strong>Name: </strong> {$name} </p> 
						  <p><strong>Email: </strong> {$email} </p> 	  
						  <p><strong>Message: </strong> {$message} </p>"; 
			mail("name@domain.com","Message from your portfolio",$emailbody,$headers);
			
			//Clear email fields	
			$name = "";
			$email = "";
			$subject = "";
			$message = "";
			$nameError = "";
			$emailError = "";
			
			//Display email confirmation message
			$confirmation = "<span class='confirmation'>Thank you for contacting me.</span>";
		}
		
		//Jump to contact section
		echo "jumpToContact();";
	}
	
	if(isset($_POST["reset"]))
	{
		//Clear email fields	
		$name = "";
		$email = "";
		$subject = "";
		$message = "";
		$nameError = "";
		$emailError = "";
		$confirmation = "";
		
		//Jump to contact section
		echo "jumpToContact();";
	}
?>	