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
        // Mock Implementation
        // Simulate Telnet/SNMP connection delay
        sleep(1);

        // Return random signal between -15.00 and -32.00
        // -15 to -24 is Good
        // -24 to -27 is Warning
        // < -27 is Bad/LOS
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
