<?php

trait ModuleHelper
{
    private $prefix;
    private $archive_id;

    protected $archive_mappings = [];
    protected $profile_mappings = [];
    protected $hidden_mappings = [];
    protected $icon_mappings = [];

    /**
     * apply changes, when settings form has been saved
     */
    public function ApplyChanges()
    {
        parent::ApplyChanges();

        if (IPS_GetKernelRunlevel() == KR_READY && method_exists($this, 'onKernelReady')) {
            $this->onKernelReady();
        }
    }

    /**
     * @param null $sender
     * @param mixed $message
     */
    protected function _log($sender = NULL, $message = '')
    {
        if ($this->ReadPropertyBoolean('log')) {
            if (is_array($message)) {
                $message = json_encode($message);
            }
            IPS_LogMessage($sender, $message);
        }
    }

    /**
     * creates an instance by identifier
     * @param int $module_id
     * @param int $parent_id
     * @param string $identifier
     * @param string $name
     * @return mixed
     */
    protected function CreateInstanceByIdentifier($module_id, $parent_id, $identifier, $name = null)
    {
        // if kernel is not ready, don't create any instances
        if (IPS_GetKernelRunlevel() != KR_READY) {
            return false;
        }

        // set name by identifier, if no name was provided
        if (!$name) {
            $name = $identifier;
        }

        // set identifier
        $identifier = $this->identifier($identifier);

        // get instance id, if exists
        $instance_id = @IPS_GetObjectIDByIdent($identifier, $parent_id);

        // if instance doesn't exist, create it!
        if ($instance_id === false) {
            $instance_id = IPS_CreateInstance($module_id);
            IPS_SetParent($instance_id, $parent_id);
            IPS_SetName($instance_id, $this->Translate($name));
            IPS_SetIdent($instance_id, $identifier);
        }

        // return instance id
        return $instance_id;
    }

    /**
     * creates a category by identifier
     * @param int $parent_id
     * @param string $identifier
     * @param string $name
     * @param string $icon
     * @return mixed
     */
    protected function CreateCategoryByIdentifier($parent_id, $identifier, $name = null, $icon = null)
    {
        // set name by identifier, if no name was provided
        if (!$name) {
            $name = $identifier;
        }

        // set identifier
        $identifier = $this->identifier($identifier);

        // get category id, if exists
        $category_id = @IPS_GetObjectIDByIdent($identifier, $parent_id);

        // if category doesn't exist, create it!
        if ($category_id === false) {
            $category_id = IPS_CreateCategory();
            IPS_SetParent($category_id, $parent_id);
            IPS_SetName($category_id, $this->Translate($name));
            IPS_SetIdent($category_id, $identifier);

            if ($icon) {
                IPS_SetIcon($category_id, $icon);
            }
        }

        // return category id
        return $category_id;
    }

