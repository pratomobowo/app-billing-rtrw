<?php

namespace App\Services;

use App\Models\Olt;
use App\Models\Onu;

class OltService
{
    /**
     * Get Signal for specific ONU.
     * Returns signal in dBm (float) or null if failed.
     */
    public function checkSignal(Olt $olt, string $serialNumber): ?float
    {
        // TODO: Implement real OLT Driver based on $olt->type (ZTE, Huawei, etc.)
        // This usually requires Telnet/SSH or SNMP interaction.
        // For now, this returns a simulated signal for UI demonstration.
        
        // Mock Implementation
        // Simulate Telnet/SNMP connection delay
        sleep(1);

        // Return random signal between -15.00 and -32.00
        return rand(-3200, -1500) / 100;
    }

    /**
     * Refresh all ONUs under an OLT.
     */
    public function refreshAll(Olt $olt)
    {
        $onus = $olt->onus;
        foreach ($onus as $onu) {
            $signal = $this->checkSignal($olt, $onu->serial_number);
            $onu->update([
                'signal' => $signal,
                'last_check' => now(),
            ]);
        }
    }
}
