<?php

define('__ROOT__', dirname(dirname(__FILE__)));
define('__MODULE__', dirname(__FILE__));

require_once(__ROOT__ . '/libs/helpers/autoload.php');
require_once(__ROOT__ . '/libs/phpmodbus/Phpmodbus/ModbusMaster.php');
require_once(__MODULE__ . '/SMARegister.php');

/**
 * Class SMA_Modbus
 * IP-Symcon SMA Modbus Module
 *
 * @version     0.1
 * @category    Symcon
 * @package     de.codeking.symcon
 * @author      Frank Herrmann <frank@codeking.de>
 * @link        https://codeking.de
 * @link        https://github.com/CodeKing/de.codeking.symcon
 *
 */
class SMAModbus extends Module
{
    use InstanceHelper;

    private $device;
    private $ip;
    private $port;
    private $unit_id = 3;

    private $modbus;
    private $update = true;
    private $isDay;

    private $applied = false;

    public $data = [];

    protected $profile_mappings = [];
    protected $archive_mappings = [];

    /**
     * create instance
     */
    public function Create()
    {
        parent::Create();

        // register public properties
        $this->RegisterPropertyString('device', 'default');
        $this->RegisterPropertyString('ip', '');
        $this->RegisterPropertyInteger('port', 502);
        $this->RegisterPropertyInteger('unit_id', 3);
        $this->RegisterPropertyInteger('interval', 300);
        $this->RegisterPropertyInteger('daytime', 1);
        $this->RegisterPropertyInteger('interval_current', 30);

        // register timers
        $this->RegisterTimer('UpdateData', 0, $this->_getPrefix() . '_UpdateValues($_IPS[\'TARGET\'], false);');
        $this->RegisterTimer('UpdateCurrent', 0, $this->_getPrefix() . '_UpdateCurrent($_IPS[\'TARGET\']);');
    }

    /**
     * execute, when kernel is ready
     */
    protected function onKernelReady()
    {
        $this->applied = true;

        // update timer
        $this->SetTimerInterval('UpdateData', $this->ReadPropertyInteger('interval') * 1000);
        $this->SetTimerInterval('UpdateCurrent', $this->ReadPropertyInteger('interval_current') * 1000);

        // read & check config
        $this->ReadConfig();
    }

    /**
     * Read config
     */
    private function ReadConfig()
    {
        // read config
        $this->device = $this->ReadPropertyString('device');
        $this->ip = $this->ReadPropertyString('ip');
        $this->port = $this->ReadPropertyInteger('port');
        $this->unit_id = $this->ReadPropertyInteger('unit_id');

        // check config
        if (!$this->ip || !$this->port) {
            exit(-1);
        }

        // create modbus instance
        if ($this->ip && $this->port) {
            $this->modbus = new ModbusMaster($this->ip, 'TCP');
            $this->modbus->port = $this->port;
            $this->modbus->endianness = 0;

            // check register on apply changes in configuration
            if ($this->applied) {
                try {
                    $this->modbus->readMultipleRegisters($this->unit_id, (int)30051, 2);
                } catch (Exception $e) {
                    $this->SetStatus(202);
                    exit(-1);
                }
            }
        }

        // status ok
        $this->SetStatus(102);
    }

    /**
     * Update everything
     */
    public function Update()
    {
        $this->UpdateDevice();
        $this->UpdateValues();
    }

    /**
     * read & update device registersSMA_UpdateDevice
     * @param bool $applied
     */
    public function UpdateDevice($applied = false)
    {
        $this->update = 'device';
        $this->ReadData(SMARegister::device_addresses);

        if ($this->applied || $applied) {
            if (isset($this->data['Device class'])) {
                if (!isset($this->data['Device-ID'])) {
                    $this->data['Device-ID'] = '';
                }
                echo sprintf($this->Translate('%s %s has been detected.'), $this->Translate($this->data['Device class']), $this->data['Device-ID']);
            } else {
                echo sprintf($this->Translate('Unfortunately no device were found. Please try again in a few seconds.'));
            }
        }
    }

    /**
     * read & update update registers
     * @param bool $applied
     */
    public function UpdateValues($applied = false)
    {
        if ($this->_isDay() || $applied || $this->applied) {
            $this->update = 'values';
            $this->ReadData(SMARegister::value_addresses);
        }
    }

    /**
     * update current values, only
     */
    public function UpdateCurrent()
    {
        if ($this->_isDay() || $this->applied) {
            $this->update = 'current';
            $this->ReadData(SMARegister::current_addresses);
        }
    }

    /**
     * save data to variables
     */
    private function SaveData()
    {
        // loop data and create variables
        $position = ($this->update == 'values') ? count(SMARegister::device_addresses) - 1 : 0;
        foreach ($this->data AS $key => $value) {
            $this->CreateVariableByIdentifier([
                'parent_id' => $this->InstanceID,
                'name' => $key,
                'value' => $value,
                'position' => $position
            ]);

            if ($this->update != 'current') {
                $position++;
            }
        }
    }

