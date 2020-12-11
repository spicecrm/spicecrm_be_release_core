<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
********************************************************************************/

/*********************************************************************************
 * Description:
 ********************************************************************************/

/**
 * @api
 * Manage uploaded files
 */
class UploadFile
{
    var $field_name;
    var $stored_file_name;
    var $uploaded_file_name;
    var $uploaded_file_size;
    var $original_file_name;
    var $uploaded_file_md5;
    var $temp_file_location;
    var $use_soap = false;
    var $use_proxy = false;
    var $file;
    var $file_ext;
    protected static $url = "upload/";

    /**
     * Upload errors
     * @var array
     */
    protected static $filesError = array(
        UPLOAD_ERR_OK => 'UPLOAD_ERR_OK - There is no error, the file uploaded with success.',
        UPLOAD_ERR_INI_SIZE => 'UPLOAD_ERR_INI_SIZE - The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        UPLOAD_ERR_FORM_SIZE => 'UPLOAD_ERR_FORM_SIZE - The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
        UPLOAD_ERR_PARTIAL => 'UPLOAD_ERR_PARTIAL - The uploaded file was only partially uploaded.',
        UPLOAD_ERR_NO_FILE => 'UPLOAD_ERR_NO_FILE - No file was uploaded.',
        5 => 'UNKNOWN ERROR',
        UPLOAD_ERR_NO_TMP_DIR => 'UPLOAD_ERR_NO_TMP_DIR - Missing a temporary folder.',
        UPLOAD_ERR_CANT_WRITE => 'UPLOAD_ERR_CANT_WRITE - Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION => 'UPLOAD_ERR_EXTENSION - A PHP extension stopped the file upload.',
    );

    /**
     * Create upload file handler
     * @param string $field_name Form field name
     */
    function __construct($field_name = '')
    {
        // $field_name is the name of your passed file selector field in your form
        // i.e., for Emails, it is "email_attachmentX" where X is 0-9
        $this->field_name = $field_name;
    }

    /**
     * Setup for SOAP upload
     * @param string $filename Name for the file
     * @param string $file
     */
    function set_for_soap($filename, $file)
    {
        $this->stored_file_name = $filename;
        $this->use_soap = true;
        $this->file = $file;
    }



    /**
     * Try renaming a file to bean_id name
     * @param string $filename
     * @param string $bean_id
     */
    protected static function tryRename($filename, $bean_id)
    {
        $fullname = "upload://$bean_id.$filename";
        if (file_exists($fullname)) {
            if (!rename($fullname, "upload://$bean_id")) {
                $GLOBALS['log']->fatal("unable to rename file: $fullname => $bean_id");
            }
            return true;
        }
        return false;
    }


    /**
     * Get upload error from system
     * @return string upload error
     */
    public function get_upload_error()
    {
        if (isset($this->field_name) && isset($_FILES[$this->field_name]['error'])) {
            return $_FILES[$this->field_name]['error'];
        }
        return false;
    }

    /**
     * standard PHP file-upload security measures. all variables accessed in a global context
     * @return bool True on success
     */
    public function confirm_upload()
    {
        global $sugar_config;

        if (empty($this->field_name) || !isset($_FILES[$this->field_name])) {
            return false;
        }

        //check to see if there are any errors from upload
        if ($_FILES[$this->field_name]['error'] != UPLOAD_ERR_OK) {
            if ($_FILES[$this->field_name]['error'] != UPLOAD_ERR_NO_FILE) {
                if ($_FILES[$this->field_name]['error'] == UPLOAD_ERR_INI_SIZE) {
                    //log the error, the string produced will read something like:
                    //ERROR: There was an error during upload. Error code: 1 - UPLOAD_ERR_INI_SIZE - The uploaded file exceeds the upload_max_filesize directive in php.ini. upload_maxsize is 16
                    $errMess = string_format($GLOBALS['app_strings']['UPLOAD_ERROR_TEXT_SIZEINFO'], array($_FILES['filename_file']['error'], self::$filesError[$_FILES['filename_file']['error']], $sugar_config['upload_maxsize']));
                    $GLOBALS['log']->fatal($errMess);
                } else {
                    //log the error, the string produced will read something like:
                    //ERROR: There was an error during upload. Error code: 3 - UPLOAD_ERR_PARTIAL - The uploaded file was only partially uploaded.
                    $errMess = string_format($GLOBALS['app_strings']['UPLOAD_ERROR_TEXT'], array($_FILES['filename_file']['error'], self::$filesError[$_FILES['filename_file']['error']]));
                    $GLOBALS['log']->fatal($errMess);
                }
            }
            return false;
        }

        if ($_FILES[$this->field_name]['proxy'] !== true && !is_uploaded_file($_FILES[$this->field_name]['tmp_name'])) {
            return false;
        } elseif (($_FILES[$this->field_name]['size'] > 1048576) && ($_FILES[$this->field_name]['size'] > $sugar_config['upload_maxsize'])) {
            $GLOBALS['log']->fatal("ERROR: uploaded file was too big: max filesize: {$sugar_config['upload_maxsize']}");
            return false;
        }

        if (!UploadStream::writable()) {
            $GLOBALS['log']->fatal("ERROR: cannot write to upload directory");
            return false;
        }

        $this->mime_type = $this->getMime($_FILES[$this->field_name]);
        $this->stored_file_name = $this->create_stored_filename();
        $this->temp_file_location = $_FILES[$this->field_name]['tmp_name'];
        $this->uploaded_file_name = $_FILES[$this->field_name]['name'];
        $this->uploaded_file_size = $_FILES[$this->field_name]['size'];
        $this->uploaded_file_md5 = md5_file($_FILES[$this->field_name]['tmp_name']);

        return true;
    }

