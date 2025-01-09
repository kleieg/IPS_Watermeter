<?php

class MyWatermeter extends IPSModule
{
    public function Create()
    {
        //Never delete this line!
        parent::Create();

        $this->ConnectParent('{C6D2AEB3-6E1F-4B2E-8E69-3A1A00246850}');

        // properties
        $this->RegisterPropertyString('Address', '');

        // variables
        $this->RegisterVariableFloat("Value", "Value", "Water_2");
        $this->RegisterVariableString("Error", "Error", "Error");
        $this->RegisterVariableInteger("Time", "Time", "~UnixTimestamp");

    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();

        $topic = $this->ReadPropertyString('Address');
        $this->SetReceiveDataFilter('.*' . $topic . '.*');
    }

    public function ReceiveData($JSONString)
    {
        $this->SendDebug('JSON', $JSONString, 0);
        if (empty($this->ReadPropertyString('Address'))) return;

        $Buffer = json_decode($JSONString, true);
        $Payload = json_decode($Buffer['Payload'], true);

        if(isset($Payload['value'])) {
            if(strlen($Payload['value']) > 0) {
                $this->SetValue('Value', $Payload['value']);
            }
        }
        if(isset($Payload['error'])) {
            $this->SetValue('Error', $Payload['error']);
        }
        if(isset($Payload['timestamp'])) {
            $this->SetValue('Time', strtotime($Payload['timestamp']));
        }
    }

}