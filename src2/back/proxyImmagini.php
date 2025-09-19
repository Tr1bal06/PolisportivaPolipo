<?php
/**
 * File: proxyImmagini.php
 * Auth: Alberto Magrini
 * Desc: Script PHP per fare da proxy e in caso forzare il content type alle immagini ospitate su Google Drive
 */
$fileId = $_GET['id'] ?? '1PZ3EY68XmiTCwX6Oput1HcEmr66OWntC';

$url = "https://drive.google.com/uc?export=download&id=" . urlencode($fileId);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$data = curl_exec($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);


if (!$data) {
    header("HTTP/1.1 404 Not Found");
    echo "Immagine non trovata.";
    exit;
}

if (!$contentType) {
    $contentType = "image/jpeg";
}

header("Content-Type: " . $contentType);
echo $data;