    /**
     * Guess MIME type for file
     * @param string $filename
     * @return string MIME type
     */
    function getMimeSoap($filename)
    {
        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $nameArray = explode('.', $filename);
        $ext = strtolower(array_pop($nameArray));
        if (array_key_exists($ext, $mime_types)) {
            $mime = $mime_types[$ext];
        } else if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mime = finfo_file($finfo, $filename);
            finfo_close($finfo);
        } else if (function_exists('mime_content_type')) {
            $mime = mime_content_type($filename);
        } else {
            $mime = ' application/octet-stream';
        }
        return $mime;
    }

    /**
     * Get MIME type for uploaded file
     * @param array $_FILES_element $_FILES element required
     * @return string MIME type
     */
    function getMime($_FILES_element)
    {
        $filename = $_FILES_element['name'];
        $filetype = isset($_FILES_element['type']) ? $_FILES_element['type'] : null;
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

        $is_image = strpos($filetype, 'image/') === 0;
        // if it's an image, or no file extension is available and the mime is octet-stream
        // try to determine the mime type
        $recheckMime = $is_image || (empty($file_ext) && $filetype == 'application/octet-stream');

        $mime = 'application/octet-stream';
        if ($filetype && !$recheckMime) {
            $mime = $filetype;
        } elseif (function_exists('mime_content_type')) {
            $mime = mime_content_type($_FILES_element['tmp_name']);
        } elseif ($is_image) {
            $info = getimagesize($_FILES_element['tmp_name']);
            if ($info) {
                $mime = $info['mime'];
            }
        } elseif (function_exists('ext2mime')) {
            $mime = ext2mime($filename);
        }

        return $mime;
    }

    /**
     * gets note's filename
     * @return string
     */
    function get_stored_file_name()
    {
        return $this->stored_file_name;
    }

    function get_uploaded_file_size()
    {
        return $this->uploaded_file_size;
    }

    function get_uploaded_file_md5()
    {
        return $this->uploaded_file_md5;
    }

    /**
     * Returns the contents of the uploaded file
     */
    public function get_file_contents()
    {

        // Need to call
        if (!isset($this->temp_file_location)) {
            $this->confirm_upload();
        }

        if (($data = @file_get_contents($this->temp_file_location)) === false) {
            return false;
        }

        return $data;
    }


    /**
     * creates a file's name for preparation for saving
     * @return string
     */
    function create_stored_filename()
    {
        global $sugar_config;

        if (!$this->use_soap) {
            $stored_file_name = $_FILES[$this->field_name]['name'];
            $this->original_file_name = $stored_file_name;

            /**
             * cn: bug 8056 - windows filesystems and IIS do not like utf8.  we are forced to urlencode() to ensure that
             * the file is linkable from the browser.  this will stay broken until we move to a db-storage system
             */
            if (is_windows()) {
                // create a non UTF-8 name encoding
                // 176 + 36 char guid = windows' maximum filename length
                $end = (strlen($stored_file_name) > 176) ? 176 : strlen($stored_file_name);
                $stored_file_name = substr($stored_file_name, 0, $end);
                $this->original_file_name = $_FILES[$this->field_name]['name'];
            }
            $stored_file_name = str_replace("\\", "", $stored_file_name);
        } else {
            $stored_file_name = $this->stored_file_name;
            $this->original_file_name = $stored_file_name;
        }

        $this->file_ext = pathinfo($stored_file_name, PATHINFO_EXTENSION);
        // cn: bug 6347 - fix file extension detection
        foreach ($sugar_config['upload_badext'] as $badExt) {
            if (strtolower($this->file_ext) == strtolower($badExt)) {
                $stored_file_name .= ".txt";
                $this->file_ext = "txt";
                break; // no need to look for more
            }
        }
        return $stored_file_name;
    }

    /**
     * moves uploaded temp file to permanent save location
     * @param string bean_id ID of parent bean
     * @return bool True on success
     */
    function final_move($bean_id, $replace = true)
    {
        $destination = $bean_id;
        if (substr($destination, 0, 9) != "upload://") {
            $destination = "upload://$bean_id";
        }

        if (!$replace && file_exists($destination)) {
            if ($this->use_proxy)
                unlink($_FILES[$this->field_name]['tmp_name']);

            return true;
        }

        if ($this->use_soap) {
            if (!file_put_contents($destination, $this->file)) {
                $GLOBALS['log']->fatal("ERROR: can't save file to $destination");
                return false;
            }
        }
        if ($this->use_proxy) {
            if (!file_put_contents($destination, file_get_contents($_FILES[$this->field_name]['tmp_name']))) {
            // if (!rename($_FILES[$this->field_name]['tmp_name'], $destination)) {
                $GLOBALS['log']->fatal("ERROR: can't save file to $destination");
                return false;
            }
        } else {

                if(!UploadStream::move_uploaded_file($_FILES[$this->field_name]['tmp_name'], $destination)) {
                    $GLOBALS['log']->fatal("ERROR: can't move_uploaded_file to $destination. You should try making the directory writable by the webserver");
                    return false;
                }
        }
        return true;
    }


    /**
     * returns the path with file name to save an uploaded file
     * @param string bean_id ID of the parent bean
     * @return string
     */
    function get_upload_path($bean_id)
    {
        $file_name = $bean_id;

        // cn: bug 8056 - mbcs filename in urlencoding > 212 chars in Windows fails
        $end = (strlen($file_name) > 212) ? 212 : strlen($file_name);
        $ret_file_name = substr($file_name, 0, $end);

        return "upload://$ret_file_name";
    }

    /**
     * deletes a file
     * @param string bean_id ID of the parent bean
     * @param string file_name File's name
     */
    static public function unlink_file($bean_id, $file_name = '')
    {
        if (file_exists("upload://$bean_id$file_name")) {
            return unlink("upload://$bean_id$file_name");
        }
    }

    /**
     * Get upload file location prefix
     * @return string prefix
     */
    public function get_upload_dir()
    {
        return "upload://";
    }

}

