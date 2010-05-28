<?php
/**
 * Description of Image
 *
 * @author martin
 *
 */
class App_Image {

    const X_NO_UPLOAD     = 0;
    const X_BAD_SETTINGS  = 1;
    const X_LOST_FILE     = 2;
    const X_BAD_IMAGE     = 3;

    protected static $imageTypes = array(
        'jpg' => IMAGETYPE_JPEG,
        'jpeg' => IMAGETYPE_JPEG,
        'png' => IMAGETYPE_PNG,
        'gif' => IMAGETYPE_GIF
    );

    public static function saveImageVariations($src_path, $dst_dir, $variations = array()){

        $name = basename($src_path);

        $output = array();

        foreach($variations as $variation => $spec_str){

            list($dstWidth, $dstHeight, $method, $dstFormat) = self::parseImageSpec($spec_str);
            $dst = self::getImagePath($dst_dir, $variation, $name, $dstFormat);
            self::resizeImage($src_path, $dst, $dstWidth, $dstHeight, $method, $dstFormat);

            $output[$variation] = $dst;
        }

        return $output;
    }

    public static function getImagePath($image_dir, $variation, $name, $dstFormat = null){

        if($dstFormat !== null) $name = App_File::pathInfo($name, PATHINFO_FILENAME) . '.' . $dstFormat;
        return rtrim($image_dir, '/') . '/' . $variation . '/' . $name;
    }

    public static function parseImageSpec($dim_str){

        if(!preg_match('/^(\d+)\W+(\d+)\W+(\w,+)(?:\W+(\w+))?$/', strtolower($dim_str), $matches))
            throw new App_Exception('Image specification invalid', self::X_BAD_SETTINGS);

        array_shift($matches);

        if(!array_key_exists(3, $matches)){
            $matches[3] = null;
        }

        return $matches;
    }

    public static function parseResizeMethod($method){

        if(is_string($method)){
            $method = explode(',', $method);
        }

        if(!is_array($method) || empty($method)){
            throw new App_Exception('Resize method invalid', self::X_BAD_SETTINGS);
        }

        return $method;
    }

