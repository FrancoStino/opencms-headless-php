/**
 * OpenCms Headless - Application JavaScript
 */

(function() {
    'use strict';

    // =====================================================
    // Dark Mode Management
    // =====================================================

    /**
     * Toggle dark mode
     */
    function toggleDarkMode() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');

        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem('darkMode', 'false');
        } else {
            html.classList.add('dark');
            localStorage.setItem('darkMode', 'true');
        }
    }

    /**
     * Initialize dark mode from localStorage or system preference
     */
    function initializeDarkMode() {
        const savedMode = localStorage.getItem('darkMode');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedMode === 'true' || (!savedMode && prefersDark)) {
            document.documentElement.classList.add('dark');
        }
    }

    // =====================================================
    // Mobile Menu Management
    // =====================================================

    /**
     * Toggle mobile menu visibility
     */
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    // =====================================================
    // Event Listeners
    // =====================================================

    /**
     * Initialize all event listeners
     */
    function initializeEventListeners() {
        // Dark mode toggle
        const darkModeButton = document.querySelector('[onclick="toggleDarkMode()"]');
        if (darkModeButton) {
            darkModeButton.addEventListener('click', toggleDarkMode);
            darkModeButton.removeAttribute('onclick'); // Remove inline handler
        }

        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('[onclick="toggleMobileMenu()"]');
        if (mobileMenuButton) {
            mobileMenuButton.removeAttribute('onclick'); // Remove inline handler
        }
    }

    // =====================================================
    // Google Maps Integration
    // =====================================================

    /**
     * Initialize Google Map for a specific element
     * @param {string} elementId - The ID of the map container
     * @param {string} address - The address to geocode
     */
    function initMapForElement(elementId, address) {
        const mapElement = document.getElementById(elementId);
        if (!mapElement) {
            console.error('Map element not found:', elementId);
            return;
        }

        // Create geocoder to convert address to coordinates
        const geocoder = new google.maps.Geocoder();

        geocoder.geocode({ address: address }, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK && results[0]) {
                const location = results[0].geometry.location;

                // Create map
                const map = new google.maps.Map(mapElement, {
                    zoom: 15,
                    center: location,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: false,
                    zoomControl: true,
                    styles: [
                        {
                            featureType: 'poi',
                            stylers: [{ visibility: 'off' }]
                        }
                    ]
                });

                // Add marker
                const marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: address
                });

                // Add info window
                const infoWindow = new google.maps.InfoWindow({
                    content: '<div style="font-family: system-ui, sans-serif; font-size: 14px;"><strong>' + address + '</strong></div>'
                });

                marker.addListener('click', function() {
                    infoWindow.open(map, marker);
                });

                // Open info window by default
                infoWindow.open(map, marker);
            } else {
                console.error('Geocode was not successful for the following reason: ' + status);
                mapElement.innerHTML = '<div class="flex items-center justify-center h-full bg-gray-100 dark:bg-gray-700 rounded-lg"><p class="text-gray-500 dark:text-gray-400">Impossibile caricare la mappa</p></div>';
            }
        });
    }

    /**
     * Initialize Google Map (legacy function for backward compatibility)
     */
    function initMap() {
        // This function is kept for backward compatibility
        // Modern implementation uses initMapForElement
        console.log('initMap called - use initMapForElement for new implementations');
    }

    /**
     * Initialize the application
     */
    function initialize() {
        initializeDarkMode();
        initializeEventListeners();
    }

    // Run initialization when DOM is ready
    if (document.readyState === 'loading') {
    } else {
        initialize();
    }

    // =====================================================
    // Global Functions (for backward compatibility and external APIs)
    // =====================================================

    window.toggleDarkMode = toggleDarkMode;
    window.toggleMobileMenu = toggleMobileMenu;
    window.initMap = initMap; // For Google Maps API
    window.initMapForElement = initMapForElement; // For individual map elements

})();
