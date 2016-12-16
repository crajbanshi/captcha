<?php
namespace PW;

if(! isset($_SESSION)){
	session_id();
	session_start();
}
/**
 *
 * @throws
 *
 * @author Chanchal Rajbanshi
 * @version 0.1.1
 *         
 */


class PwCaptcha
{

    public $fonts = array(
        'Antykwa' => array(
            'spacing' => - 3,
            'minSize' => 27,
            'maxSize' => 30,
            'font' => 'AntykwaBold.ttf'
        ),
        'Candice' => array(
            'spacing' => - 1.5,
            'minSize' => 28,
            'maxSize' => 31,
            'font' => 'Candice.ttf'
        ),
        'calibri' => array(
            'spacing' => - 2,
            'minSize' => 24,
            'maxSize' => 30,
            'font' => 'calibri.ttf'
        ),
        'Duality' => array(
            'spacing' => - 2,
            'minSize' => 30,
            'maxSize' => 38,
            'font' => 'Duality.ttf'
        ),
        'Jura' => array(
            'spacing' => - 2,
            'minSize' => 28,
            'maxSize' => 32,
            'font' => 'Jura.ttf'
        ),
        'StayPuft' => array(
            'spacing' => - 1.5,
            'minSize' => 28,
            'maxSize' => 32,
            'font' => 'StayPuft.ttf'
        ),        
        'Times' => array(
            'spacing' => - 2,
            'minSize' => 28,
            'maxSize' => 34,
            'font' => 'TimesNewRomanBold.ttf'
        ),
        'VeraSans' => array(
            'spacing' => - 1,
            'minSize' => 20,
            'maxSize' => 28,
            'font' => 'VeraSansBold.ttf'
        )
    );

    /**
     *
     * @var image
     */
    protected $image;

    /**
     *
     * @var string
     */
    protected $fontfile;

    /**
     *
     * @var int
     */
    protected $minLength = 1;

    /**
     *
     * @var int
     */
    protected $maxLength = 9;

    /**
     *
     * @var string
     */
    protected $text;

    /**
     *
     * @var int
     */
    protected $numberOfLines = 6;

    /**
     *
     * @var boolean
     */
    protected $isColourText = FALSE;

    /**
     * construntor
     */
    public function loadFontFiles()
    {
        $fontcfg = $this->fonts[array_rand($this->fonts)];
        
        $this->fontfile = __DIR__ . '/fonts/' . $fontcfg['font'];
    }

    /**
     * set captcha text is multi colored
     * default param value true
     * 
     * @param string $color            
     */
    public function setIsMultiColourText($isColor = true)
    {
        $this->isColourText = $isColor;
    }

    /**
     * get captcha text is multi colored
     *
     * @param string $color            
     */
    public function isMultiColourText()
    {
        return $this->isColourText;
    }

    /**
     * set min length of text
     *
     * @param number $min            
     * @throws \InvalidArgumentException
     */
    public function setMinLength($min = 4)
    {
        if (! is_int($min) && $min > 1) {
            throw new \InvalidArgumentException('param must be an positive integer');
        }
        
        $this->minLength = $min;
    }

    /**
     * set max length of text
     *
     * @param number $max            
     * @throws \InvalidArgumentException
     */
    public function setMaxLength($max = 8)
    {
        if (! is_int($max) && $max > 2) {
            throw new \InvalidArgumentException('param must be an integer');
        }
        $this->maxLength = $max;
    }

    /**
     * Generate Captcha Image
     */
    protected function generateCaptcha()
    {
        $text = $this->randomText();
        $this->storeCaptchaText($text);
        $this->image = imagecreate(25 * $this->maxLength, 50);
        $this->loadFontFiles();
        imagecolorallocate($this->image, 255, 255, 255);
        $color = imagecolorallocate($this->image, 0, 0, 0);
        
        $this->drawLines();
        
        $size = 20;
        $angle = 0;
        $x = 4;
        $y = 40;
        $fontfile = $this->fontfile;
        //
        $textarr = str_split($text);
        foreach ($textarr as $val) {
            $angle = rand(- 20, 20);
            
            if ($this->isMultiColourText()) {
                $color = $this->getTextColor();
            }
            
            $coords = imagettftext($this->image, $size, $angle, $x, $y, $color, $fontfile, $val);
            $x += ($coords[2] - $x) + 4;
        }
    }

