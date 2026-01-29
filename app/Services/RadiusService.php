<?php

namespace App\Services;

use App\Models\Radius\RadCheck;
use App\Models\Radius\RadReply;
use App\Models\Radius\RadUserGroup;
use App\Models\Radius\RadGroupReply;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class RadiusService
{
    /**
     * Create or update a user in Radius.
     */
    public function syncUser(string $username, string $password, string $groupName): bool
    {
        DB::beginTransaction();
        try {
            // 1. Set Password (Cleartext-Password)
            RadCheck::updateOrCreate(
                ['username' => $username, 'attribute' => 'Cleartext-Password'],
                ['op' => ':=', 'value' => $password]
            );

            // 2. Set User Group
            RadUserGroup::updateOrCreate(
                ['username' => $username],
                ['groupname' => $groupName, 'priority' => 1]
            );

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Radius Sync User Failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create or update a group in Radius with bandwidth limits.
     */
    public function syncGroup(string $groupName, string $bandwidthLimit): bool
    {
        try {
            // Set Mikrotik-Rate-Limit in radgroupreply
            RadGroupReply::updateOrCreate(
                ['groupname' => $groupName, 'attribute' => 'Mikrotik-Rate-Limit'],
                ['op' => ':=', 'value' => $bandwidthLimit]
            );

            return true;
        } catch (Exception $e) {
            Log::error("Radius Sync Group Failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a user from Radius.
     */
    public function deleteUser(string $username): bool
    {
        try {
            RadCheck::where('username', $username)->delete();
            RadReply::where('username', $username)->delete();
            RadUserGroup::where('username', $username)->delete();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Set user status in Radius by changing their group.
     */
    public function setUserStatus(string $username, string $status, string $activeGroup): bool
    {
        try {
            $isolatedGroup = Setting::getValue('radius_isolated_group', 'ISOLATED');
            $groupName = ($status === 'isolated') ? $isolatedGroup : $activeGroup;

            RadUserGroup::where('username', $username)->delete();
            RadUserGroup::create([
                'username' => $username,
                'groupname' => $groupName,
                'priority' => 1
            ]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
