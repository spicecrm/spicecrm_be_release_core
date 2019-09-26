<?php

namespace SpiceCRM\includes\SpiceAttachments;
use Slim\Http\UploadedFile;
use SpiceCRM\modules\Mailboxes\Handlers\OutlookAttachment;

class SpiceAttachments {
    const UPLOAD_DESTINATION = 'upload://';

    public static function getAttachmentsForBean($beanName, $beanId, $lastN = 10, $json_encode = true) {
        global $current_user, $db, $beanFiles, $beanList;
        $attachments = array();

        if ($GLOBALS['db']->dbType == 'mssql') {
            $attachmentsRes = $db->query("SELECT TOP $lastN qn.*,u.user_name FROM spiceattachments AS qn 
                LEFT JOIN users AS u ON u.id=qn.user_id WHERE qn.bean_id='{$beanId}' AND qn.bean_type='{$beanName}'
                AND qn.deleted = 0 ORDER BY qn.trdate DESC");
        } else {
            $attachmentsRes = $db->limitQuery("SELECT qn.*,u.user_name FROM spiceattachments AS qn
                LEFT JOIN users AS u ON u.id=qn.user_id WHERE qn.bean_id='{$beanId}' AND qn.bean_type='{$beanName}'
                AND qn.deleted = 0 ORDER BY qn.trdate DESC", 0, $lastN);
        }

        if ($GLOBALS['db']->dbType == 'mssql' || $db->getRowCount($attachmentsRes) > 0) {
            while ($thisAttachment = $db->fetchByAssoc($attachmentsRes)) {

                $attachments[] = [
                    'id'             => $thisAttachment['id'],
                    'user_id'        => $thisAttachment['user_id'],
                    'user_name'      => $thisAttachment['user_name'],
                    'date'           => $GLOBALS['timedate']->to_display_date_time($thisAttachment['trdate']),
                    'text'           => nl2br($thisAttachment['text']),
                    'filename'       => $thisAttachment['filename'],
                    'filepath'       => self::UPLOAD_DESTINATION . $thisAttachment['filemd5'],
                    'filesize'       => $thisAttachment['filesize'],
                    'filemd5'        => $thisAttachment['filemd5'],
                    'file_mime_type' => $thisAttachment['file_mime_type'],
                    'thumbnail'      => $thisAttachment['thumbnail'],
                    'external_id'    => $thisAttachment['external_id'],
                    'url'            => "index.php?module=SpiceThemeController&action=attachment_download&id="
                                        . $thisAttachment['id'],
                ];
            }
        }
        if ($json_encode)
            return json_encode($attachments);
        else
            return $attachments;
    }

    public static function getAttachmentsForBeanHashFiles($beanName, $beanId, $lastN = 10) {
        global $current_user, $db, $beanFiles, $beanList;
        $attachments = array();

        if ($GLOBALS['db']->dbType == 'mssql') {
            $attachmentsRes = $db->query("SELECT TOP $lastN qn.*,u.user_name FROM spiceattachments AS qn LEFT JOIN users AS u ON u.id=qn.user_id WHERE qn.bean_id='{$beanId}' AND qn.bean_type='{$beanName}' AND qn.deleted = 0 ORDER BY qn.trdate DESC");
        } else {
            $attachmentsRes = $db->limitQuery("SELECT qn.*,u.user_name FROM spiceattachments AS qn LEFT JOIN users AS u ON u.id=qn.user_id WHERE qn.bean_id='{$beanId}' AND qn.bean_type='{$beanName}' AND qn.deleted = 0 ORDER BY qn.trdate DESC", 0, $lastN);
        }

        if ($GLOBALS['db']->dbType == 'mssql' || $db->getRowCount($attachmentsRes) > 0) {
            while ($thisAttachment = $db->fetchByAssoc($attachmentsRes)) {
                $file = base64_encode(file_get_contents(self::UPLOAD_DESTINATION . $thisAttachment['id']));
                $attachments[] = array(
                    'id' => $thisAttachment['id'],
                    'user_id' => $thisAttachment['user_id'],
                    'user_name' => $thisAttachment['user_name'],
                    'date' => $GLOBALS['timedate']->to_display_date_time($thisAttachment['trdate']),
                    'text' => nl2br($thisAttachment['text']),
                    'filename' => $thisAttachment['filename'],
                    'filesize' => $thisAttachment['filesize'],
                    'file_mime_type' => $thisAttachment['file_mime_type'],
                    'file' => $file
                );
            }
        }

        return json_encode($attachments);
    }

    public static function getAttachmentsCount($lastN = 10) {
        global $current_user, $db;
        $attachmentsRec = $db->fetchByAssoc($db->query("SELECT count(*) AS noteCount FROM spiceattachments WHERE bean_id='{$_REQUEST['record']}' AND bean_type='{$_REQUEST['module']}'  AND deleted = 0"));

        return $attachmentsRec['noteCount'];
    }

    public static function saveAttachment($beanName, $beanId, $post) {
        global $current_user, $db;
        $guid = create_guid();

        require_once('include/upload_file.php');
        $upload_file = new \UploadFile('file');
        if (isset($_FILES['file']) && $upload_file->confirm_upload()) {
            $filename = $upload_file->get_stored_file_name();
            $file_mime_type = $upload_file->mime_type;
            $filesize = $upload_file->get_uploaded_file_size();
            $filemd5 = $upload_file->get_uploaded_file_md5();
            $upload_file->use_proxy = $_FILES['file']['proxy'] ? true : false;
            $upload_file->final_move($filemd5, false);
        }

        // if we have an image create a thumbnail
        $thumbnail = self::createThumbnail($filemd5, $file_mime_type);

        $db->query("INSERT INTO spiceattachments (id, bean_type, bean_id, user_id, trdate, filename, filesize, filemd5, text, thumbnail, deleted, file_mime_type) VALUES ('{$guid}', '{$beanName}', '{$beanId}', '" . $current_user->id . "', '" . gmdate('Y-m-d H:i:s') . "', '{$filename}', '{$filesize}', '{$filemd5}', '{$_POST['text']}', '{$thumbnail}', 0, '{$file_mime_type}')");
        $attachments[] = array(
            'id' => $guid,
            'user_id' => $current_user->id,
            'user_name' => $current_user->user_name,
            'date' => $GLOBALS['timedate']->to_display_date_time(gmdate('Y-m-d H:i:s')),
            'text' => nl2br($_POST['text']),
            'filename' => $filename,
            'filesize' => $filesize,
            'file_mime_type' => $file_mime_type,
            'thumbnail' => $thumbnail,
            'url' => "index.php?module=SpiceThemeController&action=attachment_download&id=" . $guid
        );
        return json_encode($attachments);
    }

    public static function saveAttachmentHashFiles($beanName, $beanId, $post) {
        global $current_user, $db, $sugar_config;
        $guid = create_guid();

        require_once('include/upload_file.php');
        $upload_file = new \UploadFile('file');

        $decodedFile = base64_decode($post['file']);
        $upload_file->set_for_soap($post['filename'], $decodedFile);


        $ext_pos = strrpos($upload_file->stored_file_name, ".");
        $upload_file->file_ext = substr($upload_file->stored_file_name, $ext_pos + 1);
        if (in_array($upload_file->file_ext, $sugar_config['upload_badext'])) {
            $upload_file->stored_file_name .= ".txt";
            $upload_file->file_ext = "txt";
        }

        $filename = $upload_file->get_stored_file_name();
        $file_mime_type = $post['filemimetype'] ?: $upload_file->getMimeSoap($filename);
        $filesize = strlen($decodedFile);
        $filemd5 = md5($decodedFile);

        $upload_file->final_move($filemd5);

        // if we have an image create a thumbnail
        $thumbnail = self::createThumbnail($filemd5, $file_mime_type);

        $db->query("INSERT INTO spiceattachments (id, bean_type, bean_id, user_id, trdate, filename, filesize, filemd5, text, thumbnail, deleted, file_mime_type) VALUES ('{$guid}', '{$beanName}', '{$beanId}', '" . $current_user->id . "', '" . gmdate('Y-m-d H:i:s') . "', '{$filename}', '{$filesize}', '{$filemd5}', '{$post['text']}', '$thumbnail', 0, '{$file_mime_type}')");
        $file = base64_encode(file_get_contents(self::UPLOAD_DESTINATION . $guid));

        $attachments[] = array(
            'id' => $guid,
            'user_id' => $current_user->id,
            'user_name' => $current_user->user_name,
            'date' => $GLOBALS['timedate']->to_display_date_time(gmdate('Y-m-d H:i:s')),
            'text' => nl2br($post['text']),
            'filename' => $filename,
            'filesize' => $filesize,
            'file_mime_type' => $file_mime_type,
            'file' => $file,
            'thumbnail' => $thumbnail,
        );
        return json_encode($attachments);
    }

    /**
     * saveAttachmentLocalFile
     *
     * Adds an attachment that is already a local file to a bean.
     *
     * @param $module_name {string} the name of the module
     * @param $bean_id {string} the id of the bean
     * @param array $file = [name, path, mime_type, file_size, file_md5]
     * @return array
     * @throws \Exception
     */
    public static function saveAttachmentLocalFile($module_name, $bean_id, array $file) {
        global $current_user, $db, $sugar_config;
        $guid = create_guid();

        $ext_pos = strrpos($file['name'], ".");
        $file['file_ext'] = substr($file['name'], $ext_pos + 1);
        if (in_array($file['file_ext'], $sugar_config['upload_badext'])) {
            $file['name'] .= ".txt";
            $file['file_ext'] = "txt";
        }

        $file_name = $file['name'];
        $file_mime_type = $file['mime_type'];
        if (!$file_mime_type) {
            $file_mime_type = mime_content_type($file['path'] . $file['name']);
        }

        $file_size = $file['file_size'];
        if (!$file_size) {
            $file_size = filesize($file['path'] . $file['name']);
        }

        $file_content = file_get_contents($file['path'] . $file['name']);

        $file_name_md5 = $file['file_md5'];
        if (!$file_name_md5) {
            $file_name_md5 = md5($file_content);    // warning: possibility to produce duplicate md5 key for different contents...
        }

        $filePath = self::UPLOAD_DESTINATION.$file_name_md5;
        if (!file_exists($filePath)) {
            $bytes = file_put_contents($filePath, $file_content);
            if (!$bytes) {
                throw new \Exception("Could not save file {$file['name']} to upload://$guid");
            }
        }

        $thumbnail = self::createThumbnail($file_name_md5, $file_mime_type);

        $sql = "INSERT INTO spiceattachments (id, bean_type, bean_id, user_id, trdate, filename, filesize, filemd5, thumbnail, deleted, file_mime_type)
                VALUES ('{$guid}', '{$module_name}', '{$bean_id}', '" . $current_user->id . "', '" . gmdate('Y-m-d H:i:s') . "', '{$file_name}', '{$file_size}', '{$file_name_md5}', '$thumbnail', 0, '{$file_mime_type}')";
        $db->query($sql);

        $attachments = [
            'id' => $guid,
            'user_id' => $current_user->id,
            'user_name' => $current_user->user_name,
            'date' => $GLOBALS['timedate']->to_display_date_time(gmdate('Y-m-d H:i:s')),
            'filename' => $file_name,
            'filesize' => $file_size,
            'file_mime_type' => $file_mime_type,
            'file' => base64_encode(file_get_contents(self::UPLOAD_DESTINATION . $guid)),
            'thumbnail' => $thumbnail,
        ];
        return $attachments;
    }

    public static function saveEmailAttachment($beanName, $beanId, $payload) {
        global $current_user, $db;
        $guid = create_guid();

        // if we have an image create a thumbnail
        $thumbnail = self::createThumbnail($payload->filemd5, $payload->mime_type);

        $db->query("INSERT INTO spiceattachments (id, bean_type, bean_id, user_id, trdate, filename, filesize, filemd5, text, thumbnail, deleted, file_mime_type)
                        VALUES ('{$guid}', '{$beanName}', '{$beanId}', '" . $current_user->id . "', '" . gmdate('Y-m-d H:i:s') . "',
                        '{$payload->filename}', '{$payload->filesize}', '{$payload->filemd5}', '{$_POST['text']}', '{$thumbnail}', 0, '{$payload->mime_type}')");
        $attachments[] = array(
            'id' => $guid,
            'user_id' => $current_user->id,
            'user_name' => $current_user->user_name,
            'date' => $GLOBALS['timedate']->to_display_date_time(gmdate('Y-m-d H:i:s')),
            'text' => nl2br($_POST['text']),
            'filename' => $payload->filename,
            'filesize' => $payload->filesize,
            'file_mime_type' => $payload->file_mime_type,
            'thumbnail' => $thumbnail,
            'url' => "index.php?module=SpiceThemeController&action=attachment_download&id=" . $guid
        );
        return json_encode($attachments);
    }

    public static function saveEmailAttachmentFromGmail($beanName, $beanId, $payload) {
        global $current_user, $db;
        $guid = create_guid();

        $filename = $payload['filename'];
        $filesize = $payload['filesize'];
        $md5 = md5($payload['md5_encoded']);
        $content = $payload['content'];
        $byte_content = self::toByteContent($content);

        // if we have an image create a thumbnail
        $mime_type = $payload['mimetype'];
        //$mime_type = self::experimentalMimeType($filename);
        $thumbnail = self::createThumbnail($md5, $mime_type);

        $query = "INSERT INTO spiceattachments (id, bean_type, bean_id, user_id, trdate, filename, filesize, filemd5, text, thumbnail, deleted, file_mime_type)" .
            " VALUES ('{$guid}', '{$beanName}', '{$beanId}', '" . $current_user->id . "', '" . gmdate('Y-m-d H:i:s') .
            " ', '{$filename}', {$filesize}, '{$md5}', '', '{$thumbnail}', 0, '{$mime_type}')";
        $db->query($query);

        $filepath = 'upload://' . $md5;
        touch($filepath);
        file_put_contents($filepath, $byte_content);

        $attachments[] = array(
            'id' => $guid,
            'user_id' => $current_user->id,
            'user_name' => $current_user->user_name,
            'date' => $GLOBALS['timedate']->to_display_date_time(gmdate('Y-m-d H:i:s')),
            'text' => $payload['content'],
            'filename' => $payload['filename'],
            'filesize' => $payload['filesize'],
            'file_mime_type' => $payload['mimetype'],
            'thumbnail' => $thumbnail,
            'url' => "index.php?module=SpiceThemeController&action=attachment_download&id=" . $guid
        );
        return json_encode($attachments);
    }

    public static function saveEmailAttachmentFromOutlook(\Email $email, OutlookAttachment $attachment) {
        $filepath = 'upload://' . $attachment->fileMd5;
        touch($filepath);

        $byteContent = self::toByteContent($attachment->content);
        file_put_contents($filepath, $byteContent);

        // if we have an image create a thumbnail
        $attachment->thumbnail = self::createThumbnail($attachment->fileMd5, $attachment->fileMimeType);


        return $attachment->save();
    }

    private static function toByteContent($content) {
        return base64_decode($content);
    }

    private static function experimentalMimeType($filename) {
        return strrchr($filename, ".");
    }

    public static function saveRestAttachment($beanName, $beanId, UploadedFile $uploaded_file) {
        global $current_user, $db;
        $guid = create_guid();
        $directory = '';
        $supportedimagetypes = ['jpeg', 'png', 'gif', 'bmp'];

        if ($uploaded_file->getError() === UPLOAD_ERR_OK) {
            $filename = self::moveUploadedFile($directory, $uploaded_file);
            die(var_dump($uploaded_file));
            if (file_exists($uploaded_file->file)) {
                die('file ' . $uploaded_file->file . ' exists');
            } else {
                die('file ' . $uploaded_file->file . ' doesnt exist');
            }
            $filemd5 = md5_file($uploaded_file->file);

            // todo generate thumbnail
            $filetypearray = explode('/', $uploaded_file->getClientMediaType());
            if (count($filetypearray) == 2 && $filetypearray[0] == 'image' && array_search($filetypearray[1], $supportedimagetypes) >= 0) {


                if (list($width, $height) = getimagesize($uploaded_file->file)) {
                    if ($width > $height) {
                        $newwidth = 30;
                        $newheight = round(30 * $height / $width);
                    } else {
                        $newwidth = round(30 * $width / $height);
                        $newheight = 30;
                    }

                    $thumb = imagecreatetruecolor($newwidth, $newheight);

                    // create
                    $createfunction = 'imagecreatefrom' . $filetypearray[1];
                    $source = $createfunction($uploaded_file->file);

                    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    ob_start();
                    imagejpeg($thumb);
                    $thumbnail = base64_encode(ob_get_contents());
                    ob_end_clean();
                    imagedestroy($thumb);
                }


            }
        }

        /*require_once('include/upload_file.php');
        $upload_file = new UploadFile('file');
        if (isset($_FILES['file']) && $upload_file->confirm_upload()) {
            $filename = $upload_file->get_stored_file_name();
            $file_mime_type = $upload_file->mime_type;
            $filesize = $upload_file->get_uploaded_file_size();
            $filemd5 = $upload_file->get_uploaded_file_md5();
            $upload_file->use_proxy = $_FILES['file']['proxy'] ? true : false;
            $upload_file->final_move($filemd5, false);
        }*/

        $db->query(
            "INSERT INTO spiceattachments (
                  id,
                  bean_type,
                  bean_id,
                  user_id,
                  trdate,
                  filename,
                  filesize,
                  filemd5,
                  thumbnail,
                  deleted,
                  file_mime_type
                  ) VALUES (
                  '{$guid}',
                  '{$beanName}',
                  '{$beanId}',
                  '" . $current_user->id . "',
                  '" . gmdate('Y-m-d H:i:s') . "',
                  '" . $uploaded_file->getClientFilename() . "',
                  '" . $uploaded_file->getSize() . "',
                  '{$filemd5}',
                  '{$thumbnail}',
                  0,
                  '" . $uploaded_file->getClientMediaType() . "'
                )"
        );
        $attachments[] = array(
            'id' => $guid,
            'user_id' => $current_user->id,
            'user_name' => $current_user->user_name,
            'date' => $GLOBALS['timedate']->to_display_date_time(gmdate('Y-m-d H:i:s')),
            //'text' => nl2br($_POST['text']),
            'filename' => $uploaded_file->getClientFilename(),
            'filesize' => $uploaded_file->getSize(),
            'file_mime_type' => $uploaded_file->getClientMediaType(),
            'thumbnail' => $thumbnail,
            'url' => "index.php?module=SpiceThemeController&action=attachment_download&id=" . $guid
        );
        return json_encode($attachments);
    }

    public static function deleteAttachment($attachmentId) {
        global $current_user, $db;
        $db->query("UPDATE spiceattachments SET deleted = 1 WHERE id='{$attachmentId}'" . (!$current_user->is_admin ? " AND user_id='" . $current_user->id . "'" : ""));

        // todo: delete also file if MD5 is no longer used anywhere
    }

    public static function getAttachment($attachmentId) {
        global $current_user, $db, $beanFiles, $beanList;
        $attachment = array();

        $attachmentsRes = $db->query("SELECT * FROM spiceattachments WHERE id = '$attachmentId'");

        while ($thisAttachment = $db->fetchByAssoc($attachmentsRes)) {
            $file = base64_encode(file_get_contents(self::UPLOAD_DESTINATION . ($thisAttachment['filemd5'] ?: $thisAttachment['id'])));
            $attachment = array(
                'id' => $thisAttachment['id'],
                'user_id' => $thisAttachment['user_id'],
                'user_name' => $thisAttachment['user_name'],
                'date' => $GLOBALS['timedate']->to_display_date_time($thisAttachment['trdate']),
                'text' => nl2br($thisAttachment['text']),
                'filename' => $thisAttachment['filename'],
                'filesize' => $thisAttachment['filesize'],
                'file_mime_type' => $thisAttachment['file_mime_type'],
                'file' => $file
            );
        }

        return json_encode($attachment);
    }

    public static function downloadAttachment($attachmentId) {
        global $db;

        $query = "SELECT filename, file_mime_type, filemd5, filesize FROM spiceattachments ";
        $query .= "WHERE id= '" . $db->quote($attachmentId) . "'";
        $rs = $GLOBALS['db']->query($query);
        $row = $GLOBALS['db']->fetchByAssoc($rs);
        $download_location = self::UPLOAD_DESTINATION . ($row['filemd5'] ?: $attachmentId);
        $name = $row['name'];
        $mime_type = $row['file_mime_type'];

        // make sure to clean the buffer
        while (ob_get_level() && @ob_end_clean()) ;

        header("Pragma: public");
        header("Cache-Control: maxage=1, post-check=0, pre-check=0");
        header('Content-type: ' . $row['file_mime_type']);
        header("Content-Disposition: attachment; filename=\"" . $row['filename'] . "\";");
        header("X-Content-Type-Options: nosniff");
        header("Content-Length: " . filesize($download_location));
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
        readfile($download_location);
    }

    /**
     * Moves the uploaded file to the upload directory and assigns it a unique name
     * to avoid overwriting an existing uploaded file.
     *
     * @param string $directory directory to which the file is moved
     * @param UploadedFile $uploaded file uploaded file to move
     * @return string filename of moved file
     */
    public static function moveUploadedFile($directory, UploadedFile $uploadedFile) {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    public static function createThumbnail($filename, $mime_type) {
        $supportedimagetypes = ['jpeg', 'png', 'gif'];
        $filetypearray = explode('/', $mime_type);
        if (count($filetypearray) == 2
            && strtolower($filetypearray[0]) == 'image'
            && array_search(strtolower($filetypearray[1]), $supportedimagetypes) >= 0) {
            if (list($width, $height) = getimagesize(self::UPLOAD_DESTINATION . $filename)) {
                if ($width > $height) {
                    $newwidth = 30;
                    $newheight = round(30 * $height / $width);
                } else {
                    $newwidth = round(30 * $width / $height);
                    $newheight = 30;
                }

                $thumb = imagecreatetruecolor($newwidth, $newheight);

                // create
                $createfunction = 'imagecreatefrom' . strtolower($filetypearray[1]);
                $source = $createfunction(self::UPLOAD_DESTINATION . $filename);

                imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                ob_start();
                imagejpeg($thumb);
                $thumbnail = base64_encode(ob_get_contents());
                ob_end_clean();
                imagedestroy($thumb);

                return $thumbnail;
            }
        }

        return '';
    }
}