    /**
     * generate multicolor captcha text.
     * 
     * @param string $invers
     * @return number
     */
    protected function getTextColor($invers = FALSE)
    {
        $c = array(
            rand(0, 255),
            rand(0, 255),
            rand(0, 255)
        );
        
        if (($c[0] + $c[1] + $c[2]) > 500) {
            return $this->getTextColor();
        }
        
        return imagecolorallocate($this->image, $c[0], $c[1], $c[2]);
    }

    /**
     * draw random lines in captcha Image
     */
    protected function drawLines()
    {
        for ($i = 0; $i < $this->numberOfLines; $i ++) {
            $color = imagecolorallocate($this->image, rand(100, 250), rand(100, 250), rand(100, 250));
            imageline($this->image, rand(0, 50), rand(0, 50), rand(100, 25 * $this->maxLength), rand(0, 50), $color);
        }
    }

    /**
     * Generate Random Text
     *
     * @return string
     */
    protected function randomText()
    {
        $text = '';
        if ($this->minLength <= 1) {
            $captchaLength = $this->maxLength;
        } else {
            $captchaLength = rand($this->minLength, $this->maxLength);
        }
        
        for ($i = 0; $i < $captchaLength; $i ++) {
            $block = rand(0, 3);
            if ($block == 0) {
                $val = rand(48, 57);
            } elseif ($block == 1) {
                $val = rand(65, 90);
            } else {
                $val = rand(97, 122);
            }
            $text .= chr($val);
        }
        
        return $text;
    }

    /**
     * output raw image
     *
     * @return \Sunflow\Captcha\image
     */
    private function getCaptcha()
    {
        if (! $this->image) {
            $this->generateCaptcha();
        }
        
        return $this->image;
    }

    /**
     * output image
     */
    public function writeCaptchaImage()
    {
        header("Content-type: image/png");
        imagepng($this->getCaptcha());
    }

    /**
     *
     * @param String $text            
     * @throws \Exception
     */
    private function storeCaptchaText($text)
    {
        if (! is_string($text)) {
            throw new \Exception('String required as parameter in ' . __METHOD__);
        }                
        $_SESSION['captchatext'] = $text;       
    }

    /**
     *
     * @param string $text            
     * @return boolean
     */
    public function validateLastCaptcha($text)
    {    	
        if ($_SESSION['captchatext'] === $text) {
            return TRUE;
        }
    }

    /**
     * set no of backgroung lines draw in captcha image
     * 
     * @param int $nooflines
     */
    public function setNumberOfLines($nooflines)
    {
        if (! is_int($nooflines)) {}
        $this->numberOfLines = $nooflines;
    }

    /**
     * validate captcha text
     * 
     * @param string $text
     * @return boolean
     */
    public static function isValidCaptcha($text)
    {
        if ($_SESSION['captchatext'] === $text) {
            return TRUE;
        }
    }
    
    /**
     * output
     */
    public function render()
    {
    	$uri = "captcha.php";    	
    	 
    	$file = 'th.png';
    	imagepng($this->getCaptcha(), $file);
    	if ($fp = fopen($file, "rb", 0)) {
    		$gambr = fread($fp, filesize($file));
    		fclose($fp);
    		$base64 = chunk_split(base64_encode($gambr));
    
    		// output only base 64 image source
    		if (array_key_exists('recaptcha', $_REQUEST)) {
    			ob_clean();
    			if (! isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] != explode('?',$uri)[0] ) {
    				throw new BadRequestHttpException('Page Not Availabe');
    			}
    
    			die('data:image/jpeg/png/gif;base64,' . $base64);
    		}
    
//     		style="background: url(\'data:image/png;base64,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\') no-repeat;
//     					background-size: 25px 25px; height: 25px;"
    		// output full html source
    		$html = '<captcha><img  src="data:image/jpeg/png/gif;base64,' . $base64 . '" id="captcha">
                <div class="fa fa-refresh" aria-hidden="true"
                onclick="recaptcha()" id="refresh" style="display:inline">
    				<img src="data:image/png;base64,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" height="25px">
    				</div><script>function recaptcha(){    
                		document.getElementById("captcha").src = "'. $uri . '?recaptcha=" + Math.random();	
}                		</script></captcha>';
    		echo $html;
    	}
    }

   
}