/**
 * @internal
 * Upload file stream handler
 */
class UploadStream
{
    const STREAM_NAME = "upload";
    protected static $upload_dir;

    /**
     * Method checks Suhosin restrictions to use streams in php
     *
     * @static
     * @return bool is allowed stream or not
     */
    public static function getSuhosinStatus()
    {
        // looks like suhosin patch doesn't block protocols, only suhosin extension (tested on FreeBSD)
        // if suhosin is not installed it is okay for us
        if (extension_loaded('suhosin') == false) {
            return true;
        }
        $configuration = ini_get_all('suhosin', false);

        // suhosin simulation is okay for us
        if ($configuration['suhosin.simulation'] == true) {
            return true;
        }

        // checking that UploadStream::STREAM_NAME is allowed by white list
        $streams = $configuration['suhosin.executor.include.whitelist'];
        if ($streams != '') {
            $streams = explode(',', $streams);
            foreach ($streams as $stream) {
                $stream = explode('://', $stream, 2);
                if (count($stream) == 1) {
                    if ($stream[0] == UploadStream::STREAM_NAME) {
                        return true;
                    }
                } elseif ($stream[1] == '' && $stream[0] == UploadStream::STREAM_NAME) {
                    return true;
                }
            }

            $GLOBALS['log']->fatal('Stream ' . UploadStream::STREAM_NAME . ' is not listed in suhosin.executor.include.whitelist and blocked because of it');
            return false;
        }

        // checking that UploadStream::STREAM_NAME is not blocked by black list
        $streams = $configuration['suhosin.executor.include.blacklist'];
        if ($streams != '') {
            $streams = explode(',', $streams);
            foreach ($streams as $stream) {
                $stream = explode('://', $stream, 2);
                if ($stream[0] == UploadStream::STREAM_NAME) {
                    $GLOBALS['log']->fatal('Stream ' . UploadStream::STREAM_NAME . 'is listed in suhosin.executor.include.blacklist and blocked because of it');
                    return false;
                }
            }
            return true;
        }

        $GLOBALS['log']->fatal('Suhosin blocks all streams, please define ' . UploadStream::STREAM_NAME . ' stream in suhosin.executor.include.whitelist');
        return false;
    }