    /**
     * read data via modbus
     * @param array $addresses
     */
    private function ReadData(array $addresses)
    {
        // read config
        $this->ReadConfig();

        // get addresses by device
        if ($this->device == 'default') {
            $addresses = $addresses['default'];
        } else {
            $addresses = array_replace_recursive(
                $addresses['default'],
                $addresses[$this->device]
            );
        }

        // read data
        foreach ($addresses AS $address => $config) {
            try {
                // wait some time before continue
                if (count($addresses) > 2) {
                    IPS_Sleep(200);
                }

                // read register
                $value = $this->modbus->readMultipleRegisters($this->unit_id, (int)$address, $config['count']);

                // set endianness
                $endianness = in_array($config['format'], ['RAW', 'TEMP', 'DURATION_S', 'DURATION_H']) ? 2 : 0;

                // fix bytes
                $value = $endianness
                    ? array_chunk($value, 4)[0]
                    : array_chunk($value, 2)[1];

                // convert signed value
                if (substr($config['type'], 0, 1) == 'S') {
                    // convert to signed int
                    $value = PhpType::bytes2signedInt($value, $endianness);
                } // convert unsigned value
                else if (substr($config['type'], 0, 1) == 'U') {
                    // convert to unsigned int
                    $value = PhpType::bytes2unsignedInt($value, $endianness);
                }

                // set value to 0 if value is negative or invalid
                if ((is_int($value) || is_float($value)) && $value < 0 || $value == 65535) {
                    $value = (float)0;
                }

                // continue if value is still an array
                if (is_array($value)) {
                    continue;
                }

                // map value
                if (isset($config['mapping'][$value])) {
                    $value = $this->Translate($config['mapping'][$value]);
                } // convert decimals
                elseif ($config['format'] == 'FIX0') {
                    $value = (float)$value;
                } elseif ($config['format'] == 'FIX1') {
                    $value /= (float)10;
                } elseif ($config['format'] == 'FIX2') {
                    $value /= (float)100;
                } elseif ($config['format'] == 'FIX3') {
                    $value /= (float)1000;
                } elseif ($config['format'] == 'DURATION_S') {
                    $value /= 3600;
                } elseif ($config['format'] == 'DURATION_H') {
                    $value /= 60;
                }

                // set profile
                if (isset($config['profile']) && !isset($this->profile_mappings[$config['name']])) {
                    $this->profile_mappings[$config['name']] = $config['profile'];
                }

                // set archive
                if (isset($config['archive'])) {
                    $this->archive_mappings[$config['name']] = $config['archive'];
                }

                // append data
                $this->data[$config['name']] = $value;
            } catch (Exception $e) {
            }
        }

        // save data
        $this->SaveData();
    }

    /**
     * detect if it's daytime
     * @return bool
     */
    private function _isDay()
    {
        // return true on configuration
        if (!$this->ReadPropertyInteger('daytime')) {
            return true;
        }

        if (is_null($this->isDay)) {
            // default value
            $this->isDay = false;

            // get location module
            $location_id = $this->_getLocationId();

            // get all location variables
            $location_variables = IPS_GetChildrenIDs($location_id);

            // search for isDay variable
            foreach ($location_variables AS $variable_id) {
                if ($variable = IPS_GetObject($variable_id)) {
                    if (strtolower($variable['ObjectIdent']) == 'isday') {
                        $this->isDay = GetValueBoolean($variable['ObjectID']);
                    }
                }
            }
        }

        // if it's day, enable current values timer, otherwise disable it!
        if ($this->isDay) {
            $this->SetTimerInterval('UpdateCurrent', $this->ReadPropertyInteger('interval_current') * 1000);
        } else {
            $this->SetTimerInterval('UpdateCurrent', 0);
        }

        // return value
        return $this->isDay;
    }

    /**
     * create custom variable profile
     * @param string $profile_id
     * @param string $name
     */
    protected function CreateCustomVariableProfile(string $profile_id, string $name)
    {
        switch ($name):
            case 'Watt':
                IPS_CreateVariableProfile($profile_id, 2); // float
                IPS_SetVariableProfileDigits($profile_id, 0); // 0 decimals
                IPS_SetVariableProfileText($profile_id, '', ' W'); // Watt
                IPS_SetVariableProfileIcon($profile_id, 'Electricity');
                break;
            case 'kWh.Fixed':
                IPS_CreateVariableProfile($profile_id, 2); // float
                IPS_SetVariableProfileDigits($profile_id, 0); // 0 decimals
                IPS_SetVariableProfileText($profile_id, '', ' kWh'); // Watt
                IPS_SetVariableProfileIcon($profile_id, 'Electricity');
                break;
            case 'Hours':
                IPS_CreateVariableProfile($profile_id, 2); // float
                IPS_SetVariableProfileDigits($profile_id, 1); // 1 decimal
                IPS_SetVariableProfileText($profile_id, '', ' ' . $this->Translate('h')); // Watt
                IPS_SetVariableProfileIcon($profile_id, 'Clock');
                break;
        endswitch;
    }

}