    /**
     * creates a variable by identifier or updates its data
     * @param array $options
     * @return mixed
     */
    protected function CreateVariableByIdentifier($options)
    {

        // set options
        /**
         * @var $parent_id integer
         * @var $name string
         * @var $value mixed
         * @var $identifier string
         * @var $position integer
         * @var $custom_profile boolean|array
         * @var $icon boolean|string
         */
        $defaults = [
            'parent_id' => 0,
            'name' => NULL,
            'value' => NULL,
            'identifier' => false,
            'position' => 0,
            'custom_profile' => false,
            'icon' => false
        ];

        // get options
        $options = array_merge($defaults, $options);
        extract($options);

        // remove whitespaces
        $name = trim($name);
        if (is_string($value)) {
            $value = trim($value);
        }

        // set identifier
        $has_identifier = true;
        if (!$identifier) {
            $has_identifier = false;
            $identifier = $name;
        }
        $identifier = $this->identifier($identifier);

        // get archive id
        if (!$this->archive_id) {
            $this->archive_id = $this->_getArchiveId();
        }

        // get variable id, if exists
        $variable_created = false;
        $variable_id = @IPS_GetObjectIDByIdent($identifier, $parent_id);

        // set type of variable
        $type = $this->GetVariableType($value);

        // if variable doesn't exist, create it!
        if ($variable_id === false) {
            $variable_created = true;

            // create variable
            $variable_id = IPS_CreateVariable($type);
            IPS_SetParent($variable_id, $parent_id);
            IPS_SetIdent($variable_id, $identifier);

            // hide visibility
            if (in_array($name, $this->hidden_mappings)) {
                IPS_SetHidden($variable_id, true);
            }

            // enable archive
            if (isset($this->archive_mappings[$name])) {
                AC_SetLoggingStatus($this->archive_id, $variable_id, true);
                AC_SetAggregationType($this->archive_id, $variable_id, (int)$this->archive_mappings[$name]);
                IPS_ApplyChanges($this->archive_id);
            }

            // set profile by name mapping
            if (!$custom_profile && isset($this->profile_mappings[$name])) {
                // attach module prefix to custom profile name
                $profile_id = substr($this->profile_mappings[$name], 0, 1) == '~'
                    ? $this->profile_mappings[$name]
                    : $this->_getPrefix() . '.' . $this->profile_mappings[$name];

                // create profile, if not exists
                if (!IPS_VariableProfileExists($profile_id)) {
                    $this->CreateCustomVariableProfile($profile_id, $this->profile_mappings[$name]);
                }

                IPS_SetVariableCustomProfile($variable_id, $profile_id);
            }
        }

        // set custom profile
        if ($custom_profile) {
            $profile_id = is_string($custom_profile)
                ? $custom_profile
                : (isset($custom_profile['id'])
                    ? $custom_profile['id']
                    : str_replace($this->_getPrefix() . '_', $this->_getPrefix() . '.', $identifier));

            if (is_array($custom_profile)) {
                if (!IPS_VariableProfileExists($profile_id)) {
                    IPS_CreateVariableProfile($profile_id, $type);
                }

                if (isset($custom_profile['icon'])) {
                    IPS_SetVariableProfileIcon($profile_id, $custom_profile['icon']);
                }

                if (isset($custom_profile['prefix']) || isset($custom_profile['suffix'])) {
                    $prefix = isset($custom_profile['prefix']) ? $custom_profile['prefix'] : '';
                    $suffix = isset($custom_profile['suffix']) ? $custom_profile['suffix'] : '';
                    IPS_SetVariableProfileText($profile_id, $prefix, $suffix);
                }

                if (isset($custom_profile['steps'])) {
                    $min = isset($custom_profile['min']) ? $custom_profile['min'] : 0;
                    $max = isset($custom_profile['max']) ? $custom_profile['max'] : 100;
                    $steps = isset($custom_profile['steps']) ? $custom_profile['steps'] : 1;
                    IPS_SetVariableProfileValues($profile_id, $min, $max, $steps);
                }

                if (isset($custom_profile['values']) && is_array($custom_profile['values'])) {
                    foreach ($custom_profile['values'] AS $profile_value => $profile_name) {
                        $custom_profile['icon'] = isset($custom_profile['icon']) ? $custom_profile['icon'] : '';
                        IPS_SetVariableProfileAssociation($profile_id, $profile_value, $this->Translate($profile_name), $custom_profile['icon'], -1);
                    }
                }
            }

            IPS_SetVariableCustomProfile($variable_id, $profile_id);
        }

        // set name & position
        if ($variable_created || $has_identifier) {
            IPS_SetName($variable_id, $this->Translate($name));
        }

        if ($position) {
            IPS_SetPosition($variable_id, $position);
        }

        // set icon
        if ($icon) {
            IPS_SetIcon($variable_id, $icon);
        } else if (isset($this->icon_mappings[$name])) {
            IPS_SetIcon($variable_id, $this->icon_mappings[$name]);
        }

        // set value
        SetValue($variable_id, $value);

        // return variable id
        return $variable_id;
    }

    /**
     * creates a link, if not exists
     * @param int $parent_id
     * @param int $target_id
     * @param string $name
     * @param int $position
     * @param string $icon
     * @return bool|int
     */
    protected function CreateLink($parent_id, $target_id, $name, $position = 0, $icon = null)
    {
        $link_id = false;

        // detect already created links
        $links = IPS_GetLinkList();
        foreach ($links AS $link) {
            $parent_link_id = IPS_GetParent($link);
            $link = IPS_GetLink($link);

            if ($parent_link_id == $parent_id && $link['TargetID'] == $target_id) {
                $link_id = $link['LinkID'];
                break;
            }
        }

        // if link doesn't exist, create it!
        if ($link_id === false) {
            // create link
            $link_id = IPS_CreateLink();
            IPS_SetName($link_id, $this->Translate($name));
            IPS_SetParent($link_id, $parent_id);

            // set target id
            IPS_SetLinkTargetID($link_id, $target_id);

            // hide visibility
            if (in_array($name, $this->hidden_mappings)) {
                IPS_SetHidden($link_id, true);
            }

            // set icon
            if ($icon) {
                IPS_SetIcon($link_id, $icon);
            }
        }

        // set position
        IPS_SetPosition($link_id, $position);

        return $link_id;
    }

    /**
     * create custom variable profile
     * @param string $profile_id
     * @param string $name
     */
    protected function CreateCustomVariableProfile(string $profile_id, string $name)
    {
        /**
         * will be overwritten by each module.php
         */
    }

    /**
     * get variable type by contents
     * @param $value
     * @return int
     */
    private function GetVariableType($value)
    {
        if (is_bool($value)) {
            $type = 0;
        } else if (is_float($value)) {
            $type = 2;
        } else if (is_int($value) || (intval($value) === $value)) {
            $type = 1;
        } else {
            $type = 3;
        }

        return $type;
    }

