<?php

use Illuminate\Support\Facades\File;

if (!function_exists('getSecretToken')) {
    function getSecretToken() : string
    {
        return '7|ddVwAWCcbmI9TrUIwnSJAqO7K7DJY6ypsX5Fq5pvad7907ac';
    }
}

/** Delete file */
if (!function_exists("deleteFileIfExists")) {
    function deleteFileIfExists($filePath) {
        try {
            if (File::exists(public_path($filePath))) {
                File::delete(public_path($filePath));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

/** Set Sidebar Active */
if (!function_exists("setSidebarActive")) {
    function setSidebarActive($route) {
        if (is_array($route)) {
            foreach ($route as $r) {
                if (request()->routeIs($r)) {
                    return 'active';
                }
            }
        }
    }
}

/** provide the info for employee assignment */
if (!function_exists("getContractTypes")) {
    function getContractTypes() {
        return [
            'permanent',
            'contract'
        ];
    }
}

if (!function_exists("getAssignmentStatus")) {
    function getAssignmentStatus() {
        return [
            'active',
            'on_leave',
            'terminated'
        ];
    }
}

if (!function_exists("getUniqueSystemUserId")) {
    function getUniqueSystemUserId() {
        $latestSystemUserId = (int) \App\Models\EmployeeAssignment::max('system_user_id');

        if (is_null($latestSystemUserId)) {
            $newSystemUserId = 1000; // Start from 1000 if no entries exist
        } else {
            $newSystemUserId = $latestSystemUserId + 1; // Increment by 1
        }
        return $newSystemUserId;

    }
}
