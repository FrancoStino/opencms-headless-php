<?php
/**
 * Test API OpenCMS - Accessibile via browser
 */

$baseUrl = getenv('OPENCMS_SERVER') ?: 'http://mercury.local';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenCMS API Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">🧪 OpenCMS API Test</h1>
        
        <?php
        // Test 1: Homepage
        echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
        echo '<h2 class="text-xl font-bold mb-4">1. Test Homepage</h2>';
        $url = $baseUrl . '/json/sites/mercury.local/index.html?locale=en&fallbackLocale';
        echo '<p class="text-sm text-gray-600 mb-2"><strong>URL:</strong> ' . htmlspecialchars($url) . '</p>';
        
        $response = @file_get_contents($url);
        if ($response === false) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">';
            echo '❌ <strong>ERRORE:</strong> Impossibile raggiungere l\'API<br>';
            echo 'Verifica che OpenCMS sia in esecuzione su ' . htmlspecialchars($baseUrl);
            echo '</div>';
        } else {
            $data = json_decode($response, true);
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">';
            echo '✅ <strong>Risposta ricevuta</strong>';
            echo '</div>';
            
            echo '<div class="space-y-2 text-sm">';
            echo '<p><strong>Type:</strong> ' . htmlspecialchars($data['attributes']['type'] ?? 'N/A') . '</p>';
            echo '<p><strong>Containers:</strong> ' . count($data['containers'] ?? []) . '</p>';
            
            if (!empty($data['containers'])) {
                echo '<div class="mt-4">';
                echo '<p class="font-bold mb-2">Containers Details:</p>';
                foreach ($data['containers'] as $i => $container) {
                    echo '<div class="ml-4 mb-2">';
                    echo '<p class="font-semibold">Container ' . $i . ': ' . count($container['elements'] ?? []) . ' elementi</p>';
                    if (!empty($container['elements'])) {
                        echo '<div class="ml-4">';
                        foreach ($container['elements'] as $j => $element) {
                            echo '<p class="text-gray-600">Element ' . $j . ': ' . htmlspecialchars($element['formatterKey'] ?? 'N/A') . '</p>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                }
                echo '</div>';
            }
            
            echo '<details class="mt-4">';
            echo '<summary class="cursor-pointer text-blue-600 font-bold">View Full JSON</summary>';
            echo '<pre class="mt-2 bg-gray-900 text-green-400 p-4 rounded overflow-auto max-h-96 text-xs">';
            echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            echo '</pre>';
            echo '</details>';
            
            echo '</div>';
        }
        echo '</div>';
        
        // Test 2: Cartella Contatti
        echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
        echo '<h2 class="text-xl font-bold mb-4">2. Test Cartella Contatti</h2>';
        $url = $baseUrl . '/json/sites/mercury.local/contatti/?locale=en&fallbackLocale';
        echo '<p class="text-sm text-gray-600 mb-2"><strong>URL:</strong> ' . htmlspecialchars($url) . '</p>';
        
        $response = @file_get_contents($url);
        if ($response === false) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">';
            echo '❌ <strong>ERRORE:</strong> Cartella non trovata';
            echo '</div>';
        } else {
            $data = json_decode($response, true);
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">';
            echo '✅ <strong>Risposta ricevuta</strong>';
            echo '</div>';
            
            echo '<div class="space-y-2 text-sm">';
            echo '<p><strong>Contenuto cartella:</strong></p>';
            echo '<ul class="ml-4 list-disc">';
            foreach ($data as $key => $item) {
                $type = $item['attributes']['type'] ?? 'unknown';
                $isFolder = $item['isFolder'] ?? false;
                echo '<li>' . htmlspecialchars($key) . ' - Type: ' . htmlspecialchars($type) . ($isFolder ? ' (folder)' : '') . '</li>';
            }
            echo '</ul>';
            echo '</div>';
            
            echo '<details class="mt-4">';
            echo '<summary class="cursor-pointer text-blue-600 font-bold">View Full JSON</summary>';
            echo '<pre class="mt-2 bg-gray-900 text-green-400 p-4 rounded overflow-auto max-h-96 text-xs">';
            echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            echo '</pre>';
            echo '</details>';
        }
        echo '</div>';
        
        // Test 2b: Pagina Contatti index.html
        echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
        echo '<h2 class="text-xl font-bold mb-4">2b. Test Pagina Contatti index.html</h2>';
        $url = $baseUrl . '/json/sites/mercury.local/contatti/index.html?locale=en&fallbackLocale';
        echo '<p class="text-sm text-gray-600 mb-2"><strong>URL:</strong> ' . htmlspecialchars($url) . '</p>';
        
        $response = @file_get_contents($url);
        if ($response === false) {
            echo '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">';
            echo '⚠️ <strong>File index.html non trovato nella cartella contatti</strong><br>';
            echo 'Devi creare un file index.html dentro /contatti/ in OpenCMS';
            echo '</div>';
        } else {
            $data = json_decode($response, true);
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">';
            echo '✅ <strong>Risposta ricevuta</strong>';
            echo '</div>';
            
            echo '<div class="space-y-2 text-sm">';
            echo '<p><strong>Type:</strong> ' . htmlspecialchars($data['attributes']['type'] ?? 'N/A') . '</p>';
            echo '<p><strong>Containers:</strong> ' . count($data['containers'] ?? []) . '</p>';
            echo '</div>';
        }
        echo '</div>';
        
        // Test 2c: Model Group Header
        echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
        echo '<h2 class="text-xl font-bold mb-4">2c. Test Model Group (Header)</h2>';
        $url = $baseUrl . '/json/sites/mercury.local/.content/modelgroup/modelgroup-00003.html?locale=en&fallbackLocale';
        echo '<p class="text-sm text-gray-600 mb-2"><strong>URL:</strong> ' . htmlspecialchars($url) . '</p>';
        
        $response = @file_get_contents($url);
        if ($response === false) {
            echo '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">';
            echo '⚠️ <strong>Model group non trovato</strong>';
            echo '</div>';
        } else {
            $data = json_decode($response, true);
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">';
            echo '✅ <strong>Risposta ricevuta</strong>';
            echo '</div>';
            
            echo '<div class="space-y-2 text-sm">';
            echo '<p><strong>Type:</strong> ' . htmlspecialchars($data['attributes']['type'] ?? 'N/A') . '</p>';
            if (isset($data['containers'])) {
                echo '<p><strong>Containers:</strong> ' . count($data['containers']) . '</p>';
            }
            echo '</div>';
            
            echo '<details class="mt-4">';
            echo '<summary class="cursor-pointer text-blue-600 font-bold">View Full JSON</summary>';
            echo '<pre class="mt-2 bg-gray-900 text-green-400 p-4 rounded overflow-auto max-h-96 text-xs">';
            echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            echo '</pre>';
            echo '</details>';
        }
        echo '</div>';
        
        // Test 3: Lista Articoli
        echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
        echo '<h2 class="text-xl font-bold mb-4">3. Test Lista Articoli</h2>';
        $url = $baseUrl . '/json/sites/mercury.local/.content/article-m/?locale=en&fallbackLocale';
        echo '<p class="text-sm text-gray-600 mb-2"><strong>URL:</strong> ' . htmlspecialchars($url) . '</p>';
        
        $response = @file_get_contents($url);
        if ($response === false) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">';
            echo '❌ <strong>ERRORE:</strong> Impossibile caricare lista articoli';
            echo '</div>';
        } else {
            $data = json_decode($response, true);
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">';
            echo '✅ <strong>Risposta ricevuta</strong>';
            echo '</div>';
            
            echo '<div class="space-y-2 text-sm">';
            echo '<p><strong>Articoli trovati:</strong> ' . count($data ?? []) . '</p>';
            echo '</div>';
        }
        echo '</div>';
        
        // Test 4: API Client Test
        echo '<div class="bg-white rounded-lg shadow-md p-6">';
        echo '<h2 class="text-xl font-bold mb-4">4. Test API Client</h2>';
        
        require_once __DIR__ . '/../src/Api/ApiClient.php';
        $apiClient = new \App\Api\ApiClient();
        
        echo '<p class="text-sm mb-2"><strong>Testing loadContainerPage()...</strong></p>';
        $pageData = $apiClient->loadContainerPage('/sites/mercury.local/index.html', 'en');
        
        if ($pageData) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">';
            echo '✅ <strong>API Client funziona</strong>';
            echo '</div>';
            
            echo '<div class="space-y-2 text-sm">';
            echo '<p><strong>Containers:</strong> ' . count($pageData['containers'] ?? []) . '</p>';
            echo '<p><strong>Title:</strong> ' . htmlspecialchars($pageData['properties']['Title'] ?? 'N/A') . '</p>';
            echo '</div>';
            
            echo '<details class="mt-4">';
            echo '<summary class="cursor-pointer text-blue-600 font-bold">View API Client Response</summary>';
            echo '<pre class="mt-2 bg-gray-900 text-green-400 p-4 rounded overflow-auto max-h-96 text-xs">';
            echo htmlspecialchars(print_r($pageData, true));
            echo '</pre>';
            echo '</details>';
        } else {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">';
            echo '❌ <strong>API Client non funziona</strong>';
            echo '</div>';
        }
        
        echo '</div>';
        ?>
        
        <div class="mt-8 text-center">
            <a href="/" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                ← Torna alla Homepage
            </a>
        </div>
    </div>
</body>
</html>
