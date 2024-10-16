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
if (!function_exists('getGenders')) {
    function getGenders(): array
    {
        return [
          'male',
          'female',
          'other'
        ];
    }
}

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

/** provide the bootstrap class for status */
if (!function_exists("getStatusClass")) {
    function getStatusWithClass($status) : string
    {
        $badgeClass = '';
        switch ($status) {
            case 'active':
                $badgeClass = 'bg-success'; // Bootstrap class for green (success)
                break;
            case 'inactive':
                $badgeClass = 'bg-danger'; // Bootstrap class for yellow (warning)
                break;
            case 'pending':
                $badgeClass = 'bg-warning'; // Bootstrap class for red (danger)
                break;
            default:
                $badgeClass = 'bg-secondary'; // Default class for unknown status
        }

        return '<div class="badge ' . $badgeClass . ' text-white">' . ucfirst($status) . '</div>';
    }
}

if (!function_exists('newGetStatusWithClass')) {
    function newGetStatusWithClass($status) : string
    {
        $statuses = getStatuses(request: 'class'); // Retrieve the statuses array

        // Check if the status exists in the array
        if (array_key_exists($status, $statuses)) {
            $badgeClass = $statuses[$status]['class']; // Get the appropriate class
            $label = $statuses[$status]['label']; // Get the appropriate label
        } else {
            $badgeClass = 'bg-secondary'; // Default class for unknown status
            $label = 'Unknown'; // Default label for unknown status
        }

        return '<div class="badge ' . $badgeClass . ' text-white">' . ucfirst($label) . '</div>';
    }
}


/** this provides the array to the migration files as well to make single point of editable origin */
if (!function_exists("getStatuses")) {
    function getStatuses($request = 'status'): array
    {
        $statuses = [
            'active' => ['class' => 'bg-success', 'label' => 'Active'],
            'inactive' => ['class' => 'bg-danger', 'label' => 'Inactive'],
            'pending' => ['class' => 'bg-warning', 'label' => 'Pending'],
        ];

        if ($request == 'status') {
            // Return the keys adn default as a double-dimensional array
            $array = [
                'keys' => array_keys($statuses),
                'default' => 'active'
            ];

            return $array;
        }

        if ($request == 'class') {
            return $statuses; // Return the whole array
        }

        return [];
    }
}

