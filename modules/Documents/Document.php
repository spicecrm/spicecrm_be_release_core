<?php
if(!defined('sugarEntry') || !sugarEntry)
	die('Not A Valid Entry Point');
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

require_once ('include/upload_file.php');


// User is used to store Forecast information.
class Document extends SugarBean
{

	var $table_name = "documents";
	var $object_name = "Document";
	var $module_dir = 'Documents';

	var $relationship_fields = Array(
		'contract_id'=>'contracts',
	 );


	function __construct()
    {
		parent::__construct();
		$this->disable_row_level_security = false;
	}

	function save($check_notify = false, $fts_index_bean = true)
    {
        if (empty($this->doc_type)) {
			$this->doc_type = 'Sugar';
		}
        if (empty($this->id) || $this->new_with_id)
		{
            if (empty($this->id)) {
                $this->id = create_guid();
                $this->new_with_id = true;
            }


           $isDuplicate = false;

            $Revision = new DocumentRevision();
            //save revision.
            $Revision->in_workflow = true;
            $Revision->not_use_rel_in_req = true;
            $Revision->new_rel_id = $this->id;
            $Revision->new_rel_relname = 'Documents';
            $Revision->change_log = translate('DEF_CREATE_LOG','Documents');
            $Revision->revision = $this->revision;
            $Revision->document_id = $this->id;
            $Revision->filename = $this->filename;
            $Revision->assigned_user_id = $this->assigned_user_id;
            $Revision->created_by = $this->created_by;


            if(isset($this->file_ext))
            {
            	$Revision->file_ext = $this->file_ext;
            }

            if(isset($this->file_mime_type))
            {
            	$Revision->file_mime_type = $this->file_mime_type;
            }

            $Revision->doc_type = $this->doc_type;
            if ( isset($this->doc_id) ) {
                $Revision->doc_id = $this->doc_id;
            }
            if ( isset($this->doc_url) ) {
                $Revision->doc_url = $this->doc_url;
            }

            $Revision->id = create_guid();
            $Revision->new_with_id = true;

            $createRevision = false;
            //Move file saved during populatefrompost to match the revision id rather than document id
            if (!empty($_FILES['filename_file'])) {
                //make sure we have a filename
                if(empty($Revision->filename)) $Revision->filename = $_FILES['filename_file']['name'];
                rename("upload://{$this->id}", "upload://{$Revision->id}");
                $createRevision = true;
            } else if(!empty($this->filename) && file_exists("upload://{$this->id}")){
                $old_name = "upload://{$this->id}";
                $new_name = "upload://{$Revision->id}";
                copy($old_name, $new_name);
                $this->name = $this->document_name;
            }

            // For external documents, we just need to make sure we have a doc_id
            //if ( !empty($this->doc_id) && $this->doc_type != 'Sugar' ) {
                $createRevision = true;
            //}

            if ( $createRevision ) {
                $Revision->save();
                //update document with latest revision id
                $this->process_save_dates=false; //make sure that conversion does not happen again.
                $this->document_revision_id = $Revision->id;
            }


        }

		return parent::save($check_notify, $fts_index_bean);
	}
	function get_summary_text() {
		return "$this->document_name";
	}

	function is_authenticated() {
		return $this->authenticated;
	}

	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields() {
		global $theme;
		global $current_language;
		global $timedate;
		global $locale;

		parent::fill_in_additional_detail_fields();

        if (!empty($this->document_revision_id)) {

            $query = "SELECT users.first_name AS first_name, users.last_name AS last_name, document_revisions.date_entered AS rev_date,
            	 document_revisions.filename AS filename, document_revisions.revision AS revision,
            	 document_revisions.file_ext AS file_ext, document_revisions.file_mime_type AS file_mime_type
            	 FROM users, document_revisions
            	 WHERE users.id = document_revisions.created_by AND document_revisions.id = '$this->document_revision_id'";

            $result = $this->db->query($query);
            $row = $this->db->fetchByAssoc($result);

            //populate name
            if(isset($this->document_name))
            {
            	$this->name = $this->document_name;
            }

            if(isset($row['filename']))$this->filename = $row['filename'];
            if(isset($row['file_mime_type']))$this->file_mime_type = $row['file_mime_type'];
            //$this->latest_revision = $row['revision'];
            if(isset($row['revision'])){
                $this->revision = $row['revision'];
                $this->next_revision = $row['revision'] + 1;
            }

        }


		//get last_rev_by user name.
		if (!empty ($row)) {
			$this->last_rev_created_name = $locale->getLocaleFormattedName($row['first_name'], $row['last_name']);

			$this->last_rev_create_date = $this->db->fromConvert($row['rev_date'], 'datetime');
			$this->last_rev_mime_type = $row['file_mime_type'];
		}

		global $app_list_strings;
	    if(!empty($this->status_id)) {
	       //_pp($this->status_id);
	       $this->status = $app_list_strings['document_status_dom'][$this->status_id];
	    }
        if (!empty($this->related_doc_id)) {
            $this->related_doc_name = Document::get_document_name($this->related_doc_id);
            $this->related_doc_rev_number = DocumentRevision::get_document_revision_name($this->related_doc_rev_id);
        }
	}


    /**
     * mark_relationships_deleted
     *
     * Override method from SugarBean to handle deleting relationships associated with a Document.  This method will
     * remove DocumentRevision relationships and then optionally delete Contracts depending on the version.
     *
     * @param $id String The record id of the Document instance
     */
	function mark_relationships_deleted($id)
    {
        $this->load_relationships('revisions');
       	$revisions= $this->get_linked_beans('revisions','DocumentRevision');

       	if (!empty($revisions) && is_array($revisions)) {
       		foreach($revisions as $key=>$version) {
       			UploadFile::unlink_file($version->id,$version->filename);
       			//mark the version deleted.
       			$version->mark_deleted($version->id);
       		}
       	}

	}

	//static function.
	function get_document_name($doc_id){
		if (empty($doc_id)) return null;

		$db = DBManagerFactory::getInstance();
		$query="select document_name from documents where id='$doc_id'  and deleted=0";
		$result=$db->query($query);
		if (!empty($result)) {
			$row=$db->fetchByAssoc($result);
			if (!empty($row)) {
				return $row['document_name'];
			}
		}
		return null;
	}
}


