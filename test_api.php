<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Mock Laravel application for Http facade to work if possible, 
// or just use Guzzle directly for the test.
// Since this is easier, I'll use native curl/file_get_contents if I can't easily bootstrap Laravel.

$nip = '198504262011011002'; // Example from screenshot
$url = "https://api-splp.layanan.go.id/t/bengkulukota.go.id/data_kinerja/1.0/api/pegawai/{$nip}/get_pegawai";

echo "Testing URL: $url\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status Code: $httpCode\n";
echo "Response Body:\n";
echo $response . "\n";