    /**
     * Resizes, and optionally reformats, images without stretching or producing distortion.
     * Uses one of six sizing algorithms:
     *
     * crop
     * Takes the largest possible rectangle from the centre of the source image that matches the aspect ratio of the
     * destination image and scales it to the destination dimensions. This method guarantees the
     * destination image dimensions will exactly match the destination dimensions ($dstWidth, $dstHeight).
     *
     * scale
     * Takes the entire image area of the source image and scales it to the largest possible size that does not exceed
     * the destination dimensions. The destination image may not match, but will not exceed, the destination dimensions
     * ($dstWidth, $dstHeight).
     * 
     * centre
     * As scale except that this method guarantees the destination image dimensions will exactly match the destination 
     * dimensions ($dstWidth, $dstHeight). Any area of the destination image not covered by the source image will be
     * transparent for PNG and GIF or filled with white space for JPG.
     *
     * limit,crop
     * limit,scale
     * limit,centre
     * As the non-limit methods but the image is only resized if the source dimensions are greater than the destination 
     * dimensions ($dstWidth, $dstHeight). With all limit methods, the destination image may not match, but will not 
     * exceed, the destination dimensions ($dstWidth, $dstHeight).
     * 
     * In all cases, if the destination dimensions and format match those of the source file, the source file is copied
     * to the destination path as is.
     *
     * @param string $srcFile path to source image
     * @param string $dstFile path where destination file should be saved
     * @param int $dstWidth width of destination image
     * @param int $dstHeight height of destination image
     * @param mixed $method OPTIONAL crop|scale|limit,crop|limit,scale DEFAULT crop, resizing method to use, may be
     * given as an array e.g. array('limit', 'crop') or a comma delimited string e.g. 'limit,crop'.
     * @param string $dstFormat OPTIONAL jpg|png|gif DEFAULT format of $srcFile, destination format
     * @return void
     * @throws App_Exception on error
     */
    public static function resizeImage($srcFile, $dstFile, $dstWidth, $dstHeight, $method = 'crop', $dstFormat = null){


        // make sure dst directory exists

        App_File::ensurePathExists($dstFile, true);


        // analyse src image

        list($srcWidth, $srcHeight, $srcType) = getimagesize($srcFile);


        // validate format

        if(empty($dstFormat)){
            $dstFormat = array_search($srcType, self::$imageTypes);
        }

        if(!array_key_exists($dstFormat, self::$imageTypes)){

            throw new App_Exception(
                'Unsupported destination image format: ' . $dstFormat . ' original format: ' . $srcType,
                self::X_BAD_SETTINGS
            );
        }

        $dstType = self::$imageTypes[$dstFormat];
        
        $dstTop = $dstLeft = 0;
        $scaleWidth = $dstWidth;
        $scaleHeight = $dstHeight;


        // calculate sample dimensions, if src image satisfies dst criteria, copy file across and return

        if($srcWidth == $dstWidth && $srcHeight == $dstHeight){

            if($srcType == $dstType){

                if(!copy($srcFile, $dstFile)){
                    throw new App_Exception('Could not copy file', self::X_LOST_FILE);
                }
                return;
            }

            $sampleWidth = $srcWidth;
            $sampleHeight = $srcHeight;
            $sampleLeft = $sampleTop = 0;

        }else{


            // calculate aspect ratios

            $srcRatio = $srcWidth / $srcHeight;
            $dstRatio = $dstWidth / $dstHeight;


            // parse method

            $method = self::parseResizeMethod($method);
            $op = array_shift($method);

            if($op == 'limit'){

                if($srcWidth <= $dstWidth && $srcHeight <= $dstHeight){

                    if($srcType == $dstType){

                        if(!copy($srcFile, $dstFile)){
                            throw new App_Exception('Could not copy file', self::X_LOST_FILE);
                        }
                        return;
                    }

                    $scaleWidth = $dstWidth = $srcWidth;
                    $scaleHeight = $dstHeight = $srcHeight;
                    $dstRatio = $srcRatio;

                }else{

                    $op = array_shift($method);
                }

            }

            switch($op){

                case 'crop':

                    if($srcRatio >= $dstRatio){

                        // src is wider than dst
                        $sampleHeight = $srcHeight;
                        $sampleWidth = $srcHeight * $dstRatio;

                        $sampleTop = 0;
                        $sampleLeft = (int)($srcWidth / 2 - $sampleWidth / 2);

                    }else{

                        // src is taller than dst
                        $sampleWidth = $srcWidth;
                        $sampleHeight = $srcWidth / $dstRatio;

                        $sampleLeft = 0;
                        $sampleTop = (int)($srcHeight / 2 - $sampleHeight / 2);
                    }

                    break;

                case 'scale':

                    $sampleWidth = $srcWidth;
                    $sampleHeight = $srcHeight;
                    $sampleLeft = $sampleTop = 0;

                    if($srcRatio >= $dstRatio){

                        // src is wider than dst
                        $scaleHeight = $dstHeight = $dstWidth / $srcRatio;

                    }else{

                        // src is taller than dst
                        $scaleWidth = $dstWidth = $dstHeight * $srcRatio;
                    }

                    break;
                    
                case 'centre':
                    
                    $sampleWidth = $srcWidth;
                    $sampleHeight = $srcHeight;
                    $sampleLeft = $sampleTop = 0;
                    
                    if($srcRatio >= $dstRatio){

                        // src is wider than dst
                        $scaleHeight = $dstWidth / $srcRatio;
                        $dstTop = (int)($dstHeight / 2 - $scaleHeight / 2);                        

                    }else{

                        // src is taller than dst
                        $scaleWidth = $dstHeight * $srcRatio;
                        $dstLeft = (int)($dstWidth / 2 - $scaleWidth / 2);
                    }
                    
                    break;

                case 'limit':

                    break;

                default:

                    throw new App_Exception('Unsupported sizing method', self::X_BAD_SETTINGS);

            }
        }

        // create image objects, canvases

        switch($srcType){

            case IMAGETYPE_JPEG:

                $srcImg = imagecreatefromjpeg($srcFile);
                $dstImg = imagecreatetruecolor($dstWidth, $dstHeight);
                imagefill($dstImg, 0, 0, imagecolorallocate($dstImg, 255, 255, 255));
                break;

            case IMAGETYPE_PNG:

                $srcImg = imagecreatefrompng($srcFile);
                $dstImg = imagecreatetruecolor($dstWidth, $dstHeight);
                imagesavealpha($dstImg, true);
                imagefill($dstImg, 0, 0, imagecolorallocatealpha($dstImg, 0, 0, 0, 127));
                break;

            case IMAGETYPE_GIF:

                $srcImg = imagecreatefromgif($srcFile);
                $dstImg = imagecreatetruecolor($dstWidth, $dstHeight);
                imagesavealpha($dstImg, true);
                imagefill($dstImg, 0, 0, imagecolorallocatealpha($dstImg, 0, 0, 0, 127));
                break;

            default:
                throw new App_Exception('Unsupported source image format', self::X_BAD_IMAGE);
        }

        //var_dump(compact('dst_img', 'src_img', 'left', 'top', 'width', 'height', 's_width', 's_height'));
        //exit();


        // copy image data from src canvas to dst canvas

        imagecopyresampled(
            $dstImg, $srcImg,           //canvases
            $dstLeft, $dstTop,          //dst top-left
            $sampleLeft, $sampleTop,    //src top-left
            $scaleWidth, $scaleHeight,  //dst size
            $sampleWidth, $sampleHeight //src size
        );


        // save dst canvas to dst file

        switch($dstType){

            case IMAGETYPE_JPEG:

                imagejpeg($dstImg, $dstFile);
                break;

            case IMAGETYPE_PNG:

                imagepng($dstImg, $dstFile);
                break;

            case IMAGETYPE_GIF:

                imagegif($dstImg, $dstFile);
                break;

        }


        // clean up

        imagedestroy($srcImg);
        imagedestroy($dstImg);
    }
}
?>
