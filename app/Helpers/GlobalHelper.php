<?php

use Illuminate\Support\Facades\File;

/** Secret token to validate the api connection */
if (!function_exists('getSecretToken')) {
    function getSecretToken(): string
    {
        return '7|ddVwAWCcbmI9TrUIwnSJAqO7K7DJY6ypsX5Fq5pvad7907ac';
    }
}

/** Additional info for forms */
if (!function_exists('getCountryItems')) {
    function getCountryItems(): array
    {
        return [
            'Australia',
            'New Zealand',
//            'U.S.A.'
        ];
    }
}

if (!function_exists('getStateItems')) {
    function getStateItems($country): array
    {
        $states = [
            'Australia' => [
                'New South Wales',
                'Victoria',
                'Queensland',
                'Western Australia',
                'South Australia',
                'Tasmania',
                'Northern Territory',
                'Australian Capital Territory'
            ],
            'New Zealand' => [
                'Northland',
                'Auckland',
                'Waikato',
                'Bay of Plenty',
                'Gisborne',
                'Hawke\'s Bay',
                'Taranaki',
                'Manawatu-Wanganui',
                'Wellington',
                'Tasman',
                'Nelson',
                'Marlborough',
                'West Coast',
                'Canterbury',
                'Otago',
                'Southland'
            ]
        ];

        return $states[$country] ?? [];
    }
}

if (!function_exists('getIndustryItems')) {
    function getIndustryItems(): array
    {
        return [
            'Aged Care Services',
            'Disability Support Services',
            'Home Care & Community Services',
            'Hospitals & Healthcare Facilities',
            'Physiotherapy & Rehabilitation',
        ];
    }
}

/** Delete file */
if (!function_exists("deleteFileIfExists")) {
    function deleteFileIfExists($filePath)
    {
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
    function setSidebarActive($route)
    {
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
    function getContractTypes()
    {
        return [
            'permanent',
            'contract'
        ];
    }
}

if (!function_exists("getAssignmentStatus")) {
    function getAssignmentStatus()
    {
        return [
            'active',
            'on_leave',
            'terminated'
        ];
    }
}

