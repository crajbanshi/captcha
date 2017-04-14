# pwcaptcha

Build texture captcha is very easy using pw captcha.

include library file into your page. Create an object of the PwCaptcha class.

require_once 'src/PwCaptcha.php';
$captcha = new PwCaptcha();


To get output of captcha image use

$captcha->render();


#configre methods

$captcha->setIsMultiColourText( boolean )

set minimum character number 
<code>$captcha->setMinLength()</code>

set maximum character length
$captcha->setMaxLength() 

set number og random straigth lines in background

$captcha->setNumberOfLines()

Min 1 and max is 100



