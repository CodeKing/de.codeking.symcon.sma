<?php

/**
 * Trait to read / write object properties in instance buffer
 */
trait BufferHelper
{
    /**
     * get property
     * @param string $name property name
     * @return mixed $value property value
     */
    public function __get($name)
    {
        if (strpos($name, 'Multi_') === 0 && is_array($this->{'BufferList_' . $name})) {
            $Lines = "";
            foreach ($this->{'BufferList_' . $name} as $BufferIndex) {
                $Lines .= $this->{'Part_' . $name . $BufferIndex};
            }
            return unserialize($Lines);
        }
        return unserialize($this->GetBuffer($name));
    }

    /**
     * write property
     * @param string $name property name
     * @param mixed $value property value
     */
    public function __set($name, $value)
    {
        $Data = serialize($value);
        if (strpos($name, 'Multi_') === 0) {
            $OldBuffers = $this->{'BufferList_' . $name};
            if ($OldBuffers == false) {
                $OldBuffers = [];
            }
            $Lines = str_split($Data, 8000);
            foreach ($Lines as $BufferIndex => $BufferLine) {
                $this->{'Part_' . $name . $BufferIndex} = $BufferLine;
            }
            $NewBuffers = array_keys($Lines);
            $this->{'BufferList_' . $name} = $NewBuffers;
            $DelBuffers = array_diff_key($OldBuffers, $NewBuffers);
            foreach ($DelBuffers as $DelBuffer) {
                $this->{'Part_' . $name . $DelBuffer} = "";
            }
            return;
        }

        $this->SetBuffer($name, $Data);
    }
}