    /**
     * Get upload directory
     * @return string
     */
    public static function getDir()
    {
        if (empty(self::$upload_dir)) {
            self::$upload_dir = rtrim($GLOBALS['sugar_config']['upload_dir'], '/\\');
            if (empty(self::$upload_dir)) {
                self::$upload_dir = "upload";
            }
            if (!file_exists(self::$upload_dir)) {
                sugar_mkdir(self::$upload_dir, 0755, true);
            }
        }
        return self::$upload_dir;
    }

    /**
     * Check if upload dir is writable
     * @return bool
     */
    public static function writable()
    {
        return is_writable(self::getDir());
    }

    /**
     * Register the stream
     */
    public static function register()
    {
        stream_register_wrapper(self::STREAM_NAME, __CLASS__);
    }

    /**
     * Get real FS path of the upload stream file
     * @param string $path Upload stream path (with upload://)
     * @return string FS path
     */
    public static function path($path)
    {
        $path = substr($path, strlen(self::STREAM_NAME) + 3); // cut off upload://
        $path = str_replace("\\", "/", $path); // canonicalize path
        if ($path == ".." || substr($path, 0, 3) == "../" || substr($path, -3, 3) == "/.." || strstr($path, "/../")) {
            return null;
        }
        return self::getDir() . "/" . $path;
    }

    /**
     * Ensure upload subdir exists
     * @param string $path Upload stream path (with upload://)
     * @param bool $writable
     * @return boolean
     */
    public static function ensureDir($path, $writable = true)
    {
        $path = self::path($path);
        if (!is_dir($path)) {
            return sugar_mkdir($path, 0755, true);
        }
        return true;
    }

    public function dir_closedir()
    {
        closedir($this->dirp);
    }

    public function dir_opendir($path, $options)
    {
        $this->dirp = opendir(self::path($path));
        return !empty($this->dirp);
    }

    public function dir_readdir()
    {
        return readdir($this->dirp);
    }

    public function dir_rewinddir()
    {
        return rewinddir($this->dirp);
    }

    public function mkdir($path, $mode, $options)
    {
        return mkdir(self::path($path), $mode, ($options & STREAM_MKDIR_RECURSIVE) != 0);
    }

    public function rename($path_from, $path_to)
    {
        return rename(self::path($path_from), self::path($path_to));
    }

    public function rmdir($path, $options)
    {
        return rmdir(self::path($path));
    }

    public function stream_cast($cast_as)
    {
        return $this->fp;
    }

    public function stream_close()
    {
        fclose($this->fp);
        return true;
    }

    public function stream_eof()
    {
        return feof($this->fp);
    }

    public function stream_flush()
    {
        return fflush($this->fp);
    }

    public function stream_lock($operation)
    {
        return flock($this->fp, $operation);
    }

    public function stream_open($path, $mode)
    {
        $fullpath = self::path($path);
        if (empty($fullpath)) return false;
        if ($mode == 'r') {
            $this->fp = fopen($fullpath, $mode);
        } else {
            // if we will be writing, try to transparently create the directory
            $this->fp = @fopen($fullpath, $mode);
            if (!$this->fp && !file_exists(dirname($fullpath))) {
                mkdir(dirname($fullpath), 0755, true);
                $this->fp = fopen($fullpath, $mode);
            }
        }
        return !empty($this->fp);
    }

    public function stream_read($count)
    {
        return fread($this->fp, $count);
    }

    public function stream_seek($offset, $whence = SEEK_SET)
    {
        return fseek($this->fp, $offset, $whence) == 0;
    }

    public function stream_set_option($option, $arg1, $arg2)
    {
        return true;
    }

    public function stream_stat()
    {
        return fstat($this->fp);
    }

    public function stream_tell()
    {
        return ftell($this->fp);
    }

    public function stream_write($data)
    {
        return fwrite($this->fp, $data);
    }

    public function unlink($path)
    {
        unlink(self::path($path));
        return true;
    }

    public function url_stat($path, $flags)
    {
        return @stat(self::path($path));
    }

    public static function move_uploaded_file($upload, $path)
    {
        return move_uploaded_file($upload, self::path($path));
    }
}

