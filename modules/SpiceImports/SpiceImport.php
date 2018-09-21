<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * twentyreasons SpiceImport
 * @author Stefan Wölflinger (twentyreasons)
 */
require_once('include/SugarObjects/templates/basic/Basic.php');
require_once('include/utils.php');

class SpiceImport extends SugarBean
{
    //Sugar vars
    var $table_name = "spiceimports";
    var $object_name = "SpiceImport";
    var $new_schema = true;
    var $module_dir = "SpiceImports";
    var $id;
    var $date_entered;
    var $date_modified;
    var $assigned_user_id;
    var $modified_user_id;
    var $created_by;
    var $created_by_name;
    var $modified_by_name;
    var $description;
    var $name;
    var $data;

    var $objectimport;

    function __construct()
    {
        parent::__construct();
    }

    function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    function get_summary_text()
    {
        return $this->name;
    }

    public static function saveImportFiles($properties)
    {

        global $current_user, $db;
        $guid = create_guid();
        $delimiter = ($properties['separator'] == 'comma') ? ',' : ';';
        $enclosure = chr(8);

        switch ($properties['enclosure']) {
            case 'single':
                $enclosure = "'";
                break;
            case 'double':
                $enclosure = '"';
                break;
        }

        require_once('include/upload_file.php');
        $upload_file = new UploadFile('file');

        if (isset($_FILES['file']) && $upload_file->confirm_upload()) {
            $filename = $upload_file->get_stored_file_name();
            $file_mime_type = $upload_file->mime_type;
            $filesize = $upload_file->get_uploaded_file_size();
            $filemd5 = $upload_file->get_uploaded_file_md5();
            $upload_file->use_proxy = $_FILES['file']['proxy'] ? true : false;
            $upload_file->final_move($filemd5, false);
        } else {
            $errorMsg = $upload_file->get_upload_error();
            return json_encode(array('status' => 'error', 'data' => $errorMsg));
        }

        $row = 0;
        $fileData = Array();
        $fileTooBig = false;
        global $sugar_config;
        $file = file("upload://" . $filemd5);
        $import_max_records_per_file = (isset($sugar_config['import_max_records_per_file']) ? $sugar_config['import_max_records_per_file'] : 50);

        if (count($file) > $import_max_records_per_file)
            $fileTooBig = true;

        if (($handle = fopen("upload://" . $filemd5, "r")) !== FALSE) {
            $fileHeader = fgetcsv($handle, 0, $delimiter, $enclosure);
            file_put_contents($file, preg_replace('{(.)\1+}', '$1', $handle), file_get_contents($file));
            while (($data = fgetcsv($handle, 0, $delimiter, $enclosure)) !== FALSE) {
                if (array(null) !== $data && count(array_filter($data)) == count($data)) {
                    if ($row < 2)
                        $fileData[] = $data;
                    $row++;
                }
            }
            fclose($handle);
        }

        $attachments[] = array(
            'id' => $guid,
            'user_id' => $current_user->id,
            'user_name' => $current_user->user_name,
            'date' => $GLOBALS['timedate']->to_display_date_time(gmdate('Y-m-d H:i:s')),
            'text' => nl2br($_POST['text']),
            'filename' => $filename,
            'filesize' => $filesize,
            'filemd5' => $filemd5,
            'file_mime_type' => $file_mime_type,
            'fileheader' => $fileHeader,
            'filedata' => $fileData,
            'filerows' => $row
        );

        return json_encode(array('files' => $attachments, 'fileTooBig' => $fileTooBig));
    }

    public function deleteImportFile($filemd5)
    {
        if (!unlink("upload://" . $filemd5)) {
            return json_encode(array('status' => 'File cant be deleted'));
        } else {
            return json_encode(array('status' => 'succeed'));
        }
    }

    function mark_deleted($id)
    {
        global $db;

        $data = json_decode($this->data);
        $filemd5 = $data->fileId;

        $beanList = $this->get_list("", " data like '%$filemd5%' ");

        if (count($beanList['list']) == 1)
            $this->deleteImportFile($filemd5);

        parent::mark_deleted($id);

        $query = $db->query("DELETE FROM spiceimportlogs WHERE import_id = '$id'");
        if (!$query)
            return false;

        return true;

    }

