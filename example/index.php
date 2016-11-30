<?php 

use PW\PwCaptcha;
require_once 'pwcaptcha-master/src/PW/PwCaptcha.php';

$captcha = new PwCaptcha();

if(isset($_POST['captcha'])){
	
	/**
	 * validate captcha text, get return true if captcha text is valid
	 * $captcha->isValidCaptcha($text);
	 */
	
	//validate captcha and show result
	die( ($captcha->isValidCaptcha($_POST['captcha']))?'Valid Captcha':'Invalid Captcha');
	
}

// set min text length 
$captcha->setMinLength(2);

// set max text length
$captcha->setMaxLength(5);

// set no of background lines
$captcha->setNumberOfLines(20);

// set true for multicolor text output
$captcha->setIsMultiColourText(true);

?><!DOCTYPE html>
<html>
<body>

<form action="" method="post">
  First name:<br>
  <input type="text" name="firstname" placeholder="Mickey">
  <br>
  Last name:<br>
  <input type="text" name="lastname" placeholder="Mouse">
  <br><?php 
  $captcha->render();
  ?>
  Captcha:<br>
  
  <input type="text" name="captcha" placeholder="captcha">
  <br>
  <br>
  <input type="submit" value="Submit">
</form> 


</body>
</html>
