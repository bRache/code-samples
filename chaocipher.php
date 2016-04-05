<?php

	/*
	*	This script implements the chaocipher developed by J.F. Byrne in 1918.
	*	The chaocipher version presented here uses the basic algorithm for encryption/decryption.
	*	The basic algorithm uses an all upper-case alphabet. A regex will remove unusable characters
	*	from the plaintext string and convert all letters to upper-case.
	*	This code is fully executable and should be used for demonstration purposes only.
	*/
	
	class chaocipher {
		
		/*
		*	Properties
		*/
		private $plaintext;
		private $ciphertext;
		private $plaintextRotor;
		private $ciphertextRotor;	
		private $plaintextRotor_length;
		private $ciphertextRotorLength;
		private $nadir;
		const ZENITH = 0;
		private $cipherKey;
		private $index;
		
		function __construct(){
			$this->initialize();			
			$this->plaintextRotorLength = strlen($this->plaintextRotor);
			$this->ciphertextRotorLength = strlen($this->ciphertextRotor);
			$this->nadir = (strlen($this->plaintextRotor) / 2);
		}

		/*
		*	This method sets the cipher system to its ready state.
		*	Arguments: None.
		*	Return: None.
		*/		
		private function initialize(){
			$this->plaintextRotor =  "PUXKNDLQRIBFOEAMHVSJCTYWGZ";
			$this->ciphertextRotor = "MYVSGJFWZCNXBLETDPUOKHQRAI";	
			$this->plaintext = "";
			$this->ciphertext = "";	
			$this->cipherKey = "";
			$this->index = 0;
		}

		/*
		*	This method permutes the plaintext rotor.
		*	Arguments: None.
		*	Return: None.
		*/			
		private function permutePlaintextRotor(){
			$substring1 = substr($this->plaintextRotor, $this->ZENITH, $this->index + 1);
			$this->plaintextRotor = str_replace($substring1, "" ,$this->plaintextRotor);
			$this->plaintextRotor = $this->plaintextRotor . $substring1;
			$swap = substr($this->plaintextRotor, 2, 1);
			$this->plaintextRotor = str_replace($swap, "", $this->plaintextRotor);
			$this->plaintextRotor = substr_replace($this->plaintextRotor, $swap, $this->nadir, 0);			
		}

		/*
		*	This method permutes the ciphertext rotor.
		*	Arguments: None.
		*	Return: None.
		*/			
		private function permuteCiphertextRotor(){
			$substring = substr($this->ciphertextRotor, $this->index, $this->ciphertextRotorLength);
			$this->ciphertextRotor = str_replace($substring, "", $this->ciphertextRotor);	
			$this->ciphertextRotor = $substring . $this->ciphertextRotor;	
			$swap = substr($this->ciphertextRotor, 1, 1);			
			$this->ciphertextRotor = str_replace($swap, "", $this->ciphertextRotor);
			$this->ciphertextRotor = substr_replace($this->ciphertextRotor, $swap, $this->nadir, 0);			
		}

		/*
		*	This method sets the cipher key in the system.
		*	Arguments: Key string.
		*	Return: None.
		*/	
		public function setKey($key) {
			$this->cipherKey = preg_replace('/[^A-Za-z]/','',$key);	//Remove special characters from the string.
			$this->cipherKey = strtoupper($this->cipherKey);					
			
			//Search for ciphertext / plaintext
			for($i = 0; $i < strlen($this->cipherKey); $i++){
				for($n = 0; $n < $this->plaintextRotorLength; $n++){
					if(substr($this->plaintextRotor, $n, 1) == substr($this->cipherKey, $i, 1)){	
						$this->index = $n;
					}
				}

				//Permute rotors
				$this->permutePlaintextRotor();
				$this->permuteCiphertextRotor();												
			}
		}

		/*
		*	This method returns the cipher key in the system.
		*	Arguments: None.
		*	Return: Key string.
		*/			
		public function getKey() {
			return $this->cipherKey;
		}

		/*
		*	This method sets the plaintext in the system.
		*	Arguments: Plaintext string.
		*	Return: None.
		*/			
		public function setPlaintext($plaintext){	
			$this->plaintext = preg_replace('/[^A-Za-z]/','',$plaintext);	//Remove special characters from the string.		
			$this->plaintext = strtoupper($this->plaintext);					
		}
		
		/*
		*	This method returns the plaintext from the system.
		*	Arguments: None.
		*	Return: Plaintext string.
		*/			
		public function getPlaintext(){	
			return $this->plaintext;			
		}

		/*
		*	This method sets the ciphertext in the system.
		*	Arguments: Plaintext string.
		*	Return: None.
		*/		
		public function set_ciphertext($ciphertext){
			$this->ciphertext = preg_replace('/[^A-Za-z]/','',$ciphertext);	//Remove special characters from the string.
			$this->ciphertext = strtoupper($this->ciphertext);					
		}	
		
		/*
		*	This method returns the ciphertext from the system.
		*	Arguments: None.
		*	Return: Ciphertext string.
		*/			
		public function getCiphertext(){	
			return $this->ciphertext;			
		}	

		/*
		*	This method encrypts a plaintext sent by the user.
		*	Arguments: Plaintext string, key string.
		*	Return: Ciphertext string.
		*/		
		public function encrypt($plaintext, $key){
			$this->initialize();		
			$this->setPlaintext($plaintext);
			$this->setKey($key);
			
			//Search for plaintext / ciphertext
			for($i = 0; $i < strlen($this->plaintext); $i++){
				for($n = 0; $n < 26; $n++){
					if(substr($this->plaintextRotor, $n, 1) == substr($this->plaintext, $i, 1)){
						$this->ciphertext = $this->ciphertext . substr($this->ciphertextRotor, $n, 1);	
						$this->index = $n;
					}
				}
					
				//Permute rotors
				$this->permutePlaintextRotor();
				$this->permuteCiphertextRotor();
			}		
			return $this->ciphertext;
		}

		/*
		*	This method decrypts a ciphertext sent by the user.
		*	Arguments: Ciphertext string, key string.
		*	Return: Plaintext string.
		*/			
		public function decrypt($ciphertext, $key) {	
			$this->initialize();			
			$this->set_ciphertext($ciphertext);
			$this->setKey($key);
			
			//Search for ciphertext / plaintext
			for($i = 0; $i < strlen($this->ciphertext); $i++){
				for($n = 0; $n < 26; $n++){
					if(substr($this->ciphertextRotor, $n, 1) == substr($this->ciphertext, $i, 1)){
						$this->plaintext = $this->plaintext . substr($this->plaintextRotor, $n, 1);	
						$this->index = $n;
					}
				}			
				
				//Permute rotors
				$this->permutePlaintextRotor();
				$this->permuteCiphertextRotor();				
			}
			return $this->plaintext;			
		}
	}//end class
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Create instance of class
	$cipher = new chaocipher;
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//Variables
	$keyMessage = "";
	$textMessage = "";
	$text = "";
	$textSet = false;
	$keySet = false;
	
	//Encrypt plaintext
	if(isset($_POST['encrypt'])){
				
		if($_POST['text']){
			$textSet = true;	
			$text = $_POST['text'];			
		} else {
			$textMessage = "Message not set.";
			$textSet = false;
		} 
		
		if($_POST['key']){
			$keySet = true;	
		}
		 else { 
		 	$keyMessage = "Key not set.";
			$keySet = false;			
		 }
		 
		 if($textSet == true && $keySet == true) {
			$textMessage = "";
			$keyMessage = "";		 
		 	$text="";
			$text = $cipher->encrypt($_POST['text'], $_POST['key']);				
		}
	}
	
	//Decrypt ciphertext
	if(isset($_POST['decrypt'])){
		
		if($_POST['text']){
			$textSet = true;
			$text = $_POST['text'];									
		} else {
			$textMessage = "Message not set.";
			$textSet = false;
		} 
		
		if($_POST['key']){
			$keySet = true;	
		}
		 else { 
		 	$keyMessage = "Key not set.";
			$keySet = false;			
		 }
		 
		 if($textSet == true && $keySet == true) {
			$textMessage = "";
			$keyMessage = "";		 
		 	$text="";
			$text = $cipher->decrypt($_POST['text'], $_POST['key']);				
		}
	}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>CHAOCIPHER</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta "keywords" content="chaocipher, cipher systems, cipher">