    /**
     * replaces special chars for identifier
     * @param $identifier
     * @return mixed
     */
    protected function identifier($identifier)
    {
        $identifier = strtr($identifier, [
            '-' => '_',
            ' ' => '_',
            ':' => '_',
            '%' => 'p'
        ]);

        $identifier = preg_replace('/[^A-Za-z0-9\.\_]/', '', $identifier);

        // @depreciated fallback
        $old_prefixes = [
            'DC' => 'Discovergy',
            'GF' => 'GetFresh',
            'OF' => 'Oilfox',
            'SMA' => 'SMAModbus',
            'HC' => 'HomeConnectDevice',
            'HCIO' => 'HomeConnect',
            'NC' => 'NetatmoCamera',
            'UNIFI' => 'Unifi'
        ];

        foreach ($old_prefixes AS $old_prefix => $new_prefix) {
            if ($this->_getPrefix() === $new_prefix) {
                if ($old_variable_id = @$this->GetIdForIdentRecursive($old_prefix . '_' . $identifier)) {
                    if ($wrong_variable_id = @$this->GetIdForIdentRecursive($new_prefix . '_' . $identifier)) {
                        $object = IPS_GetObject($wrong_variable_id);
                        $ids_to_delete = $object['ChildrenIDs'];
                        $ids_to_delete[] = $wrong_variable_id;

                        foreach ($ids_to_delete AS $id) {
                            $obj = IPS_GetObject($id);

                            if ($obj['ObjectType']) {
                                IPS_DeleteVariable($id);
                            } else {
                                IPS_DeleteCategory($id);
                            }
                        }

                    }

                    IPS_SetIdent($old_variable_id, $new_prefix . '_' . $identifier);
                }
            }
        }

        return $this->_getPrefix() . '_' . $identifier;
    }

    /**
     * find id by ident recursive
     * @param string $Ident
     * @return bool|int
     */
    protected function GetIdForIdentRecursive(string $Ident)
    {
        if ($id = $this->GetIdForIdent($Ident)) {
            return $id;
        }

        $object = IPS_GetObject($this->InstanceID);
        foreach ($object['ChildrenIDs'] AS $children_id) {
            if ($id = IPS_GetObjectIDByIdent($Ident, $children_id)) {
                return $id;
            }
        }

        return false;
    }

    /**
     * get url of connect module
     * @return string
     */
    protected function _getConnectURL()
    {
        $connect_instances = IPS_GetInstanceListByModuleID('{9486D575-BE8C-4ED8-B5B5-20930E26DE6F}');

        // get connect module
        if ($connect_id = $connect_instances[0]) {
            $connect_url = CC_GetURL($connect_id);
            if (strlen($connect_url) > 10) {
                // remove trailing slash
                if (substr($connect_url, -1) == '/') {
                    $connect_url = substr($connect_url, 0, -1);
                }
                // return connect url
                return $connect_url;
            }
        }

        // fallback
        return 'e.g. https://symcon.domain.com';
    }

    /**
     * get location module instance id
     * @return int
     */
    protected function _getLocationId()
    {
        $location_instances = IPS_GetInstanceListByModuleID('{45E97A63-F870-408A-B259-2933F7EABF74}');
        return $location_instances[0];
    }

    /**
     * get archive module instance id
     * @return int
     */
    protected function _getArchiveId()
    {
        $archive_instances = IPS_GetInstanceListByModuleID('{43192F0B-135B-4CE7-A0A7-1475603F3060}');
        return $archive_instances[0];
    }

    /**
     * get ips locale
     * thanks to @patami on https://www.symcon.de/forum/threads/34361-Aktuelle-Sprache-ermitteln?p=321855#post321855
     * @return string
     */
    protected function _getIpsLocale()
    {
        // Get info about the system default ~Switch variable profile
        $info = @IPS_GetVariableProfile('~Switch');

        // Get the display text of the OFF option
        $name = $info['Associations'][0]['Name'];

        // Check if the locale is German, otherwise English
        $locale = ($name == 'Aus') ? 'de-DE' : 'en-US';

        // Return the value
        return $locale;
    }

    /**
     * get identifier by needle
     * @param $needle
     * @return array
     */
    protected function _getIdentifierByNeedle($needle)
    {
        $needle = str_replace(' ', '_', $needle);

        $idents = [];
        if ($needle) {
            foreach (IPS_GetChildrenIDs($this->InstanceID) AS $object_ids) {
                $object = IPS_GetObject($object_ids);

                if (strstr($object['ObjectIdent'], '_' . $needle)) {
                    $idents[] = $object['ObjectIdent'];
                }
            }
        }

        return $idents;
    }

    /**
     * get unit system by locale
     * @return string
     */
    protected function _getIpsUnits()
    {
        $locale = $this->_getIpsLocale();
        return in_array($locale, ['en-US', 'en-GB']) ? 'imperial' : 'metric';
    }

    /**
     * get prefix by current class name
     * @return string
     */
    protected function _getPrefix()
    {
        return get_class($this);
    }

}