<?php
/** @var array $localeContent */
/** @var array $settings */

// Get map data - support both structures

// Try different data structures
$address     = '';
$coordinates = null;
$title       = $localeContent[ 'Title' ] ?? 'Mappa';

// Determine zoom level
// Check if settings specify to use first marker zoom or a specific zoom
$mapZoomSetting = $settings[ 'mapZoom' ] ?? null;
if ( $mapZoomSetting === 'firstMarker' || empty($settings[ 'zoom' ]) ) {
	// We'll get zoom from the coordinate data, or use default 15
	$zoom = '15';
} else {
	$zoom = $settings[ 'zoom' ] ?? '15';
}

// Check if it's OpenCMS MapCoord structure
if ( isset( $localeContent[ 'MapCoord' ] ) && is_array ( $localeContent[ 'MapCoord' ] ) && !empty( $localeContent[ 'MapCoord' ] ) ) {
	$mapCoord = $localeContent[ 'MapCoord' ][ 0 ]; // Take first coordinate

	// Get address
	$address = $mapCoord[ 'Address' ] ?? '';

	// Parse coordinates from JSON string
	if ( isset( $mapCoord[ 'Coord' ] ) ) {
		$coordJson = json_decode ( $mapCoord[ 'Coord' ], true );
		if ( $coordJson && isset( $coordJson[ 'lat' ] ) && isset( $coordJson[ 'lng' ] ) ) {
			$coordinates = [
					'lat' => $coordJson[ 'lat' ],
					'lng' => $coordJson[ 'lng' ]
			];
			// Override zoom if specified in coordinates AND if mapZoom setting allows it
			if ( $mapZoomSetting === 'firstMarker' && isset( $coordJson[ 'zoom' ] ) && $coordJson[ 'zoom' ] > 0 ) {
				// Only use the zoom from coordinates if it's reasonable (between 10 and 20)
				$coordZoom = (int) $coordJson[ 'zoom' ];
				if ( $coordZoom >= 10 && $coordZoom <= 20 ) {
					$zoom = $coordZoom;
				} else if ( $coordZoom < 10 ) {
					// If zoom is too low (like 6), use a reasonable default
					$zoom = 15;
				}
			}
		}
	}
} // Check if it's Contact-style data (from Contact component)
else if ( isset( $localeContent[ 'Contact' ][ 'AddressChoice' ][ 'Address' ] ) ) {
	$contactAddress = $localeContent[ 'Contact' ][ 'AddressChoice' ][ 'Address' ];
	$address        = trim (
			( $contactAddress[ 'StreetAddress' ] ?? '' ) . ' ' .
			( $contactAddress[ 'PostalCode' ] ?? '' ) . ' ' .
			( $contactAddress[ 'Locality' ] ?? '' )
	);
	// No coordinates in this structure, but we can try to geocode the address
} // Check if it's the expected map component structure
else if ( isset( $localeContent[ 'Address' ] ) ) {
	$address     = $localeContent[ 'Address' ] ?? '';
	$coordinates = $localeContent[ 'Coordinates' ] ?? null;
}

// Google Maps API Key
$apiKey        = getenv ( 'GOOGLE_MAPS_API_KEY' );
$useGoogleMaps = $apiKey && $apiKey !== 'your_api_key_here';

// Generate map source
$mapSrc = '';
$mapId  = '';

if ( $useGoogleMaps ) {
	// Use Google Maps JavaScript API (interactive map)
	$mapId  = 'google-map-' . uniqid ();
	$mapSrc = 'google-js-api'; // Flag to indicate we're using JS API
} else {
	// Fallback: use OpenStreetMap (no API key needed)
	if ( $coordinates && isset( $coordinates[ 'lat' ] ) && isset( $coordinates[ 'lng' ] ) ) {
		$lat    = $coordinates[ 'lat' ];
		$lng    = $coordinates[ 'lng' ];
		$mapSrc = "https://www.openstreetmap.org/export/embed.html?bbox=" . ( $lng - 0.01 ) . "%2C" . ( $lat - 0.01 ) . "%2C" . ( $lng + 0.01 ) . "%2C" . ( $lat + 0.01 ) . "&layer=mapnik&marker={$lat}%2C{$lng}";
	} else if ( $address ) {
		// Default location
		$mapSrc = "https://www.openstreetmap.org/export/embed.html?bbox=16.8%2C41.1%2C16.9%2C41.2&layer=mapnik&marker=41.125%2C16.866";
	}
}
?>