<meta "description" content="Enjoy this PHP demonstration of chaocipher!">
<meta "author" content="Brian Rache">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700' rel='stylesheet' type='text/css'>
<style>
	body {
		background-color: #FFFFFF;
		font-family: 'Roboto', sans-serif;
		font-size: 1em;
		font-weight: 400;
		margin: 0 auto;
		width: 100%;
	}
	
	header {
		background-color: #333;
		color: #3DB8B8;
		display: block;
		font-weight: 500;
		font-size: 2.25em;
		height: 7vh;
		margin: 0 auto;		
		padding: 3% 0 3% 0;
		width: 100%;
	}
	
	.headerContent {
		margin: 0 auto;
		width: 40%;
		max-width: 350px;	
	}	
	
	footer {
		background-color: #333;
		color: #3DB8B8;
		display: block;
		font-weight: 400;
		font-size: 1em;
		height: 40px;
		line-height: 1.5em;
		margin: 7% 0 0 0;		
		padding: 4% 0 4% 0;
		width: 100%;
	}
	
	.footerContent {
		margin: 0 auto;
		text-align: center;
		width: 40%;
		max-width: 350px;	
	}
	
	.content {
		display: block;
		margin: 0 auto;
		padding: 30px 0 0 0;
		width: 40%;
		max-width: 350px;	
	}
	
	form {
		display: block;	
	}
	
	label {
		display: block;
		font-size: 1.8em;
		margin: 0 0 3% 0;			
	}
	
	#key {
		border: thin solid #333;
		display: inline-block;
		font-family: 'Roboto', sans-serif;
		font-size: 1em;
		height: 30px;
		margin: 0 0 10% 0; 		
		width: 100%;
	}
	
	textarea {
		border: thin solid #333;
		display: block;
		font-family: 'Roboto', sans-serif;
		font-size: 1em;		
		height: 20%;
		width: 100%;	
	}
	
	#submit {
		background-color: #333;
		border:none;
		color: #3DB8B8;
		font-size: 1em;
		margin: 0 2% 0 2%;
		padding: 3% 2% 3% 2%;
		width: 40%;
	}
	
	#submit:hover {
		color: #F63;
	}
	
	.buttonHolder {
		display: block;
		margin: 10% 0 0 0;
		text-align: center;
		width: 100%;	
	}
	
	.message {
		color: #F63;
		font-size: .5em;
		float: right;
		margin: 3% 0 0 5%;
	}
	
	.link {
		display: block;
		margin: 25% 0 0 0; 
		text-align: center;		
		width: 100%;		
	}
	
	.link a {
		color: #999;
		text-decoration: none;	
	}
	
	@media only screen and (max-width: 54.5em){
		body {
			font-size: 1em;
		}
		
		header {
			font-size: 2.25em;
			padding: 5% 0 5% 0;			
			width: 100%;
		}
		
		.headerContent {
			width: 60%;
		}	
		
		footer {
			font-size: 1em;
			padding: 6% 0 6% 0;			
		}
		
		.footerContent {
			width: 60%;
		}
		
		.content {
			width: 60%;
		}
		
		label {
			font-size: 1.8em;
		}
		
		#key {
			font-size: 1em;
			width: 97%;		
		}
		
		textarea {
			font-size: 1em;		
		}
		
		#submit {
			font-size: 1em;
		}
		
		.message {
			font-size: .5em;
		}
		
		.link {
			margin: 20% 0 20% 0; 	
		}		
	}
	
	@media only screen and (max-width:36.25em){
		body {
			font-size: 1em;
		}
		
		header {
			font-size: 2.25em;
			padding: 10% 0 10% 0;
			width: 100%;
		}
		
		.headerContent {
			width: 90%;
		}	
		
		footer {
			font-size: 1em;
			padding: 10% 0 10% 0;			
		}
		
		.footerContent {
			width: 90%;
		}
		
		.content {
			width: 90%;
		}
		
		label {
			font-size: 1.8em;
		}
		
		#key {
			font-size: 1em;
			width: 97%;			
		}
		
		textarea {
			font-size: 1em;		
		}
		
		#submit {
			font-size: 1em;
		}
		
		.message {
			font-size: .45em;
		}	
		
		.link {
			margin: 20% 0 20% 0; 	
		}		
	}
	
</style>
</head>
<body>
	<header>
    	<div class="headerContent">
    		CHAOCIPHER
        </div><!-- end headerContent -->
    </header>
    <div class="content">
		<form method="post" action="chaocipher.php">
        	<label>Key<div class="message"><?php echo $keyMessage ?></div></label>
        	<input type="password" name="key" alt="key" id="key"></input>   
    		<label>Message<div class="message"><?php echo $textMessage ?></div></label>
        	<textarea name="text" rows="10" cols="64" placeholder="Enter a message to encrypt or decrypt."><?php echo $text ?></textarea>    
        	<div class="buttonHolder">
        		<input type="submit" name="encrypt" alt="encrypt" value="Encrypt" id="submit"></input>
        		<input type="submit" name="decrypt" alt="decrypt" value="Decrypt" id="submit"></input>
        	</div>
        	<div class="link"><a href="http://www.brianrache.com/chaocipher/aboutChaocipher.html">Learn more about Chaocipher.</a></div><!-- end link -->     
		</form>
    </div><!-- end content -->
	<footer>
    	<div class="footerContent">
        For demonstration purposes only.<br />
    	version 1.0
        </div>
    </footer>
</body>
</html>
