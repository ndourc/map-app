// City Business Map Application
class CityBusinessMap {
    constructor() {
        this.map = null;
        this.markers = [];
        this.currentCity = null;
        this.currentCoordinates = null;
        this.businessData = [];

        this.init();
    }

    async init() {
        try {
            // Show loading overlay
            this.showLoading();

            // Set default city to Bulawayo
            this.currentCity = 'Bulawayo';
            document.getElementById('current-city').textContent = this.currentCity;
            document.getElementById('panel-city').textContent = this.currentCity;

            // Initialize map with Bulawayo coordinates
            this.currentCoordinates = this.getCityCoordinates('Bulawayo');
            this.initMap();

            // Load businesses for Bulawayo
            await this.loadBusinesses();

            // Set up event listeners
            this.setupEventListeners();

            // Hide loading overlay
            this.hideLoading();

        } catch (error) {
            console.error('Initialization error:', error);
            this.showError('Failed to initialize the application. Please refresh the page.');
        }
    }

    // Geolocation Module
    getUserLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Geolocation is not supported by this browser.'));
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.currentCoordinates = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Log location accuracy for debugging
                    console.log('Location accuracy:', position.coords.accuracy, 'meters');
                    console.log('Coordinates:', this.currentCoordinates);

                    resolve(this.currentCoordinates);
                },
                (error) => {
                    console.error('Geolocation error:', error);
                    this.showLocationError();
                    reject(error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0 // Don't use cached location
                }
            );
        });
    }

    // Reverse Geocoding Module
    async getCityName() {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${this.currentCoordinates.lat}&lon=${this.currentCoordinates.lng}&zoom=10&addressdetails=1`
            );

            if (!response.ok) {
                throw new Error('Failed to fetch city name');
            }

            const data = await response.json();

            // Extract city name from response
            this.currentCity = this.extractCityName(data);

            // Update UI
            document.getElementById('current-city').textContent = this.currentCity;

        } catch (error) {
            console.error('Reverse geocoding error:', error);
            this.currentCity = 'Unknown City';
            document.getElementById('current-city').textContent = this.currentCity;
        }
    }

    extractCityName(data) {
        const address = data.address;

        // Log the full address data for debugging
        console.log('Full address data:', address);

        // Try different address components in order of preference
        let cityName = address.city ||
            address.town ||
            address.village ||
            address.county ||
            address.state ||
            'Unknown City';

        // For Zimbabwe, check for specific cities
        if (address.country === 'Zimbabwe') {
            // Check if we're in Bulawayo area (approximate coordinates)
            if (this.currentCoordinates.lat >= -20.2 && this.currentCoordinates.lat <= -20.1 &&
                this.currentCoordinates.lng >= 28.5 && this.currentCoordinates.lng <= 28.7) {
                cityName = 'Bulawayo';
            }
            // Check if we're in Harare area (approximate coordinates)
            else if (this.currentCoordinates.lat >= -17.9 && this.currentCoordinates.lat <= -17.7 &&
                this.currentCoordinates.lng >= 31.0 && this.currentCoordinates.lng <= 31.2) {
                cityName = 'Harare';
            }
        }

        console.log('Extracted city name:', cityName);
        return cityName;
    }

    // Map Display Module
    initMap() {
        this.map = L.map('map').setView([this.currentCoordinates.lat, this.currentCoordinates.lng], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(this.map);

        // Add user location marker
        const userMarker = L.marker([this.currentCoordinates.lat, this.currentCoordinates.lng], {
            icon: L.divIcon({
                className: 'custom-marker user-location',
                html: '<div style="background: #007bff; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            })
        }).addTo(this.map);

        // Add popup to user marker
        userMarker.bindPopup('<strong>Your Location</strong><br>This is where you are located.');
    }

    // Business API Module
    async loadBusinesses(type = '') {
        try {
            const url = new URL('/api/businesses.php', window.location.origin);
            url.searchParams.set('city', this.currentCity);
            if (type) {
                url.searchParams.set('type', type);
            }

            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            this.businessData = await response.json();

            // Clear existing markers
            this.clearMarkers();

            // Add markers for businesses
            this.addBusinessMarkers();

        } catch (error) {
            console.error('Error loading businesses:', error);
            // For demo purposes, create sample data if API fails
            this.createSampleData();
        }
    }

    clearMarkers() {
        this.markers.forEach(marker => {
            this.map.removeLayer(marker);
        });
        this.markers = [];
    }

    addBusinessMarkers() {
        this.businessData.forEach(business => {
            const marker = L.marker([business.latitude, business.longitude], {
                icon: L.divIcon({
                    className: `custom-marker ${business.type}`,
                    html: `<div style="background: ${this.getMarkerColor(business.type)}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(this.map);

            // Add popup
            marker.bindPopup(`
                <div style="min-width: 200px;">
                    <h4 style="margin: 0 0 8px 0; color: #333;">${business.name}</h4>
                    <p style="margin: 0 0 5px 0; color: #666; font-size: 12px; text-transform: capitalize;">${business.type.replace('_', ' ')}</p>
                    <p style="margin: 0; color: #888; font-size: 11px;">${business.address}</p>
                </div>
            `);

            this.markers.push(marker);
        });

        // Update business counter
        this.updateBusinessCounter();
    }

    updateBusinessCounter() {
        const count = this.businessData.length;
        const type = document.getElementById('panel-business-type').value;
        let message = `${count} business${count !== 1 ? 'es' : ''} found`;

        if (type) {
            message += ` in ${type.replace('_', ' ')}`;
        }

        document.getElementById('business-count').textContent = message;
        document.getElementById('panel-business-count').textContent = `${count} businesses`;
    }

    // Populate business list in panel
    populateBusinessList() {
        const businessList = document.getElementById('business-list');
        businessList.innerHTML = '';

        this.businessData.forEach((business, index) => {
            const businessItem = document.createElement('div');
            businessItem.className = 'business-item';
            businessItem.innerHTML = `
                <h4>${business.name}</h4>
                <div class="business-type">${business.type.replace('_', ' ')}</div>
                <div class="business-address">${business.address}</div>
            `;

            businessItem.addEventListener('click', () => {
                // Highlight selected item
                document.querySelectorAll('.business-item').forEach(item => {
                    item.classList.remove('selected');
                });
                businessItem.classList.add('selected');

                // Center map on this business
                this.map.setView([business.latitude, business.longitude], 16);

                // Open popup for this business
                this.markers[index].openPopup();
            });

            businessList.appendChild(businessItem);
        });
    }

    // Get user's actual location
    async getUserActualLocation() {
        try {
            this.showLoading();

            const coordinates = await this.getUserLocation();
            this.currentCoordinates = coordinates;

            // Get city name from coordinates
            await this.getCityName();

            // Update UI
            document.getElementById('current-city').textContent = this.currentCity;
            document.getElementById('panel-city').textContent = this.currentCity;

            // Center map on user location
            this.map.setView([coordinates.lat, coordinates.lng], 15);

            // Load businesses for the detected city
            await this.loadBusinesses();

            this.hideLoading();

        } catch (error) {
            console.error('Error getting actual location:', error);
            this.hideLoading();
            alert('Could not get your location. Please check your browser settings.');
        }
    }

    getMarkerColor(type) {
        const colors = {
            'restaurant': '#28a745',
            'fast_food': '#ffc107',
            'tourism': '#dc3545'
        };
        return colors[type] || '#007bff';
    }

    // Create sample data for demo purposes
    createSampleData() {
        const sampleBusinesses = [
            {
                id: 1,
                name: "Burger Palace",
                latitude: this.currentCoordinates.lat + 0.01,
                longitude: this.currentCoordinates.lng + 0.01,
                type: "fast_food",
                city: this.currentCity,
                address: "123 Main Street"
            },
            {
                id: 2,
                name: "Fine Dining Restaurant",
                latitude: this.currentCoordinates.lat - 0.01,
                longitude: this.currentCoordinates.lng - 0.01,
                type: "restaurant",
                city: this.currentCity,
                address: "456 Oak Avenue"
            },
            {
                id: 3,
                name: "City Museum",
                latitude: this.currentCoordinates.lat + 0.005,
                longitude: this.currentCoordinates.lng - 0.005,
                type: "tourism",
                city: this.currentCity,
                address: "789 Cultural District"
            },
            {
                id: 4,
                name: "Pizza Express",
                latitude: this.currentCoordinates.lat - 0.005,
                longitude: this.currentCoordinates.lng + 0.005,
                type: "fast_food",
                city: this.currentCity,
                address: "321 Food Court"
            },
            {
                id: 5,
                name: "Gourmet Bistro",
                latitude: this.currentCoordinates.lat + 0.008,
                longitude: this.currentCoordinates.lng - 0.008,
                type: "restaurant",
                city: this.currentCity,
                address: "654 Gourmet Lane"
            }
        ];

        this.businessData = sampleBusinesses;
        this.addBusinessMarkers();
    }

    // Frontend Filtering Module
    setupEventListeners() {
        // Panel business type filter
        const panelFilterSelect = document.getElementById('panel-business-type');
        panelFilterSelect.addEventListener('change', (e) => {
            this.showLoading();
            this.loadBusinesses(e.target.value).finally(() => {
                this.hideLoading();
            });
        });

        // Manual city override
        const setCityBtn = document.getElementById('set-city-btn');
        const backToBulawayoBtn = document.getElementById('back-to-bulawayo-btn');
        const manualCityInput = document.getElementById('manual-city');

        setCityBtn.addEventListener('click', () => {
            const newCity = manualCityInput.value.trim();
            if (newCity) {
                this.showLoading();
                this.setManualCity(newCity).finally(() => {
                    this.hideLoading();
                });
            }
        });

        // Back to Bulawayo button
        backToBulawayoBtn.addEventListener('click', () => {
            this.showLoading();
            this.setManualCity('Bulawayo').finally(() => {
                this.hideLoading();
            });
        });

        // Allow Enter key to set city
        manualCityInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const newCity = manualCityInput.value.trim();
                if (newCity) {
                    this.showLoading();
                    this.setManualCity(newCity).finally(() => {
                        this.hideLoading();
                    });
                }
            }
        });

        // Business panel controls
        const showBusinessesBtn = document.getElementById('show-businesses-btn');
        const closePanelBtn = document.getElementById('close-panel');
        const businessPanel = document.getElementById('business-panel');

        showBusinessesBtn.addEventListener('click', () => {
            businessPanel.classList.add('open');
            this.populateBusinessList();
        });

        closePanelBtn.addEventListener('click', () => {
            businessPanel.classList.remove('open');
        });

        // My location button
        const myLocationBtn = document.getElementById('my-location-btn');
        myLocationBtn.addEventListener('click', () => {
            this.getUserActualLocation();
        });
    }

    // Manual city override method
    async setManualCity(cityName) {
        console.log('Setting manual city:', cityName);

        this.currentCity = cityName;
        document.getElementById('current-city').textContent = this.currentCity;
        document.getElementById('panel-city').textContent = this.currentCity;

        // Set map center based on city
        const cityCoordinates = this.getCityCoordinates(cityName);
        if (cityCoordinates) {
            this.map.setView([cityCoordinates.lat, cityCoordinates.lng], 13);
        }

        // Clear existing markers
        this.clearMarkers();

        // Load businesses for the new city
        await this.loadBusinesses();

        // Show success message
        const cityInput = document.getElementById('manual-city');
        cityInput.value = '';
        cityInput.placeholder = `Set to: ${cityName}`;

        setTimeout(() => {
            cityInput.placeholder = 'e.g., Bulawayo';
        }, 3000);
    }

    // Reset to Bulawayo
    resetToBulawayo() {
        this.currentCity = 'Bulawayo';
        this.currentCoordinates = this.getCityCoordinates('Bulawayo');

        // Update UI
        document.getElementById('current-city').textContent = this.currentCity;
        document.getElementById('panel-city').textContent = this.currentCity;

        // Center map on Bulawayo
        this.map.setView([this.currentCoordinates.lat, this.currentCoordinates.lng], 13);

        // Reload businesses
        this.loadBusinesses();
    }

    // Get coordinates for major cities
    getCityCoordinates(cityName) {
        const cities = {
            'Bulawayo': { lat: -20.1486, lng: 28.5806 },
            'Harare': { lat: -17.8292, lng: 31.0522 },
            'Gweru': { lat: -19.4500, lng: 29.8167 },
            'Mutare': { lat: -18.9667, lng: 32.6167 },
            'Chitungwiza': { lat: -18.0000, lng: 31.1000 },
            'Epworth': { lat: -17.8833, lng: 31.1500 },
            'Kwekwe': { lat: -18.9167, lng: 29.8167 },
            'Kadoma': { lat: -18.3333, lng: 29.9167 },
            'Masvingo': { lat: -20.0667, lng: 30.8333 },
            'Chinhoyi': { lat: -17.3500, lng: 30.2000 }
        };

        return cities[cityName] || null;
    }

    // UI Helper Methods
    showLoading() {
        document.getElementById('loading-overlay').classList.remove('hidden');
    }

    hideLoading() {
        document.getElementById('loading-overlay').classList.add('hidden');
    }

    showLocationError() {
        document.getElementById('error-modal').style.display = 'block';
    }

    showError(message) {
        alert(message);
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new CityBusinessMap();
}); 