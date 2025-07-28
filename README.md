# 🗺️ City Business Map

A dynamic single-page web application that displays businesses in your current city using interactive maps. The application automatically detects your location, centers the map on your city, and shows business locations with filtering capabilities.

## Features

- **📍 Automatic Location Detection**: Uses browser geolocation to find your current position
- **🗺️ Interactive Map**: Built with Leaflet.js and OpenStreetMap for smooth navigation
- **🏢 Business Filtering**: Filter businesses by type (Restaurant, Fast Food, Tourism)
- **📱 Responsive Design**: Works seamlessly on desktop and mobile devices
- **🎨 Modern UI**: Clean, modern interface with smooth animations
- **🔄 Real-time Updates**: Dynamic loading of business data based on your city
- **🛡️ Error Handling**: Graceful fallbacks for location and API failures

## 🏗️ Architecture

### Core Modules

| Module | Purpose | Technology |
|--------|---------|------------|
| **Geolocation Module** | Detects user's location | Browser Geolocation API |
| **Reverse Geocoding** | Converts coordinates to city name | OpenStreetMap Nominatim API |
| **Map Display Module** | Renders interactive map | Leaflet.js + OpenStreetMap |
| **Business API Module** | Serves business data | PHP + MySQL |
| **Filtering Module** | Client-side business filtering | JavaScript |
| **Frontend UI Module** | Responsive interface | HTML5 + CSS3 |

### Technology Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Map Engine**: Leaflet.js + OpenStreetMap
- **Geocoding**: OpenStreetMap Nominatim API
- **Backend**: PHP (No framework)
- **Database**: MySQL/MariaDB
- **API Format**: JSON

## 🚀 Quick Start

### Prerequisites

- Web server with PHP support (Apache, Nginx, or built-in PHP server)
- MySQL/MariaDB database (optional - app works with sample data)
- Modern web browser with geolocation support

### Installation

1. **Clone or download the project**
   ```bash
   git clone https://github.com/ndourc/map-app.git
   ```

2. **Set up the web server**
   - Place all files in your web server's document root
   - Ensure PHP is enabled on your server

3. **Database Setup (Optional)**
   ```bash
   # Import the database schema
   mysql -u root -p < database/setup.sql
   
   # Or run the SQL commands manually in your database client
   ```

4. **Configure Database Connection (Optional)**
   - Edit `api/businesses.php` and update the database configuration:
   ```php
   $db_config = [
       'host' => 'localhost',
       'dbname' => 'city_business_map',
       'username' => 'your_username',
       'password' => 'your_password'
   ];
   ```

5. **Start the application**
   ```bash
   # Using PHP built-in server (for development)
   php -S localhost:8000
   
   # Or access via your web server
   # http://localhost/city-business-map/
   ```

6. **Access the application**
   - Open your browser and navigate to the application URL
   - Allow location access when prompted
   - The map will automatically center on your city and display businesses

## 📁 Project Structure

```
city-business-map/
├── index.html              # Main HTML file
├── css/
│   └── style.css          # Main stylesheet
├── js/
│   └── app.js             # Main JavaScript application
├── api/
│   └── businesses.php     # Backend API endpoint
├── database/
│   └── setup.sql          # Database schema and sample data
└── README.md              # This file
```

## 🔧 Configuration

### Database Configuration

The application works without a database (using sample data), but for production use:

1. Create a MySQL database
2. Import the schema from `database/setup.sql`
3. Update database credentials in `api/businesses.php`

### API Endpoints

- `GET /api/businesses.php?city={city}&type={type}`
  - `city`: Required - The city name
  - `type`: Optional - Business type filter (restaurant, fast_food, tourism)

### Response Format

```json
[
  {
    "id": 1,
    "name": "Burger Palace",
    "latitude": 40.7589,
    "longitude": -73.9851,
    "type": "fast_food",
    "city": "New York",
    "address": "123 Broadway"
  }
]
```

## 🎯 Usage

1. **Location Detection**: The app automatically requests location access
2. **City Detection**: Uses reverse geocoding to determine your city
3. **Business Loading**: Fetches businesses for your city from the API
4. **Map Interaction**: Click markers to see business details
5. **Filtering**: Use the dropdown to filter businesses by type
6. **Navigation**: Pan and zoom the map to explore the area

## 🛠️ Development

### Adding New Business Types

1. Update the database schema in `database/setup.sql`
2. Modify the filter options in `index.html`
3. Update the marker colors in `js/app.js`
4. Add sample data in `api/businesses.php`

### Customizing the Map

- Modify map settings in `js/app.js` (zoom levels, tile layers)
- Update marker styles in `css/style.css`
- Customize popup content in the `addBusinessMarkers()` function

### Extending the API

- Add new endpoints in the `api/` directory
- Implement additional filtering options
- Add pagination for large datasets
- Include business ratings and reviews

## 🔒 Security Considerations

- The application uses prepared statements to prevent SQL injection
- CORS headers are configured for cross-origin requests
- Input validation is implemented on the server side
- Error messages don't expose sensitive information

## 🚀 Future Enhancements

- [ ] **Marker Clustering**: Improve readability when markers overlap
- [ ] **Business Search**: Manual search input for city or business name
- [ ] **Custom Icons**: Different icons for different business types
- [ ] **Admin Panel**: Add/edit/delete businesses via web UI
- [ ] **Location Caching**: Cache location results to reduce API calls
- [ ] **Offline Support**: Service worker for offline functionality
- [ ] **User Reviews**: Allow users to rate and review businesses
- [ ] **Directions**: Integration with mapping services for directions

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🆘 Support

If you encounter any issues:

1. Check the browser console for JavaScript errors
2. Verify your web server supports PHP
3. Ensure location access is enabled in your browser
4. Check that the API endpoint is accessible

## 📊 Performance

- **Lightweight**: No heavy frameworks, fast loading
- **Caching**: Browser caches map tiles and location data
- **Optimized**: Efficient database queries with proper indexing
- **Responsive**: Smooth performance on mobile devices

---

**Built with ❤️ using modern web technologies** 
