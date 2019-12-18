<?php

trait CaptchaTrait
{

    function createCaptcha($config = [])
    {
        $defaults = array(
            'word'       => null,
            'imgWidth'   => '240',
            'imgHeight'  => '60',
            'imgPath'    => '/media/captcha/',
            'fontType'   => 'Cabin-Bold-webfont.ttf',
            'fontSize'   => 26,
            'expiration' => 7200,
            'pool'       => '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'maxLetters' => 6,
        );

        foreach ($defaults as $key => & $val) {
            if (isset($config[$key])) {
                $val = $config[$key];
            }
        }

        $defaults['fontPath'] = base_path() . '/resources/assets/fonts/' . $defaults['fontType'];

        if ( ! extension_loaded('gd')) {
            return false;
        }

        // -----------------------------------
        // Remove old images
        // -----------------------------------

        list($uSec, $sec) = explode(' ', microtime());
        $now = ((float) $uSec + (float) $sec);

        if ($this->s3) {
            if ($files = Storage::disk('s3')->files($defaults['imgPath'])) {
                foreach ($files as $file) {
                    $items = pathinfo($file);
                    if (($items['filename'] + $defaults['expiration']) < $now) {
                        Storage::disk('s3')->delete($file);
                    }
                }
            }
        } else {
            $currentDir = @opendir($defaults['imgPath']);
            while ($filename = @readdir($currentDir)) {
                if ('.' != $filename && '..' != $filename) {
                    $name = str_replace('.jpg', '', $filename);

                    if (($name + $defaults['expiration']) < $now) {
                        @unlink($defaults['imgPath'] . $filename);
                    }
                }
            }
            @closedir($currentDir);
        }

        // -----------------------------------
        // Do we have a "word" yet?
        // -----------------------------------

        if (empty($defaults['word'])) {
            $str = '';
            for ($i = 0; $i < $defaults['maxLetters']; $i++) {
                $str .= substr($defaults['pool'], mt_rand(0, strlen($defaults['pool']) - 1), 1);
            }

            $defaults['word'] = $str;
        }

        // -----------------------------------
        // Determine angle && position
        // -----------------------------------

        $length = strlen($defaults['word']);
        $angle = ($length >= 6) ? rand(-($length - 6), ($length - 6)) : 0;
        $xAxis = rand(6, (360 / $length) - 16);
        $yAxis = ($angle >= 0) ? rand($defaults['imgHeight'], $defaults['imgWidth']) : rand(6, $defaults['imgHeight']);

        // -----------------------------------
        // Create image
        // -----------------------------------

        // PHP.net recommends imagecreatetruecolor(), but it isn't always available
        if (function_exists('imagecreatetruecolor')) {
            $img = imagecreatetruecolor($defaults['imgWidth'], $defaults['imgHeight']);
        } else {
            $img = imagecreate($defaults['imgWidth'], $defaults['imgHeight']);
        }

        // -----------------------------------
        //  Assign colors
        // -----------------------------------

        $bgColor = imagecolorallocate($img, 255, 255, 255);
        $borderColor = imagecolorallocate($img, 153, 102, 102);
        $textColor = imagecolorallocate($img, 204, 153, 153);
        $gridColor = imagecolorallocate($img, 255, 182, 182);
        $shadowColor = imagecolorallocate($img, 255, 240, 240);

        // -----------------------------------
        //  Create the rectangle
        // -----------------------------------

        ImageFilledRectangle($img, 0, 0, $defaults['imgWidth'], $defaults['imgHeight'], $bgColor);

        // -----------------------------------
        //  Create the spiral pattern
        // -----------------------------------

        $theta = 1;
        $thetaC = 7;
        $radius = 16;
        $circles = 20;
        $points = 32;

        for ($i = 0; $i < ($circles * $points) - 1; $i++) {
            $theta = $theta + $thetaC;
            $rad = $radius * ($i / $points);
            $x = ($rad * cos($theta)) + $xAxis;
            $y = ($rad * sin($theta)) + $yAxis;
            $theta = $theta + $thetaC;
            $rad1 = $radius * (($i + 1) / $points);
            $x1 = ($rad1 * cos($theta)) + $xAxis;
            $y1 = ($rad1 * sin($theta)) + $yAxis;
            imageline($img, $x, $y, $x1, $y1, $gridColor);
            $theta = $theta - $thetaC;
        }

        // -----------------------------------
        //  Write the text
        // -----------------------------------

        $defaults['useFont'] = ( ! empty($defaults['fontPath']) && file_exists($defaults['fontPath']) && function_exists('imagettftext'));

        if (empty($defaults['useFont'])) {
            $x = rand(0, $defaults['imgWidth'] / ($length / 3));
            $y = 0;
        } else {
            $x = rand(0, $defaults['imgWidth'] / ($length / 1.5));
            $y = $defaults['fontSize'] + 2;
        }

        for ($i = 0; $i < strlen($defaults['word']); $i++) {
            if (empty($defaults['useFont'])) {
                $y = rand(0, $defaults['imgHeight'] / 2);
                imagestring($img, $defaults['fontSize'], $x, $y, substr($defaults['word'], $i, 1), $textColor);
                $x += ($defaults['fontSize'] * 2);
            } else {
                $y = rand($defaults['imgHeight'] / 2, $defaults['imgHeight'] - 3);
                imagettftext($img, $defaults['fontSize'], $angle, $x, $y, $textColor, $defaults['fontPath'], substr($defaults['word'], $i, 1));
                $x += $defaults['fontSize'];
            }
        }

        // -----------------------------------
        //  Create the border
        // -----------------------------------

        imagerectangle($img, 0, 0, $defaults['imgWidth'] - 1, $defaults['imgHeight'] - 1, $borderColor);

        // -----------------------------------
        //  Generate the image
        // -----------------------------------

        $imgName = $now . '.jpg';

        if ($this->s3) {
            ob_start();
            ImageJPEG($img);
            $image = ob_get_clean();
            Storage::disk('s3')->put($defaults['imgPath'].$imgName, $image);
        } else {
            ImageJPEG($img, public_path().$defaults['imgPath'].$imgName);
        }

        ImageDestroy($img);

        return [
            'word'      => $defaults['word'],
            'imageName' => $imgName,
        ];
    }

}
