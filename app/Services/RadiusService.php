<?php

namespace App\Services;

use App\Models\Radius\RadCheck;
use App\Models\Radius\RadReply;
use App\Models\Radius\RadUserGroup;
use App\Models\Radius\RadGroupReply;
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
            // Remove old group first to avoid duplicates if user changes package
            RadUserGroup::where('username', $username)->delete();
            RadUserGroup::create([
                'username' => $username,
                'groupname' => $groupName,
                'priority' => 1
            ]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
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
            $groupName = ($status === 'isolated') ? 'ISOLATED' : $activeGroup;

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
