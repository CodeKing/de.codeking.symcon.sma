<?php

/**
 * IPS Constants Loader
 */
require_once(__DIR__ . '/ips.constants.php');

class Module extends IPSModule
{
    use ModuleHelper;

    protected $force_ident = false;

    /**
     * create instance
     * @return bool|void
     */
    public function Create()
    {
        parent::Create();

        // register global properties
        $this->RegisterPropertyBoolean('log', true);

        // register kernel messages
        $this->RegisterMessage(0, IPS_KERNELMESSAGE);
    }

    /**
     * destroy instance
     * @return bool|void
     */
    public function Destroy()
    {
        parent::Destroy();

        // remove instance profiles
        $profiles = IPS_GetVariableProfileList();
        foreach ($profiles AS $profile) {
            if (strstr($profile, $this->prefix . '.' . $this->InstanceID)) {
                IPS_DeleteVariableProfile($profile);
            }
        }
    }

    /**
     * Enable Action
     * attach prefix to ident
     * @param string $Ident
     * @return bool
     */
    protected function EnableAction($Ident)
    {
        $Ident = $this->force_ident ? $Ident : $this->identifier($Ident);
        $this->force_ident = false;

        return parent::EnableAction($Ident);
    }

    /**
     * Enable Action
     * attach prefix to ident
     * @param string $Ident
     * @return bool
     */
    protected function DisableAction($Ident)
    {
        $Ident = $this->force_ident ? $Ident : $this->identifier($Ident);
        $this->force_ident = false;

        return parent::DisableAction($Ident);
    }
}