<section class="max-w-6xl mx-auto mb-12">
	<?php if ( !empty( $title ) ): ?>
		<div class="bg-gray-100 dark:bg-gray-800 rounded-t-lg p-4 flex items-center gap-2">
			<svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
				<path fill-rule="evenodd"
					  d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
					  clip-rule="evenodd"/>
			</svg>
			<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
				<?php echo htmlspecialchars ( $title ); ?>
			</h3>
		</div>
	<?php endif; ?>

	<div class="relative w-full bg-gray-200 dark:bg-gray-700 rounded-b-lg overflow-hidden shadow-lg"
		 style="height: 450px;">
		<?php if ( $useGoogleMaps && $mapSrc === 'google-js-api' ): ?>
			<!-- Google Maps JavaScript API -->
			<div id="<?php echo $mapId; ?>" class="absolute inset-0 w-full h-full"></div>
			<script>
				( function () {
					var mapElement = document.getElementById( "<?php echo $mapId; ?>" );
					if ( !mapElement ) return;

					// Function to initialize this specific map
					function initThisMap() {
						if ( !window.google || !window.google.maps ) {
							console.error( "Google Maps not loaded" );
							mapElement.innerHTML = '<div class="flex items-center justify-center h-full bg-gray-100 dark:bg-gray-700 rounded-lg"><div class="text-center text-gray-500 dark:text-gray-400"><p>Maps API not available</p></div></div>';
							return;
						}

						try {
							var location = null;
							<?php if ( $coordinates && isset( $coordinates[ 'lat' ] ) && isset( $coordinates[ 'lng' ] ) ): ?>
							location = {
								lat: <?php echo $coordinates[ 'lat' ]; ?>,
								lng: <?php echo $coordinates[ 'lng' ]; ?> };
							<?php else: ?>
							console.warn( "No coordinates available for map" );
							<?php endif; ?>

							if ( !location ) {
								mapElement.innerHTML = '<div class="flex items-center justify-center h-full bg-gray-100 dark:bg-gray-700 rounded-lg"><div class="text-center text-gray-500 dark:text-gray-400"><p>No location data</p></div></div>';
								return;
							}

							var map = new google.maps.Map( mapElement, {
								zoom: <?php echo (int) $zoom; ?>,
								center:            location,
								mapTypeControl:    false,
								streetViewControl: false,
								fullscreenControl: false,
								zoomControl:       true,
								styles:            [
									{
										featureType: "poi",
										stylers:     [ { visibility: "off" } ]
									}
								]
							} );

							var marker = new google.maps.Marker( {
								position: location,
								map:      map,
								title:    "<?php echo addslashes ( $address ? : 'Location' ); ?>"
							} );

							// Create info window but don't open it by default
							var infoWindow = new google.maps.InfoWindow( {
								content: '<div style="font-family: system-ui, sans-serif; font-size: 14px; color: #000000;"><strong><?php echo addslashes ( $address ? : 'Location' ); ?></strong></div>'
							} );

							// Only open info window when marker is clicked
							marker.addListener( "click", function () {
								infoWindow.open( map, marker );
							} );

						} catch ( error ) {
							console.error( "Error initializing map:", error );
							mapElement.innerHTML = '<div class="flex items-center justify-center h-full bg-gray-100 dark:bg-gray-700 rounded-lg"><div class="text-center text-gray-500 dark:text-gray-400"><p>Error loading map</p></div></div>';
						}
					}

					// Load Google Maps if not already loaded
					if ( !window.google || !window.google.maps ) {
						var script     = document.createElement( "script" );
						script.src     = "https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey; ?>";
						script.async   = true;
						script.defer   = true;
						script.onload  = function () {
							initThisMap();
						};
						script.onerror = function () {
							mapElement.innerHTML = '<div class="flex items-center justify-center h-full bg-gray-100 dark:bg-gray-700 rounded-lg"><div class="text-center text-gray-500 dark:text-gray-400"><svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg><p class="text-sm mb-2">Mappa bloccata da ad blocker</p><p class="text-xs">Disabilita uBlock Origin per questo sito</p></div></div>';
						};
						document.head.appendChild( script );
					} else {
						// Google Maps already loaded
						initThisMap();
					}
				} )();
			</script>
		<?php elseif ( $mapSrc && $mapSrc !== 'google-js-api' ): ?>
			<!-- OpenStreetMap fallback -->
			<iframe
					src="<?php echo htmlspecialchars ( $mapSrc ); ?>"
					class="absolute inset-0 w-full h-full border-0"
					style="border:0;"
					allowfullscreen=""
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade">
			</iframe>
		<?php else: ?>
			<!-- No map available -->
			<div class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-700">
				<div class="text-center text-gray-500 dark:text-gray-400">
					<svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="currentColor" viewBox="0 0 20 20">
						<path fill-rule="evenodd"
							  d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
							  clip-rule="evenodd"/>
					</svg>
					<p class="text-sm mb-2">Mappa non disponibile</p>
					<?php if ( !$useGoogleMaps ): ?>
						<p class="text-xs">Configura <code class="bg-gray-200 dark:bg-gray-600 px-1 rounded">GOOGLE_MAPS_API_KEY</code>
										   per Google Maps</p>
						<p class="text-xs mt-1">Vedi documentazione per istruzioni dettagliate</p>
					<?php else: ?>
						<p class="text-xs">Errore nel caricamento della mappa</p>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $address ): ?>

	<?php endif; ?>
</section>
