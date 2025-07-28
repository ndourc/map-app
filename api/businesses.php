<?php
require_once '../config.php';

// Set CORS headers
setCorsHeaders();

// Get parameters
$city = $_GET['city'] ?? '';
$type = $_GET['type'] ?? '';

// Validate parameters
if (!validateCity($city)) {
    sendErrorResponse('City parameter is required and must be valid', 400);
}

if (!validateBusinessType($type)) {
    sendErrorResponse('Invalid business type', 400);
}



try {
    // Try to connect to database
    $pdo = getDatabaseConnection();
    
    if ($pdo) {
        // Build query
        $sql = "SELECT id, name, latitude, longitude, type, city, address FROM businesses WHERE city = :city";
        $params = ['city' => $city];
        
        if (!empty($type)) {
            $sql .= " AND type = :type";
            $params['type'] = $type;
        }
        
        // Add limit for performance
        $sql .= " LIMIT " . getConfig('api.max_results');
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $businesses = $stmt->fetchAll();
        
        sendJsonResponse($businesses);
    } else {
        // If database connection fails, return sample data
        $sampleBusinesses = generateSampleData($city, $type);
        sendJsonResponse($sampleBusinesses);
    }
    
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    
    // Generate sample data based on the city
    $sampleBusinesses = generateSampleData($city, $type);
    sendJsonResponse($sampleBusinesses);
}

function generateSampleData($city, $type = '') {
    // Get city coordinates for more realistic positioning
    $cityCoordinates = getCityCoordinates($city);
    
    $allBusinesses = [
        // Fast Food
        [
            'id' => 1,
            'name' => 'Chicken Inn',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'fast_food',
            'city' => $city,
            'address' => '123 Leopold Takawira Avenue'
        ],
        [
            'id' => 2,
            'name' => 'Nandos',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'fast_food',
            'city' => $city,
            'address' => '456 Robert Mugabe Road'
        ],
        [
            'id' => 3,
            'name' => 'Pizza Inn',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'fast_food',
            'city' => $city,
            'address' => '789 8th Avenue'
        ],
        [
            'id' => 4,
            'name' => 'Steers',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'fast_food',
            'city' => $city,
            'address' => '321 George Silundika Street'
        ],
        [
            'id' => 5,
            'name' => 'De Bonairs',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'fast_food',
            'city' => $city,
            'address' => '654 9th Avenue'
        ],
        
        // Restaurants
        [
            'id' => 6,
            'name' => 'Bulawayo Club Restaurant',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'restaurant',
            'city' => $city,
            'address' => '987 Leopold Takawira Avenue'
        ],
        [
            'id' => 7,
            'name' => 'Hillside Dams Restaurant',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'restaurant',
            'city' => $city,
            'address' => '147 Hillside Road'
        ],
        [
            'id' => 8,
            'name' => 'Mugabe International Airport Restaurant',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'restaurant',
            'city' => $city,
            'address' => '258 Airport Road'
        ],
        [
            'id' => 9,
            'name' => 'Centenary Park Restaurant',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'restaurant',
            'city' => $city,
            'address' => '369 Centenary Park'
        ],
        [
            'id' => 10,
            'name' => 'City Hall Restaurant',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'restaurant',
            'city' => $city,
            'address' => '741 Leopold Takawira Avenue'
        ],
        
        // Tourism
        [
            'id' => 11,
            'name' => 'Natural History Museum',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'tourism',
            'city' => $city,
            'address' => '852 Leopold Takawira Avenue'
        ],
        [
            'id' => 12,
            'name' => 'Bulawayo Railway Museum',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'tourism',
            'city' => $city,
            'address' => '963 Railway Station'
        ],
        [
            'id' => 13,
            'name' => 'Centenary Park',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'tourism',
            'city' => $city,
            'address' => '159 Centenary Park'
        ],
        [
            'id' => 14,
            'name' => 'Hillside Dams',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'tourism',
            'city' => $city,
            'address' => '753 Hillside Road'
        ],
        [
            'id' => 15,
            'name' => 'Mugabe International Airport',
            'latitude' => $cityCoordinates['lat'] + (rand(-5, 5) / 1000),
            'longitude' => $cityCoordinates['lng'] + (rand(-5, 5) / 1000),
            'type' => 'tourism',
            'city' => $city,
            'address' => '951 Airport Road'
        ]
    ];
    
    // Filter by type if specified
    if (!empty($type)) {
        $allBusinesses = array_filter($allBusinesses, function($business) use ($type) {
            return $business['type'] === $type;
        });
    }
    
    return array_values($allBusinesses);
}

// Helper function to get city coordinates
function getCityCoordinates($city) {
    $cities = [
        'Bulawayo' => ['lat' => -20.1486, 'lng' => 28.5806],
        'Harare' => ['lat' => -17.8292, 'lng' => 31.0522],
        'Gweru' => ['lat' => -19.4500, 'lng' => 29.8167],
        'Mutare' => ['lat' => -18.9667, 'lng' => 32.6167],
        'Chitungwiza' => ['lat' => -18.0000, 'lng' => 31.1000],
        'Epworth' => ['lat' => -17.8833, 'lng' => 31.1500],
        'Kwekwe' => ['lat' => -18.9167, 'lng' => 29.8167],
        'Kadoma' => ['lat' => -18.3333, 'lng' => 29.9167],
        'Masvingo' => ['lat' => -20.0667, 'lng' => 30.8333],
        'Chinhoyi' => ['lat' => -17.3500, 'lng' => 30.2000]
    ];
    
    return $cities[$city] ?? ['lat' => -20.1486, 'lng' => 28.5806]; // Default to Bulawayo
}
?> 