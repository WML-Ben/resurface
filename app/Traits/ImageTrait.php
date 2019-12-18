<?php

/** 2017-08-01 */

trait ImageTrait
{
    // image vars
    private $mainImage = '';
    private $watermarkImage;
    private $tempImage;
    private $jpegQuality = 75;
    private $backgroundColor = '#FFFFFF';
    private $watermarkMethod;

    // other
    private $filename = '';

    // watermark stuff, opacity
    private $watermarkTransparency = 50;

    // reported errors
    public $errors = false;
    private $errorMsg = array();

    // image info
    public $width = 0;
    public $height = 0;


    public function ajaxUploadImage($fieldName, $uploadDir, $params = [])
    {
        if (empty($_FILES[$fieldName]['name']) || empty($_FILES[$fieldName]['tmp_name'])) {
            return false;
        }

        $fileTypes = ! empty($params['fileTypes']) ? $params['fileTypes'] : [];
        $imagePrefix = ! empty($params['imagePrefix']) ? $params['imagePrefix'] : '';
        $resize = ! empty($params['resize']) ? $params['resize'] : false;
        $w = ! empty($params['w']) ? $params['w'] : 250;
        $h = ! empty($params['h']) ? $params['h'] : 250;

        $tempFile = $_FILES[$fieldName]['tmp_name'];
        $origName = $_FILES[$fieldName]['name'];
        $extension = pathinfo($origName, PATHINFO_EXTENSION);

        $patterns = ['/[^a-zA-z0-9_]{1}[^a-zA-Z0-9_\-\.\s]*$/', '/\s+/'];
        $replacements = ['', '-'];
        $cleanName = strtolower(preg_replace($patterns , $replacements, $origName));
        $newName = $imagePrefix . $cleanName;
        $destinationName = $uploadDir . $newName;

        if (!empty($fileTypes) && !in_array(strtolower($extension), $fileTypes)) {
            return [
                'success'  => false,
                'message'  => 'Invalid image file type.',
                'filename' => null,
            ];
        }
        if (!move_uploaded_file($tempFile, $destinationName)) {
            return [
                'success'  => false,
                'message'  => 'Unable to move uploaded image file.',
                'filename' => null,
            ];
        }

        if ($resize) {
            if ($this->resizeImage($destinationName, $w, $h)) {
                $result = [
                    'success'  => true,
                    'message'  => '',
                    'filename' => $newName,
                ];
            } else {
                $result = [
                    'success'  => false,
                    'message'  => 'Unable to resize image.',
                    'filename' => null,
                ];
            }
        } else {
            $result = [
                'success'  => true,
                'message'  => '',
                'filename' => $newName,
            ];
        }

        return $result;
    }

    public function resizeImage($imageName, $w = 250, $h = 250)
    {
        list($width, $height) = getimagesize($imageName);  // $imageName includes full path

        if ($width > $w && $height > $h) {
            // Both dimensions are bigger than limits:
            // Shrink proportionally so smaller size becomes limit. The other remains bigger than limit
            $rw = $w / $width;
            $rh = $h / $height;
            $r = ($rw > $rh) ? $rw : $rh;
            $newW = floor($width * $r);
            $newH = floor($height * $r);
            $image = $this->load($imageName)->resize($newW, $newH);
        } else {
            //  At least one of the dimensions is smaller than limit:
            //  Stretch proportionally so smaller size becomes limit. The other becomes bigger than limit
            if ($width < $w && $height < $h) {  // Both dimensions are smaller than limits.
                $rw = $w / $width;
                $rh = $h / $height;
                $r = ($rw > $rh) ? $rw : $rh;
                $newW = floor($width * $r);
                $newH = floor($height * $r);
            } else if ($width < $w) {               // Only width is smaller than limit
                $r = $w / $width;
                $newW = floor($width * $r);
                $newH = floor($height * $r);
            } else if ($height < $h) {              // Only height is smaller than limit
                $r = $h / $height;
                $newW = floor($width * $r);
                $newH = floor($height * $r);
            } else {                                // same height and width
                $newW = $width;
                $newH = $height;
            }
            $image = $this->load($imageName)->stretch($newW, $newH);
        }

        if ($width != $w && $height != $h) {
            // resize_crop image (centered) to fit container:  // to take the left-top corner instead: crop(1, 1, $w, $h)
            $image->resizeCrop($w, $h)->save($imageName, true);
        }

        return file_exists($imageName);
    }
    
