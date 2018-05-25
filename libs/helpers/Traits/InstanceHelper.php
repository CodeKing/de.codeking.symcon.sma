<?php

/**
 * Trait with data exchange helpers
 */
trait InstanceHelper
{
    /**
     * execute, when kernel is ready
     */
    protected function onKernelReady()
    {
    }

    /**
     * interact on symcon messages
     */
    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        switch ($Message) :
            case IPS_KERNELMESSAGE:
                if ($Data[0] != KR_READY) {
                    break;
                }
            case IM_DISCONNECT:
            case IPS_KERNELSTARTED:
                $this->RegisterParent();
                if (method_exists($this, 'IOChangeState')) {
                    if ($this->HasActiveParent()) {
                        $this->IOChangeState(IS_ACTIVE);
                    } else {
                        $this->IOChangeState(IS_INACTIVE);
                    }
                }
                break;
            case IM_CHANGESTATUS:
                if (method_exists($this, 'IOChangeState') && $SenderID == $this->GetParentId()) {
                    $this->IOChangeState($Data[0]);
                }
                break;
        endswitch;

        if ($Message == IPS_KERNELMESSAGE && $Data[0] == KR_READY) {
            $this->onKernelReady();
        }
    }

    /**
     * register parent instance
     */
    protected function RegisterParent()
    {
        $OldParentId = $this->GetParentId();
        $ParentId = @IPS_GetInstance($this->InstanceID)['ConnectionID'];
        if ($ParentId <> $OldParentId) {
            if ($OldParentId > 0) {
                $this->UnregisterMessage($OldParentId, IM_CHANGESTATUS);
                $this->UnregisterMessage($OldParentId, IM_DISCONNECT);

                if ((float)IPS_GetKernelVersion() < 4.2) {
                    $this->RegisterMessage($OldParentId, IPS_KERNELMESSAGE);
                } else {
                    $this->RegisterMessage($OldParentId, IPS_KERNELSTARTED);
                    $this->RegisterMessage($OldParentId, IPS_KERNELSHUTDOWN);
                }
            }
        }

        if ($ParentId > 0) {
            $this->RegisterMessage($ParentId, IM_CHANGESTATUS);
            $this->UnregisterMessage($ParentId, IM_DISCONNECT);

            if ((float)IPS_GetKernelVersion() < 4.2) {
                $this->RegisterMessage($ParentId, IPS_KERNELMESSAGE);
            } else {
                $this->RegisterMessage($ParentId, IPS_KERNELSTARTED);
                $this->RegisterMessage($ParentId, IPS_KERNELSHUTDOWN);
            }
        } else {
            $ParentId = 0;
        }

        return $ParentId;
    }

    /**
     * check, if module has an active parent
     * @return bool
     */
    protected function HasActiveParent()
    {
        if ($ParentID = $this->GetParentId()) {
            $parent = IPS_GetInstance($ParentID);
            if ($parent['InstanceStatus'] == 102) {
                return true;
            }
        }

        return false;
    }

    /**
     * get connected parent instance id
     * @return mixed
     */
    protected function GetParentId()
    {
        $instance = @IPS_GetInstance($this->InstanceID);
        return $instance['ConnectionID'];
    }

    /**
     * reconnect parent socket
     * @param bool $force
     */
    public function ReconnectParentSocket($force = false)
    {
        $ParentID = $this->GetParentId();
        if (($this->HasActiveParent() || $force) && $ParentID > 0) {
            IPS_SetProperty($ParentID, 'Open', true);
            @IPS_ApplyChanges($ParentID);
        }
    }

    /**
     * destroy instance by guid and identifier
     * @param null $guid
     * @param null $Ident
     * @return bool
     */
    protected function DestroyInstanceByModuleAndIdent($guid = NULL, $Ident = NULL)
    {
        // get module instances
        $instances = IPS_GetInstanceListByModuleID($guid);

        // search for instance with ident
        foreach ($instances AS $instance_id) {
            $instance = IPS_GetObject($instance_id);
            if ($instance['ObjectIdent'] == $Ident) {
                // delete instance
                IPS_DeleteInstance($instance_id);
                return true;
            }
        }

        return false;
    }

    /**
     * register a webhook
     * @param string $webhook
     * @param bool $delete
     */
    protected function RegisterWebhook($webhook, $delete = false)
    {
        $ids = IPS_GetInstanceListByModuleID("{015A6EB8-D6E5-4B93-B496-0D3F77AE9FE1}");

        if (sizeof($ids) > 0) {
            $hooks = json_decode(IPS_GetProperty($ids[0], "Hooks"), true);
            $found = false;
            foreach ($hooks AS $index => $hook) {
                if ($hook['Hook'] == $webhook) {
                    if ($hook['TargetID'] == $this->InstanceID && !$delete)
                        return;
                    elseif ($delete && $hook['TargetID'] == $this->InstanceID) {
                        continue;
                    }

                    $hooks[$index]['TargetID'] = $this->InstanceID;
                    $found = true;
                }
            }
            if (!$found) {
                $hooks[] = ["Hook" => $webhook, "TargetID" => $this->InstanceID];
            }

            IPS_SetProperty($ids[0], "Hooks", json_encode($hooks));
            IPS_ApplyChanges($ids[0]);
        }
    }
}