    public function getSavedImports($module)
    {
        global $db;
        $imports = array();
        $savedImports = $db->query("SELECT * FROM spiceimporttemplates WHERE module = '$module' ORDER BY name");
        while ($savedImport = $db->fetchByAssoc($savedImports)) {
            $savedImport['mappings'] = json_decode(str_replace(array("\r", "\n", "&#039;"), array('', '', '"'), html_entity_decode($savedImport['mappings'], ENT_QUOTES)), true) ?: array();
            $savedImport['fixed'] = json_decode(str_replace(array("\r", "\n", "&#039;"), array('', '', '"'), html_entity_decode($savedImport['fixed'], ENT_QUOTES)), true) ?: array();
            $savedImport['checks'] = json_decode(str_replace(array("\r", "\n", "&#039;"), array('', '', '"'), html_entity_decode($savedImport['checks'], ENT_QUOTES)), true) ?: array();

            $imports[] = $savedImport;
        }

        return json_encode($imports);
    }

    function saveFromImport($data)
    {
        global $current_user;
        $this->objectimport = json_decode($data['objectimport']);
        $this->data = $data['objectimport'];
        $this->module = $this->objectimport->module;
        $this->name = $this->objectimport->module . "_" . gmdate('Y-m-d H:i:s');
        $this->assigned_user_id = $current_user->id;

        if (isset($this->objectimport->templateName))
            $this->saveTemplate();

        if ($this->objectimport->fileTooBig) {
            $this->status = 'q';
            parent::save();
            return json_encode(array('status' => 'scheduled', 'msg' => 'Import has been scheduled'));
        } else {
            $this->status = 'i';
            parent::save();
            return $this->process();
        }
    }

    public function process()
    {
        $decodedData = json_decode($this->data);
        $this->objectimport = $decodedData;
        $error = false;
        $list = array();
        $delimiter = ($this->objectimport->separator == 'comma') ? ',' : ';';
        $enclosure = chr(8);

        switch ($this->objectimport->enclosure) {
            case 'single':
                $enclosure = "'";
                break;
            case 'double':
                $enclosure = '"';
                break;
        }

        if (($handle = fopen("upload://" . $this->objectimport->fileId, "r")) !== FALSE) {

            $fileHeader = fgetcsv($handle, 1000, $delimiter, $enclosure);

            while (($row = fgetcsv($handle, 1000, $delimiter, $enclosure)) !== FALSE) {

                if (array(null) !== $row && count(array_filter($row)) == count($row)) {

                    $retrieve = array();

                    foreach ($this->objectimport->checkFields as $check_field)
                        $retrieve[$check_field->moduleField] = $row[array_search($check_field->mappedField, $fileHeader)];

                    require_once('data/BeanFactory.php');
                    $newBean = BeanFactory::getBean($this->objectimport->module);

                    switch ($this->objectimport->importAction) {
                        case 'update':
                            $this->updateExistingRecord($fileHeader, $newBean, $row, $retrieve, $error, $list);
                            break;
                        case 'new':
                            $this->createNewRecord($newBean, $row, $fileHeader, $error, $list);
                            break;
                    }
                }
            }

            fclose($handle);

            if ($error)
                $this->status = 'e';
            else
                $this->status = 'c';

            $this->save();

        } else {

            $sql = "INSERT INTO spiceimportlogs (id, import_id, msg, data) VALUES (UUID(), '" . $this->id . "', 'Cant open file', 'upload://" . $this->objectimport->fileId . "')";
            $this->db->query($sql);
            $this->status = 'e';
            $this->save();
            return json_encode(array('status' => 'error', 'list' => $list, 'import_id' => $this->id, 'msg' => 'Cant open file ' . $this->objectimport->fileName));
        }

        return json_encode(array('status' => 'imported', 'list' => $list, 'import_id' => $this->id));
    }


