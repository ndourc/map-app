-- City Business Map Database Setup
-- Run this script to create the database and tables

-- Create database
CREATE DATABASE IF NOT EXISTS city_business_map;
USE city_business_map;

-- Create businesses table
CREATE TABLE IF NOT EXISTS businesses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    latitude DOUBLE NOT NULL,
    longitude DOUBLE NOT NULL,
    type ENUM('restaurant', 'fast_food', 'tourism') NOT NULL,
    city VARCHAR(100) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_city (city),
    INDEX idx_type (type),
    INDEX idx_city_type (city, type)
);

-- Insert sample data
INSERT INTO businesses (name, latitude, longitude, type, city, address) VALUES
-- New York City
('Burger Palace', 40.7589, -73.9851, 'fast_food', 'New York', '123 Broadway'),
('Fine Dining Restaurant', 40.7505, -73.9934, 'restaurant', 'New York', '456 5th Avenue'),
('City Museum', 40.7587, -73.9787, 'tourism', 'New York', '789 Museum Mile'),
('Pizza Express', 40.7614, -73.9776, 'fast_food', 'New York', '321 Times Square'),
('Gourmet Bistro', 40.7527, -73.9772, 'restaurant', 'New York', '654 Park Avenue'),
('Historical Center', 40.7484, -73.9857, 'tourism', 'New York', '987 Madison Avenue'),
('Taco Bell', 40.7568, -73.9865, 'fast_food', 'New York', '147 6th Avenue'),
('Seafood Grill', 40.7604, -73.9753, 'restaurant', 'New York', '258 3rd Avenue'),

-- Los Angeles
('Burger Palace LA', 34.0522, -118.2437, 'fast_food', 'Los Angeles', '123 Hollywood Blvd'),
('Fine Dining LA', 34.1016, -118.3267, 'restaurant', 'Los Angeles', '456 Sunset Blvd'),
('LA Museum', 34.0522, -118.2437, 'tourism', 'Los Angeles', '789 Downtown LA'),
('Pizza Express LA', 34.0928, -118.3287, 'fast_food', 'Los Angeles', '321 Beverly Hills'),
('Gourmet Bistro LA', 34.0736, -118.2400, 'restaurant', 'Los Angeles', '654 Santa Monica'),
('Historical Center LA', 34.1016, -118.3267, 'tourism', 'Los Angeles', '987 West Hollywood'),
('Taco Bell LA', 34.0522, -118.2437, 'fast_food', 'Los Angeles', '147 Venice Blvd'),
('Seafood Grill LA', 34.0928, -118.3287, 'restaurant', 'Los Angeles', '258 Melrose Ave'),

-- Chicago
('Burger Palace Chicago', 41.8781, -87.6298, 'fast_food', 'Chicago', '123 Michigan Ave'),
('Fine Dining Chicago', 41.8781, -87.6298, 'restaurant', 'Chicago', '456 State Street'),
('Chicago Museum', 41.8781, -87.6298, 'tourism', 'Chicago', '789 Millennium Park'),
('Pizza Express Chicago', 41.8781, -87.6298, 'fast_food', 'Chicago', '321 Wacker Drive'),
('Gourmet Bistro Chicago', 41.8781, -87.6298, 'restaurant', 'Chicago', '654 Lake Shore Drive'),
('Historical Center Chicago', 41.8781, -87.6298, 'tourism', 'Chicago', '987 Navy Pier'),
('Taco Bell Chicago', 41.8781, -87.6298, 'fast_food', 'Chicago', '147 Magnificent Mile'),
('Seafood Grill Chicago', 41.8781, -87.6298, 'restaurant', 'Chicago', '258 River North'),

-- Houston
('Burger Palace Houston', 29.7604, -95.3698, 'fast_food', 'Houston', '123 Main Street'),
('Fine Dining Houston', 29.7604, -95.3698, 'restaurant', 'Houston', '456 Texas Avenue'),
('Houston Museum', 29.7604, -95.3698, 'tourism', 'Houston', '789 Museum District'),
('Pizza Express Houston', 29.7604, -95.3698, 'fast_food', 'Houston', '321 Westheimer'),
('Gourmet Bistro Houston', 29.7604, -95.3698, 'restaurant', 'Houston', '654 Kirby Drive'),
('Historical Center Houston', 29.7604, -95.3698, 'tourism', 'Houston', '987 Hermann Park'),
('Taco Bell Houston', 29.7604, -95.3698, 'fast_food', 'Houston', '147 Rice Village'),
('Seafood Grill Houston', 29.7604, -95.3698, 'restaurant', 'Houston', '258 Montrose'),

-- Phoenix
('Burger Palace Phoenix', 33.4484, -112.0740, 'fast_food', 'Phoenix', '123 Central Ave'),
('Fine Dining Phoenix', 33.4484, -112.0740, 'restaurant', 'Phoenix', '456 Camelback Road'),
('Phoenix Museum', 33.4484, -112.0740, 'tourism', 'Phoenix', '789 Desert Botanical Garden'),
('Pizza Express Phoenix', 33.4484, -112.0740, 'fast_food', 'Phoenix', '321 Scottsdale Road'),
('Gourmet Bistro Phoenix', 33.4484, -112.0740, 'restaurant', 'Phoenix', '654 McDowell Road'),
('Historical Center Phoenix', 33.4484, -112.0740, 'tourism', 'Phoenix', '987 Papago Park'),
('Taco Bell Phoenix', 33.4484, -112.0740, 'fast_food', 'Phoenix', '147 Indian School Road'),
('Seafood Grill Phoenix', 33.4484, -112.0740, 'restaurant', 'Phoenix', '258 Thomas Road');

-- Create indexes for better performance
CREATE INDEX idx_lat_lng ON businesses(latitude, longitude);
CREATE INDEX idx_created_at ON businesses(created_at);

-- Show table structure
DESCRIBE businesses;

-- Show sample data
SELECT * FROM businesses LIMIT 10; 