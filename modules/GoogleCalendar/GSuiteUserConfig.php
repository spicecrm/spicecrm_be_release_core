<?php
namespace SpiceCRM\modules\GoogleCalendar;

class GSuiteUserConfig
{
    public $id;
    public $user_id;
    public $calendar_settings;
    public $beanMappings = [];
    public $fillable = ['id', 'user_id', 'calendar_settings'];
    protected $table = 'sysgsuiteuserconfig';

    public function __construct($user_id) {
        if ($user_id == '') {
            throw new \Exception('No User ID given.');
        }

        $this->user_id = $user_id;

        global $db;
        $query = "SELECT * FROM sysgsuiteuserconfig WHERE user_id = '"
                . filter_var($this->user_id, FILTER_SANITIZE_STRING) . "'";
        $q = $db->query($query);

        $result = $db->fetchByAssoc($q);

        foreach ($result as $attribute => $value) {
            if (in_array($attribute, $this->fillable)) {
                $this->$attribute = $value;
            }
        }

        if ($this->id == null) {
            $this->id = $this->generateUUID();
        }

        $this->initializeCalendarSettings();
    }

    public static function getCurrentUserConfig() {
        global $current_user;

        return new GSuiteUserConfig($current_user->id);
    }

    public function saveBeanMappings($mappings = []) {
        foreach ($mappings as $mapping) {
            if ($mapping['deleted']) {
                unset($this->beanMappings[$mapping['id']]);
            } else {
                $this->beanMappings[$mapping['id']] = $mapping;
            }
        }

        $this->save();
    }

    public function save() {
        $this->serializeCalendarSettings();
        if ($this->exists()) {
            $this->update();
        } else {
            $this->insert();
        }

    }

    public function getCalendarForBean($beanClass) {
        foreach ($this->beanMappings as $mapping) {
            if ($mapping['bean'] == $beanClass) {
                return $mapping['calendar'];
            }
        }

        return 'primary';
    }

    public function getBeanForCalendar($calendar) {
        foreach ($this->beanMappings as $mapping) {
            if ($mapping['calendar'] == $calendar) {
                return $mapping['bean'];
            }
        }

        return null;
    }

    private function initializeCalendarSettings() {
        $settings = json_decode(html_entity_decode($this->calendar_settings), true);

        if ($settings != null) {
            foreach ($settings as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    private function serializeCalendarSettings() {
        $this->calendar_settings = json_encode([
            'beanMappings' => $this->beanMappings,
        ]);
    }

    private function exists() {
        global $db;

        $query = "SELECT COUNT(*) as 'count' FROM sysgsuiteuserconfig WHERE user_id = '"
            . filter_var($this->user_id, FILTER_SANITIZE_STRING) . "'";
        $q = $db->query($query);

        $result = $db->fetchByAssoc($q);

        if ($result['count'] == 1) {
            return true;
        }

        return false;

    }

    private function update() {
        global $db;

        $values = [];

        foreach ($this->fillable as $attribute) {
            array_push($values, "`" . $attribute . "` = '" . $this->$attribute . "'");
        }

        $query = "UPDATE " . $this->table . " SET " . implode(',', $values)
                . " WHERE user_id = '" . $this->user_id . "'";

        $q = $db->query($query);
        $result = $db->fetchByAssoc($q);

        return $result;
    }

    private function insert() {
        global $db;

        $values = [];

        foreach ($this->fillable as $attribute) {
            array_push($values, "'" . $this->$attribute . "'");
        }

        $query = "INSERT INTO " . $this->table . "(" . implode(',', $this->fillable) . ")"
                . " VALUES (" . implode(',', $values) . ")";

        $q = $db->query($query);
        $result = $db->fetchByAssoc($q);

        return $result;
    }

    public function generateUUID() {
        $uuid = md5(uniqid(rand(), true));
        $guid =  substr($uuid,0,8)."-".
            substr($uuid,8,4)."-".
            substr($uuid,12,4)."-".
            substr($uuid,16,4)."-".
            substr($uuid,20,12);
        return $guid;
    }
}