    function createNewRecord($newBean, $row, $fileHeader, &$error, &$list)
    {

        if ($this->objectimport->idFieldAction == 'have') {
            $id = $row[array_search($this->objectimport->idFIeld, $fileHeader)];
            $newBean->retrieve($id);
        }

        if (empty($newBean->id)) {

            foreach ($row as $idx => $col) {

                if (isset($this->objectimport->fileMapping->{$fileHeader[$idx]})) {
                    $newBean->{$this->objectimport->fileMapping->{$fileHeader[$idx]}} = $col;

                    if ($this->objectimport->idFieldAction == 'have' &&
                        $this->objectimport->idField == $this->objectimport->fileMapping->{$fileHeader[$idx]}) {

                        $newBean->new_with_id = true;
                        $newBean->id = $col;
                    }
                }
            }

            foreach ($this->objectimport->fixedFields as $field)
                $newBean->{$field->field} = $this->objectimport->fixedFieldsValues->{$field->field};

            $newBeanId = $newBean->save();
            $list[] = array('status' => 'imported', 'recordId' => $newBeanId, 'data' => array($row[0], $row[1], $row[2], $row[3]));

            if ($this->objectimport->importDuplicateAction == 'log') {
                $dupRecs = $newBean->checkForDuplicates();

                if (count($dupRecs) > 0) {
                    $error = true;
                    $newBeanId = $newBean->save();
                    $GLOBALS['log']->debug('SpiceImports saved id ' . $newBeanId);
                    $sql = "INSERT INTO spiceimportlogs (id, import_id, msg, data) VALUES (UUID(), '" . $this->id . "', '" . 'Duplicate Entry' . "', '" . implode('";"', $row) . "')";
                    $list[] = array('status' => 'Duplicate Entry', 'data' => array($row[0], $row[1], $row[2], $row[3]));
                    $this->db->query($sql);
                }
            }
        } else {
            $sql = "INSERT INTO spiceimportlogs (id, import_id, msg, data) VALUES (UUID(), '" . $this->id . "', 'Record Exists', '" . implode('";"', $row) . "')";
            $error = true;
            $list[] = array('status' => 'Record Exists', 'data' => array($row[0], $row[1], $row[2], $row[3]));
            $this->db->query($sql);
        }
    }

    function updateExistingRecord($fileHeader, $newBean, $row, $retrieve, &$error, &$list)
    {
        $newBean->retrieve_by_string_fields($retrieve);

        if (!empty($newBean->id)) {
            foreach ($row as $idx => $col) {
                if (!empty($this->objectimport->fileMapping->{$fileHeader[$idx]}))
                    $newBean->{$this->objectimport->fileMapping->{$fileHeader[$idx]}} = $col;
            }

            foreach ($this->objectimport->fixedFields as $field)
                $newBean->{$field->field} = $this->objectimport->fixedFieldsValues->{$field->field};

            $newBeanId = $newBean->save();
            $GLOBALS['log']->debug('SpiceImports saved id ' . $newBeanId);
            $list[] = array('status' => 'updated', 'recordId' => $newBeanId, 'data' => array($row[0], $row[1], $row[2], $row[3]));
        } else {
            $sql = "INSERT INTO spiceimportlogs (id, import_id, msg, data) VALUES (UUID(), '" . $this->id . "', 'No Entries', '" . implode('";"', $row) . "')";
            $error = true;
            $list[] = array('status' => 'No Entries', 'data' => array($row[0], $row[1], $row[2], $row[3]));
            $this->db->query($sql);
        }

    }

    function saveTemplate()
    {
        $spiceImportTemplates = BeanFactory::newBean("SpiceImportTemplates");
        if ($spiceImportTemplates) {
            $spiceImportTemplates->name = $this->objectimport->templateName;
            $spiceImportTemplates->module = $this->objectimport->module;
            $spiceImportTemplates->mappings = json_encode($this->objectimport->fileMapping);
            $spiceImportTemplates->fixed = json_encode($this->objectimport->fixedFields);
            if ($this->objectimport->importAction == 'update')
                $spiceImportTemplates->checks = json_encode($this->objectimport->checkFields);

            $spiceImportTemplates->save();
        }
    }

}