    public function isPngValidButBlank($filename)
    {
        $img = imagecreatefrompng($filename);

        if (!$img) {
            return false;
        }

        $width = imagesx($img);
        $height = imagesy($img);

        if (!$width || !$height) {
            return false;
        }

        $firstcolor = imagecolorat($img, 0, 0);
        
        for ($i = 0; $i < $width; $i++) {
            for ($j = 0; $j < $height; $j++) {
                $color = imagecolorat($img, $i, $j);
                if ($color != $firstcolor) {
                    return false;
                }
            }
        }
        return true;
    }

    /************************************  Image_moo methods  ********************************/

    //----------------------------------------------------------------------------------------------------------
    // load a resource
    //----------------------------------------------------------------------------------------------------------
    private function clearErrors()
    {
        $this->errorMsg = array();
    }

    //----------------------------------------------------------------------------------------------------------
    // Set an error message
    //----------------------------------------------------------------------------------------------------------
    private function setError($msg)
    {
        $this->errors = true;
        $this->errorMsg[] = $msg;
    }

    //----------------------------------------------------------------------------------------------------------
    // returns the errors formatted as needed, same as CI doed
    //----------------------------------------------------------------------------------------------------------
    public function displayErrors($open = '<p>', $close = '</p>')
    {
        $str = '';
        foreach ($this->errorMsg as $val) {
            $str .= $open . $val . $close;
        }

        return $str;
    }

    //----------------------------------------------------------------------------------------------------------
    // verification util to see if you can use image_moo
    //----------------------------------------------------------------------------------------------------------
    public function checkGD()
    {
        if ( ! extension_loaded('gd')) {
            if ( ! dl('gd.so')) {
                $this->setError('GD library does not appear to be loaded');

                return false;
            }
        }
        if (function_exists('gd_info')) {
            $gdArray = @gd_info();
            $versionTxt = preg_replace('[[:alpha:][:space:]()]+', '', $gdArray['GD Version']);
            $versionParts = explode('.', $versionTxt);
            if ($versionParts[0] == '2') {
                return true;
            } else {
                $this->setError('Requires GD2, this is reported as ' . $versionTxt);

                return false;
            }
        } else {
            $this->setError('Could not verify GD version');

            return false;
        }
    }

    //----------------------------------------------------------------------------------------------------------
    // checks that we have an image loaded
    //----------------------------------------------------------------------------------------------------------
    private function checkImage()
    {
        if ( ! is_resource($this->mainImage)) {
            $this->setError('No main image loaded!');

            return false;
        } else {
            return true;
        }
    }

    //----------------------------------------------------------------------------------------------------------
    // Saves the temp image as a dynamic image
    // e.g. direct output to the browser
    //----------------------------------------------------------------------------------------------------------
    function saveDynamic($filename = '')
    {
        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            return $this;
        }

        // if no operations, copy it for temp save
        $this->copyToTempIfNeeded();

        // ok, lets go!
        if ($filename == '') {
            $filename = rand(1000, 999999) . '.jpg';
        }
        // send as jpeg
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // invalid file type?!
        if ( ! in_array($ext, ['gif', 'jpg', 'jpeg', 'png'])) {
            $this->setError('Do no know what ' . $ext . ' file type is in filename ' . $filename);

            return $this;
        }

        header("Content-disposition: filename=$filename;");
        header('Content-transfer-Encoding: binary');
        header('Last-modified: ' . gmdate('D, d M Y H:i:s'));
        switch ($ext) {
            case 'gif'  :
                header('Content-type: image/gif');
                imagegif($this->tempImage);
                break;
            case 'jpg' :
            case 'jpeg' :
                header('Content-type: image/jpeg');
                imagejpeg($this->tempImage, null, $this->jpegQuality);
                break;
            case 'png' :
                header('Content-type: image/png');
                imagepng($this->tempImage);
                break;
        }

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // Saves the temp image as the filename specified,
    // overwrite = true of false
    //----------------------------------------------------------------------------------------------------------
    function savePa($params = [])
    {
        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            return $this;
        }
        $prepend = (isset($params['prepend'])) ? $params['prepend'] : '';
        $append = (isset($params['append'])) ? $params['append'] : '';
        $overwrite = (isset($params['overwrite'])) ? $params['overwrite'] : false;

        // get current file parts
        $parts = pathinfo($this->filename);

