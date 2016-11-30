<?php


use PW\PwCaptcha;
require_once 'pwcaptcha-master/src/PW/PwCaptcha.php';

$captcha = new PwCaptcha();

// set min text length 
$captcha->setMinLength(2);

// set max text length
$captcha->setMaxLength(5);

// set no of background lines
$captcha->setNumberOfLines(20);

// set true for multicolor text output
$captcha->setIsMultiColourText(true);

$captcha->writeCaptchaImage();
