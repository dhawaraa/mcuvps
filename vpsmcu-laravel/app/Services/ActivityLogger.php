<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class ActivityLogger
{
    /**
     * Log user or system action
     *
     * @param string $action LOGIN, LOGOUT, CREATE, UPDATE, DELETE, STATUS_CHANGE, TOGGLE, IMPORT, EXPORT
     * @param string $module AUTH, PUBLIC_EVENTS, PUBLIC_STUDENTS, UG_BATCHES, UG_STUDENTS, GRAD_APPROVALS, DONATIONS, STUDENTS, MODULES, ORG_UNITS, USERS, CONTACT
     * @param string $description Human-readable Thai activity description
     * @param array|null $details Additional payload/data in JSON format
     * @param int|null $userId Specific user ID or default to current logged-in admin
     * @param int|null $orgUnitId Specific org unit ID or default to current logged-in admin org
     * @return ActivityLog|null
     */
    public static function log(string $action, string $module, string $description, ?array $details = null, ?int $userId = null, ?int $orgUnitId = null): ?ActivityLog
    {
        try {
            $admin = Session::get('admin_user', []);

            $uid = $userId ?? ($admin['id'] ?? null);
            $username = !empty($admin['username']) ? $admin['username'] : ($uid ? 'user#' . $uid : 'System');
            $fullName = !empty($admin['name']) ? $admin['name'] : $username;
            $role = !empty($admin['role']) ? $admin['role'] : null;
            $orgId = $orgUnitId ?? ($admin['org_unit_id'] ?? null);

            $ip = Request::ip();
            $userAgent = Request::header('User-Agent');

            return ActivityLog::create([
                'user_id' => $uid,
                'username' => $username,
                'user_fullname' => $fullName,
                'user_role' => $role,
                'org_unit_id' => $orgId,
                'action' => strtoupper($action),
                'module' => strtoupper($module),
                'description' => $description,
                'details' => $details,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Fail silently so logging never breaks application flow
            report($e);
            return null;
        }
    }
}
