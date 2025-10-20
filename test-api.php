<?php
/**
 * Test script per verificare l'API OpenCMS
 */

$baseUrl = 'http://localhost';

echo "=== TEST API OpenCMS ===\n\n";

// Test 1: Homepage
echo "1. Test Homepage:\n";
$url = $baseUrl . '/json/sites/mercury.local/index.html?locale=en&fallbackLocale';
echo "URL: $url\n";
$response = @file_get_contents($url);
if ($response === false) {
    echo "❌ ERRORE: Impossibile raggiungere l'API\n";
    echo "Verifica che OpenCMS sia in esecuzione su http://localhost\n\n";
} else {
    $data = json_decode($response, true);
    echo "✅ Risposta ricevuta\n";
    echo "Type: " . ($data['attributes']['type'] ?? 'N/A') . "\n";
    echo "Containers: " . count($data['containers'] ?? []) . "\n";
    if (!empty($data['containers'])) {
        foreach ($data['containers'] as $i => $container) {
            echo "  Container $i: " . count($container['elements'] ?? []) . " elementi\n";
            if (!empty($container['elements'])) {
                foreach ($container['elements'] as $j => $element) {
                    echo "    Element $j: " . ($element['formatterKey'] ?? 'N/A') . "\n";
                }
            }
        }
    }
    echo "\n";
}

// Test 2: Pagina Contatti
echo "2. Test Pagina Contatti:\n";
$url = $baseUrl . '/json/sites/mercury.local/contatti/index.html?locale=en&fallbackLocale';
echo "URL: $url\n";
$response = @file_get_contents($url);
if ($response === false) {
    echo "❌ ERRORE: Pagina non trovata\n";
    echo "La pagina /contatti potrebbe non esistere in OpenCMS\n\n";
} else {
    $data = json_decode($response, true);
    echo "✅ Risposta ricevuta\n";
    echo "Type: " . ($data['attributes']['type'] ?? 'N/A') . "\n";
    echo "Containers: " . count($data['containers'] ?? []) . "\n\n";
}

// Test 3: Lista Articoli
echo "3. Test Lista Articoli:\n";
$url = $baseUrl . '/json/sites/mercury.local/.content/article-m/?locale=en&fallbackLocale';
echo "URL: $url\n";
$response = @file_get_contents($url);
if ($response === false) {
    echo "❌ ERRORE: Impossibile caricare lista articoli\n\n";
} else {
    $data = json_decode($response, true);
    echo "✅ Risposta ricevuta\n";
    echo "Articoli trovati: " . count($data ?? []) . "\n\n";
}

// Test 4: Verifica struttura homepage
echo "4. Struttura completa homepage:\n";
$url = $baseUrl . '/json/sites/mercury.local/index.html?locale=en&fallbackLocale';
$response = @file_get_contents($url);
if ($response !== false) {
    $data = json_decode($response, true);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
}

echo "\n=== FINE TEST ===\n";