        // save
        $this->save($parts['dirname'] . '/' . $prepend . $parts['filename'] . $append . '.' . $parts['extension'], $overwrite);

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // Saves the temp image as the filename specified,
    // overwrite = true of false
    //----------------------------------------------------------------------------------------------------------
    function save($filename, $overwrite = false)
    {
        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            return $this;
        }

        // if no operations, copy it for temp save
        $this->copyToTempIfNeeded();

        // if not overwrite, check if it already exists
        if ( ! $overwrite && file_exists($filename)) {
            $this->setError('File exists, overwrite is FALSE, could not save over file ' . $filename);

            return $this;
        }

        // find out the type of file to save
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // invalid file type?!
        if ( ! in_array($ext, ['gif', 'jpg', 'jpeg', 'png'])) {
            $this->setError('Do no know what ' . $ext . ' file type is in filename ' . $filename);
        } else {
            switch ($ext) {
                case 'gif'  :
                    imagegif($this->tempImage, $filename);
                    break;
                case 'jpg' :
                case 'jpeg' :
                    imagejpeg($this->tempImage, $filename, $this->jpegQuality);
                    break;
                case 'png' :
                    imagepng($this->tempImage, $filename);
                    break;
            }
        }

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // private function to load a resource
    //----------------------------------------------------------------------------------------------------------
    private function loadImage($filename)
    {
        // check the request file can be located
        if ( ! file_exists($filename)) {
            $this->setError('Could not locate file ' . $filename);

            return false;
        }

        // get image info about this file
        $imageInfo = getimagesize($filename);

        // load file depending on mime type
        switch ($imageInfo['mime']) {
            case 'image/gif'  :
                $result = imagecreatefromgif($filename);
                break;
            case 'image/jpeg' :
                $result = imagecreatefromjpeg($filename);
                break;
            case 'image/png' :
                $result = imagecreatefrompng($filename);
                break;
            default:
                $this->setError('Unable to load ' . $filename . '. File type ' . $imageInfo['mime'] . 'not recognized');
                $result = false;
                break;
        }

        return $result;
    }

