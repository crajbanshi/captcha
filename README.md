# pwcaptcha

Build texture captcha is very easy using pw captcha.

include library file into your page. Create an object of the PwCaptcha class.

require_once 'pwcaptcha-master/src/PW/PwCaptcha.php';
$captcha = new PwCaptcha();


To get output of captcha image use

$captcha->render();


#configre methods

setIsMultiColourText( boolean )

set minimum character number 
<code>setMinLength()</code>

set maximum character length
setMaxLength() 

set number og random straigth lines in background

setNumberOfLines()

Min 1 and max is 100



