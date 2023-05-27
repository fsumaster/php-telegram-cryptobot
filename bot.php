<?php

$apiToken = '';
$chatId = '';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=10&page=1&sparkline=false');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo "Error: Failed to retrieve data from API.";
    exit;
}

$data = json_decode($response);

if ($data === null) {
    echo "Error: Failed to parse JSON data.";
    exit;
}

$message = "Top 10 cryptocurrencies by market cap:\n\n";

if (!empty($data) && is_array($data)) {
    foreach ($data as $coin) {
        $message .= $coin->name . " (" . $coin->symbol . "):\n";
        $message .= "Current price: $" . number_format($coin->current_price, 2) . "\n";
        $message .= "Price change in the last 24 hours: " . $coin->price_change_percentage_24h . "%\n";
        $message .= "Market cap: $" . number_format($coin->market_cap, 0) . "\n";
        $message .= "Total volume: $" . number_format($coin->total_volume, 0) . "\n\n";
    }
} else {
    echo "Error: No data found.";
    exit;
}

$url = "https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$chatId&text=" . urlencode($message);

file_get_contents($url);