    //----------------------------------------------------------------------------------------------------------
    // Load an image, public function
    //----------------------------------------------------------------------------------------------------------
    public function load($filename)
    {
        // new image, reset error messages
        $this->clearErrors();

        // remove temporary image stored
        $this->clearTemp();

        // save filename
        $this->filename = $filename;

        // reset width and height
        $this->width = 0;
        $this->height = 0;

        // load it
        $this->mainImage = $this->loadImage($filename);

        // no error, then get the dimensions set
        if ($this->mainImage <> false) {
            $this->width = imageSX($this->mainImage);
            $this->height = imageSY($this->mainImage);
        }

        // return the object
        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // Load an image, public function
    //----------------------------------------------------------------------------------------------------------
    public function load_watermark($filename, $transparentX = null, $transparentY = null)
    {
        if (is_resource($this->watermarkImage)) {
            imagedestroy($this->watermarkImage);
        }
        $this->watermarkImage = $this->loadImage($filename);

        if (is_resource($this->watermarkImage)) {
            $this->watermarkMethod = 1;
            if (($transparentX <> null) AND ($transparentY <> null)) {
                // get the top left corner color allocation
                $tpColor = imagecolorat($this->watermarkImage, $transparentX, $transparentY);

                // set this as the transparent color
                imagecolortransparent($this->watermarkImage, $tpColor);

                // $set diff method
                $this->watermarkMethod = 2;
            }
        }

        // return this object
        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // Sets the quality that jpeg will be saved at
    //----------------------------------------------------------------------------------------------------------
    public function set_watermarkTransparency($transparency = 50)
    {
        $this->watermarkTransparency = $transparency;

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // Sets teh background color to use on rotation and padding for resize
    //----------------------------------------------------------------------------------------------------------
    public function set_backgroundColor($color = '#FFFFFF')
    {
        $this->backgroundColor = $this->htmlToRgb($color);

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // Sets the quality that jpeg will be saved at
    //----------------------------------------------------------------------------------------------------------
    public function set_jpegQuality($quality = 75)
    {
        $this->jpegQuality = $quality;

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // If temp image is empty, e.g. not resized or done anything then just copy main image
    //----------------------------------------------------------------------------------------------------------
    private function copyToTempIfNeeded()
    {
        if ( ! is_resource($this->tempImage)) {
            // create a temp based on new dimensions
            $this->tempImage = imagecreatetruecolor($this->width, $this->height);

            // check it
            if ( ! is_resource($this->tempImage)) {
                $this->setError('Unable to create temp image sized ' . $this->width . ' x ' . $this->height);

                return false;
            }

            // copy image to temp workspace
            imagecopy($this->tempImage, $this->mainImage, 0, 0, 0, 0, $this->width, $this->height);
        }
    }

    //----------------------------------------------------------------------------------------------------------
    // clear everything!
    //----------------------------------------------------------------------------------------------------------
    public function clear()
    {
        if (is_resource($this->mainImage)) {
            imagedestroy($this->mainImage);
        }
        if (is_resource($this->watermarkImage)) {
            imagedestroy($this->watermarkImage);
        }
        if (is_resource($this->tempImage)) {
            imagedestroy($this->tempImage);
        }

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // you may want to revert back to teh original image to work on, e.g. watermark, this clears temp
    //----------------------------------------------------------------------------------------------------------
    public function clearTemp()
    {
        if (is_resource($this->tempImage)) {
            imagedestroy($this->tempImage);
        }

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // take main image and resize to tempimage using EXACT boundaries mw,mh (max width and max height)
    // this is proportional and crops the image centrally to fit
    //----------------------------------------------------------------------------------------------------------
    public function resizeCrop($mW, $mH)
    {
        if ( ! $this->checkImage()) {
            return $this;
        }

        // clear temp image
        $this->clearTemp();

        // create a temp based on new dimensions
        $this->tempImage = imagecreatetruecolor($mW, $mH);

        // check it
        if ( ! is_resource($this->tempImage)) {
            $this->setError('Unable to create temp image sized ' . $mW . ' x ' . $mH);

            return $this;
        }

        // work out best positions for copy
        $wx = $this->width / $mW;
        $wy = $this->height / $mH;
        if ($wx >= $wy) {
            // use full height
            $sy = 0;
            $sy2 = $this->height;

            $calcW = $mW * $wy;
            $sx = ($this->width - $calcW) / 2;
            $sx2 = $calcW;
        } else {
            // use full width
            $sx = 0;
            $sx2 = $this->width;

            $calcH = $mH * $wx;
            $sy = ($this->height - $calcH) / 2;
            $sy2 = $calcH;
        }

        // copy section
        imagecopyresampled($this->tempImage, $this->mainImage, 0, 0, $sx, $sy, $mW, $mH, $sx2, $sy2);

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // take main image and resize to tempimage using boundaries mw,mh (max width or max height)
    // this is proportional, pad to true will set it in the middle of area size
    //----------------------------------------------------------------------------------------------------------
    public function resize($mW, $mH, $pad = false)
    {
        if ( ! $this->checkImage()) {
            return $this;
        }

        // calc new dimensions
        if ($this->width > $mW || $this->height > $mH) {
            if (($this->width / $this->height) > ($mW / $mH)) {
                $tnW = $mW;
                $tnH = $tnW * $this->height / $this->width;
            } else {
                $tnH = $mH;
                $tnW = $tnH * $this->width / $this->height;
            }
        } else {
            $tnW = $this->width;
            $tnH = $this->height;
        }
        // clear temp image
        $this->clearTemp();

        // create a temp based on new dimensions
        if ($pad) {
            $tX = $mW;
            $tY = $mH;
            $pX = ($mW - $tnW) / 2;
            $pY = ($mH - $tnH) / 2;
        } else {
            $tX = $tnW;
            $tY = $tnH;
            $pX = 0;
            $pY = 0;
        }
        $this->tempImage = imagecreatetruecolor($tX, $tY);

        // check it
        if ( ! is_resource($this->tempImage)) {
            $this->setError('Unable to create temp image sized ' . $tX . ' x ' . $tY);

            return $this;
        }

        // if padding, fill background
        if ($pad) {
            $col = $this->htmlToRgb($this->backgroundColor);
            $bg = imagecolorallocate($this->tempImage, $col[0], $col[1], $col[2]);
            imagefilledrectangle($this->tempImage, 0, 0, $tX, $tY, $bg);
        }

        // copy resized
        imagecopyresampled($this->tempImage, $this->mainImage, $pX, $pY, 0, 0, $tnW, $tnH, $this->width, $this->height);

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // take main image and resize to tempimage using boundaries mw,mh (max width or max height)
    // does not retain proportions
    //----------------------------------------------------------------------------------------------------------
    public function stretch($mW, $mH)
    {
        if ( ! $this->checkImage()) {
            return $this;
        }

        // clear temp image
        $this->clearTemp();

        // create a temp based on new dimensions
        $this->tempImage = imagecreatetruecolor($mW, $mH);

        // check it
        if ( ! is_resource($this->tempImage)) {
            $this->setError('Unable to create temp image sized ' . $mH . ' x ' . $mW);

            return $this;
        }

        // copy resized (stretched, proportions not kept);
        imagecopyresampled($this->tempImage, $this->mainImage, 0, 0, 0, 0, $mW, $mH, $this->width, $this->height);

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // crop the main image to temp image using coords
    //----------------------------------------------------------------------------------------------------------
    public function crop($x1, $y1, $x2, $y2)
    {
        if ( ! $this->checkImage()) {
            return $this;
        }

        // clear temp image
        $this->clearTemp();

        // check dimensions
        if ($x1 < 0 || $y1 < 0 || $x2 - $x1 > $this->width || $y2 - $y1 > $this->height) {
            $this->setError('Invalid crop dimensions, either - passed or width/height too large ' . $x1 . '/' . $y1 . ' x ' . $x2 . '/' . $y2);

            return $this;
        }

        // create a temp based on new dimensions
        $this->tempImage = imagecreatetruecolor($x2 - $x1, $y2 - $y1);

        // check it
        if ( ! is_resource($this->tempImage)) {
            $this->setError('Unable to create temp image sized ' . $x2 - $x1 . ' x ' . $y2 - $y1);

            return $this;
        }

        // copy cropped portion
        imagecopy($this->tempImage, $this->mainImage, 0, 0, $x1, $y1, $x2 - $x1, $y2 - $y1);

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // convert #aa0011 to a php color array
    //----------------------------------------------------------------------------------------------------------
    private function htmlToRgb($color)
    {
        if (is_array($color)) {
            if (count($color) == 3) {
                return $color;
            }                                // rgb sent as an array so use it
            $this->setError('Color error, expected array(r,g,b)');

            return false;
        }
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        if (strlen($color) == 6) {
            list($r, $g, $b) = array($color[0] . $color[1],
                $color[2] . $color[3],
                $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            $this->setError('Color error, value sent not #RRGGBB or RRGGBB, and not array(r,g,b)');

            return false;
        }

        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);

        return array($r, $g, $b);
    }

    //----------------------------------------------------------------------------------------------------------
    // rotate an image bu 0 / 90 / 180 / 270 degrees
    //----------------------------------------------------------------------------------------------------------
    public function rotate($angle)
    {
        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            return $this;
        }

        // if no operations, copy it for temp save
        $this->copyToTempIfNeeded();

        // set the color
        $col = $this->htmlToRgb($$this->backgroundColor);
        $bg = imagecolorallocate($this->tempImage, $col[0], $col[1], $col[2]);

        // rotate as needed
        $this->tempImage = imagerotate($this->tempImage, $angle, $bg);

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // create an image from text that can be applied as a watermark
    // text is the text to write, $font file is a ttf file that will be used $size=font size, $color is the color of text
    //----------------------------------------------------------------------------------------------------------
    public function makeWatermarkText($text, $fontFile, $params = [])
    {
        $fontPath = base() . '/resources/assets/fonts/';

        $fontFile = $fontPath . $fontFile;

        // check font file can be found
        if ( ! file_exists($fontFile)) {
            $this->setError('Could not locate font file "' . $fontFile . '" in ' . $fontPath);

            return $this;
        }

        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            $remove = true;
            // no image loaded so make temp image to use
            $this->mainImage = imagecreatetruecolor(1000, 1000);
        } else {
            $remove = false;
        }

        $size = (isset($params['size'])) ? $params['size'] : 16;
        $color = (isset($params['color'])) ? $params['color'] : '#ffffff';
        $angle = (isset($params['angle'])) ? $params['angle'] : 0;

        // work out text dimensions
        $bBox = imageftbbox($size, $angle, $fontFile, $text);
        $bw = abs($bBox[4] - $bBox[0]) + 1;
        $bh = abs($bBox[1] - $bBox[5]) + 1;
        $bl = $bBox[1];

        // use this to create watermark image
        if (is_resource($this->watermarkImage)) {
            imagedestroy($this->watermarkImage);
        }
        $this->watermarkImage = imagecreatetruecolor($bw, $bh);

        // set colors
        $col = $this->htmlToRgb($color);
        $fontCol = imagecolorallocate($this->watermarkImage, $col[0], $col[1], $col[2]);
        $bgCol = imagecolorallocate($this->watermarkImage, 127, 128, 126);

        // set method to use
        $this->watermarkMethod = 2;

        // create bg
        imagecolortransparent($this->watermarkImage, $bgCol);
        imagefilledrectangle($this->watermarkImage, 0, 0, $bw, $bh, $bgCol);

        // write text to watermark
        imagefttext($this->watermarkImage, $size, $angle, 0, $bh - $bl, $fontCol, $fontFile, $text);

        if ($remove) {
            imagedestroy($this->mainImage);
        }

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // add a watermark to the image
    // position works like a keypad e.g.
    // 7 8 9
    // 4 5 6
    // 1 2 3
    // offset moves image inwards by x pixels
    // if abs is set then $position, $offset = direct placement coords
    //----------------------------------------------------------------------------------------------------------
    public function watermark($position, $offset = 8, $abs = false)
    {
        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            return $this;
        }

        // validate we have a watermark
        if ( ! is_resource($this->watermarkImage)) {
            $this->setError('Can\'t watermark image, no watermark loaded/created');

            return $this;
        }

        // if no operations, copy it for temp save
        $this->copyToTempIfNeeded();

        // get watermark width
        $wmW = imageSX($this->watermarkImage);
        $wm_h = imageSY($this->watermarkImage);

        // get temp widths
        $tempW = imageSX($this->tempImage);
        $tempH = imageSY($this->tempImage);

        // check watermark will fit!
        if ($wmW > $tempW || $wm_h > $tempH) {
            $this->setError("Watermark is larger than image. WM: $wmW x $wm_h Temp image: $tempW x $tempH");

            return $this;
        }

        if ($abs) {
            // direct placement
            $targetX = $position;
            $targetY = $offset;
        } else {
            // do X position
            switch ($position) {
                // x left
                case '7':
                case '4':
                case '1':
                    $targetX = $offset;
                    break;
                // x middle
                case '8':
                case '5':
                case '2':
                    $targetX = ($tempW - $wmW) / 2;
                    break;
                // x right
                case '9':
                case '6':
                case '3':
                    $targetX = $tempW - $offset - $wmW;
                    break;
                default:
                    $targetX = $offset;
                    $this->setError("Watermark position $position not in valid range 7,8,9 - 4,5,6 - 1,2,3");
            }
            // do y position
            switch ($position) {
                // y top
                case '7':
                case '8':
                case '9':
                    $targetY = $offset;
                    break;
                // y middle
                case '4':
                case '5':
                case '6':
                    $targetY = ($tempH - $wm_h) / 2;
                    break;
                // y bottom
                case '1':
                case '2':
                case '3':
                    $targetY = $tempH - $offset - $wm_h;
                    break;
                default:
                    $targetY = $offset;
                    $this->setError("Watermark position $position not in valid range 7,8,9 - 4,5,6 - 1,2,3");
            }

        }

        // copy over temp image to desired location
        if ($this->watermarkMethod == 1) {
            // use back methods to do this, taken from php help files
            //$this->imagecopymerge_alpha($this->tempImage, $this->watermarkImage, $targetX, $targetY, 0, 0, $wmW, $wm_h, $this->watermarkTransparency);

            $opacity = $this->watermarkTransparency;

            // creating a cut resource
            $cut = imagecreatetruecolor($wmW, $wm_h);

            // copying that section of the background to the cut
            imagecopy($cut, $this->tempImage, 0, 0, $targetX, $targetY, $wmW, $wm_h);

            // inverting the opacity
            $opacity = 100 - $opacity;

            // placing the watermark now
            imagecopy($cut, $this->watermarkImage, 0, 0, 0, 0, $wmW, $wm_h);
            imagecopymerge($this->tempImage, $cut, $targetX, $targetY, 0, 0, $wmW, $wm_h, $opacity);

        } else {
            // use normal with selected transparency color
            imagecopymerge($this->tempImage, $this->watermarkImage, $targetX, $targetY, 0, 0, $wmW, $wm_h, $this->watermarkTransparency);
        }

        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // add a solid border  frame, colored $color to the image
    //----------------------------------------------------------------------------------------------------------
    public function border($width = 5, $color = '#000')
    {
        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            return $this;
        }

        // if no operations, copy it for temp save
        $this->copyToTempIfNeeded();

        // get color set for temp image
        $col = $this->htmlToRgb($color);
        $borderCol = imagecolorallocate($this->tempImage, $col[0], $col[1], $col[2]);

        // get temp widths
        $tempW = imageSX($this->tempImage);
        $tempH = imageSY($this->tempImage);

        // do border
        for ($x = 0; $x < $width; $x++) {
            imagerectangle($this->tempImage, $x, $x, $tempW - $x - 1, $tempH - $x - 1, $borderCol);
        }

        // return object
        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // overlay a black white border to make it look 3d
    //----------------------------------------------------------------------------------------------------------
    public function border3D($width = 5, $rot = 0, $opacity = 30)
    {
        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            return $this;
        }

        // if no operations, copy it for temp save
        $this->copyToTempIfNeeded();

        // get temp widths
        $tempW = imageSX($this->tempImage);
        $tempH = imageSY($this->tempImage);

        // create temp canvas to merge
        $borderImage = imagecreatetruecolor($tempW, $tempH);

        // create colors
        $black = imagecolorallocate($borderImage, 0, 0, 0);
        $white = imagecolorallocate($borderImage, 255, 255, 255);
        switch ($rot) {
            case 1 :
                $cols = array($white, $black, $white, $black);
                break;
            case 2 :
                $cols = array($black, $black, $white, $white);
                break;
            case 3 :
                $cols = array($black, $white, $black, $white);
                break;
            default :
                $cols = array($white, $white, $black, $black);
        }
        $bgCol = imagecolorallocate($borderImage, 127, 128, 126);

        // create bg
        imagecolortransparent($borderImage, $bgCol);
        imagefilledrectangle($borderImage, 0, 0, $tempW, $tempH, $bgCol);

        // do border
        for ($x = 0; $x < $width; $x++) {
            // top
            imageline($borderImage, $x, $x, $tempW - $x - 1, $x, $cols[0]);
            // left
            imageline($borderImage, $x, $x, $x, $tempW - $x - 1, $cols[1]);
            // bottom
            imageline($borderImage, $x, $tempH - $x - 1, $tempW - 1 - $x, $tempH - $x - 1, $cols[3]);
            // right
            imageline($borderImage, $tempW - $x - 1, $x, $tempW - $x - 1, $tempH - $x - 1, $cols[2]);
        }

        // merg with temp image
        imagecopymerge($this->tempImage, $borderImage, 0, 0, 0, 0, $tempW, $tempH, $opacity);

        // clean up
        imagedestroy($borderImage);

        // return object
        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // add a shadow to an image, this will INCREASE the size of the image
    //----------------------------------------------------------------------------------------------------------
    public function shadow($size = 4, $direction = 3, $color = '#444')
    {
        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            return $this;
        }

        // if no operations, copy it for temp save
        $this->copyToTempIfNeeded();

        // get the current size
        $sx = imagesx($this->tempImage);
        $sy = imagesy($this->tempImage);

        // new image
        $buImage = imagecreatetruecolor($sx, $sy);

        // check it
        if ( ! is_resource($buImage)) {
            $this->setError('Unable to create shadow temp image sized ' . $this->width . ' x ' . $this->height);

            return false;
        }

        // copy the current image to memory
        imagecopy($buImage, $this->tempImage, 0, 0, 0, 0, $sx, $sy);

        imagedestroy($this->tempImage);
        $this->tempImage = imagecreatetruecolor($sx + $size, $sy + $size);

        // fill background color
        $col = $this->htmlToRgb($this->backgroundColor);
        $bg = imagecolorallocate($this->tempImage, $col[0], $col[1], $col[2]);
        imagefilledrectangle($this->tempImage, 0, 0, $sx + $size, $sy + $size, $bg);

        // work out position
        // do X position
        switch ($direction) {
            // x left
            case '7':
            case '4':
            case '1':
                $shX = 0;
                $picX = $size;
                break;
            // x middle
            case '8':
            case '5':
            case '2':
                $shX = $size / 2;
                $picX = $size / 2;
                break;
            // x right
            case '9':
            case '6':
            case '3':
                $shX = $size;
                $picX = 0;
                break;
            default:
                $shX = $size;
                $picX = 0;
                $this->setError("Shadow direction $direction not in valid range 7,8,9 - 4,5,6 - 1,2,3");
        }
        // do y position
        switch ($direction) {
            // y top
            case '7':
            case '8':
            case '9':
                $shY = 0;
                $picY = $size;
                break;
            // y middle
            case '4':
            case '5':
            case '6':
                $shY = $size / 2;
                $picY = $size / 2;
                break;
            // y bottom
            case '1':
            case '2':
            case '3':
                $shY = $size;
                $picY = 0;
                break;
            default:
                $shY = $size;
                $picY = 0;
                $this->setError("Shadow direction $direction not in valid range 7,8,9 - 4,5,6 - 1,2,3");
        }

        // create the shadow
        $shadowColor = $this->htmlToRgb($color);
        $shadow = imagecolorallocate($this->tempImage, $shadowColor[0], $shadowColor[1], $shadowColor[2]);
        imagefilledrectangle($this->tempImage, $shX, $shY, $shX + $sx - 1, $shY + $sy - 1, $shadow);

        // copy current image to correct location
        imagecopy($this->tempImage, $buImage, $picX, $picY, 0, 0, $sx, $sy);

        // clean up and destroy temp image
        imagedestroy($buImage);

        //return object
        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // allows you to use the inbulit gd2 image filters
    //----------------------------------------------------------------------------------------------------------
    public function filter($function, $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
    {
        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            return $this;
        }

        // if no operations, copy it for temp save
        $this->copyToTempIfNeeded();

        if ( ! imagefilter($this->tempImage, $function, $arg1, $arg2, $arg3, $arg4)) {
            $this->setError("Filter $function failed");
        }

        // return object
        return $this;
    }

    //----------------------------------------------------------------------------------------------------------
    // adds rounded corners to the output
    // using a quarter and rotating as you can end up with odd roudning if you draw a whole and use parts
    //----------------------------------------------------------------------------------------------------------
    public function round($radius = 5, $invert = false, $corners = '')
    {
        // validate we loaded a main image
        if ( ! $this->checkImage()) {
            return $this;
        }

        // if no operations, copy it for temp save
        $this->copyToTempIfNeeded();

        // check input
        if ($corners == '') {
            $corners = array(true, true, true, true);
        }
        if ( ! is_array($corners) || count($corners) <> 4) {
            $this->setError('Round failed, expected an array of 4 items round(radius,tl,tr,br,bl)');

            return $this;
        }

        // create corner
        $corner = imagecreatetruecolor($radius, $radius);

        // turn on aa make it nicer
        imageantialias($corner, true);
        $col = $this->htmlToRgb($this->backgroundColor);

        // use bg col for corners
        $bg = imagecolorallocate($corner, $col[0], $col[1], $col[2]);

        // create our transparent color
        $xParent = imagecolorallocate($corner, 127, 128, 126);
        imagecolortransparent($corner, $xParent);
        if ($invert) {
            // fill and clear bits
            imagefilledrectangle($corner, 0, 0, $radius, $radius, $xParent);
            imagefilledellipse($corner, 0, 0, ($radius * 2) - 1, ($radius * 2) - 1, $bg);
        } else {
            // fill and clear bits
            imagefilledrectangle($corner, 0, 0, $radius, $radius, $bg);
            imagefilledellipse($corner, $radius, $radius, ($radius * 2), ($radius * 2), $xParent);
        }

        // get temp widths
        $tempW = imageSX($this->tempImage);
        $tempH = imageSY($this->tempImage);

        // do corners
        if ($corners[0]) {
            imagecopymerge($this->tempImage, $corner, 0, 0, 0, 0, $radius, $radius, 100);
        }
        $corner = imagerotate($corner, 270, 0);
        if ($corners[1]) {
            imagecopymerge($this->tempImage, $corner, $tempW - $radius, 0, 0, 0, $radius, $radius, 100);
        }
        $corner = imagerotate($corner, 270, 0);
        if ($corners[2]) {
            imagecopymerge($this->tempImage, $corner, $tempW - $radius, $tempH - $radius, 0, 0, $radius, $radius, 100);
        }
        $corner = imagerotate($corner, 270, 0);
        if ($corners[3]) {
            imagecopymerge($this->tempImage, $corner, 0, $tempH - $radius, 0, 0, $radius, $radius, 100);
        }

        // return object
        return $this;
    }

}
