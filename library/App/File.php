<?php

if(!defined('PATHINFO_FILENAME')){
    define('PATHINFO_FILENAME', 8);
    define('PATHINFO_IS_OLD', true);
}

class App_File {

    public static function ensurePathExists($path, $path_is_filename = false, $mode = 0775){

        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        if($path_is_filename) $path = substr($path, 0, strrpos($path, DIRECTORY_SEPARATOR));
        if(is_dir($path) || empty($path)) return true;

        // Ensure a file does not already exist with the same name
        if(file_exists($path)){
            throw new App_Exception('Directory path ' . $path . ' is file');
        }

        // Crawl up the directory tree
        self::ensurePathExists(substr($path, 0, strrpos($path, DIRECTORY_SEPARATOR)), false, $mode);

        $oldMask = umask(~$mode);
        $made = mkdir($path, $mode);
        umask($oldMask);

        if(!$made) throw new App_Exception('mkdir failed for ' . $path);

        return false;

    }

    public static function move($src, $dst){
        self::ensurePathExists($dst, true);
        rename($src, $dst);
    }

    public static function delete($src){

        $src = realpath($src);

        // delete file
        if(is_file($src)){
            if(is_writable($src) && @unlink($src)) return true;
            return false;
        }

        // delete dir
        if(is_dir($src)){
            if(is_writeable($src)){

                $dir = new DirectoryIterator($src);

                foreach($dir as $node) {
                    if(!$node->isDot()) self::delete($node->getPathName());
                    unset($node);
                }

                unset($dir);

                if(@rmdir($src)) return true;
            }

            return false;
        }
    }

    public static function pathToURL($path, $base_path = ''){

        $rpath = realpath($path);

        if(!$rpath) throw new App_Exception('path' . $path . ' does not exist');

        $bdir = realpath($_SERVER['DOCUMENT_ROOT'] . $base_path);
        $bdirlen = strlen($bdir);

        //var_dump(compact('path', 'rpath', 'bdir', 'bdirlen'));

        if(substr($rpath, 0, $bdirlen) != $bdir){
            throw new App_Exception('path ' . $path . 'outside document root');
        }

        return substr($rpath, $bdirlen);

    }


    public static function pathInfo($path, $return = 0){

        $remove_basename = $process_filename = false;

        if(defined('PATHINFO_IS_OLD') && ($return & PATHINFO_FILENAME)){
            $process_filename = true;
            $return ^= PATHINFO_FILENAME;
            if(!($return & PATHINFO_BASENAME)){
                $remove_basename = true;
                $return |= PATHINFO_BASENAME;
            }

        }

        $output = pathinfo($path, $return);

        if($process_filename) if(is_array($output)){
            $output['filename'] = substr($output['basename'], 0, strrpos($output['basename'], '.'));
            if($remove_basename) unset($ouput['basename']);
        }else{
            $output = substr($output, 0, strrpos($output, '.'));
        }

        return $output;
    }

}
?>