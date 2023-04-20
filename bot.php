<?php

// Telegram bot token
define('TELEGRAM_BOT_TOKEN', 'YOUR_BOT_TOKEN_HERE');

// Telegram API URL
define('TELEGRAM_API_URL', 'https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/');

// Get incoming message data
$update = json_decode(file_get_contents('php://input'), true);

// Check if there is a new incoming message
if (isset($update['message'])) {
    $message = $update['message'];
    $chatId = $message['chat']['id'];
    $text = $message['text'];
    
    // Check if the message text contains a food item
    if (!empty($text)) {
        // Get food price
        $foodPrice = getFoodPrice($text);
        
        if ($foodPrice !== null) {
            // Prepare response message
            $response = "The price of " . ucfirst($text) . " is $" . number_format($foodPrice, 2);
        } else {
            // If food item not found, provide a default response
            $response = "We do not have information about the price of " . ucfirst($text) . " at the moment.";
        }
        
        // Send response message
        sendMessage($chatId, $response);
    }
}

// Helper function to send a message using Telegram API
function sendMessage($chatId, $message)
{
    $url = TELEGRAM_API_URL . 'sendMessage?chat_id=' . $chatId . '&text=' . urlencode($message);
    file_get_contents($url);
}

// Helper function to get food price
function getFoodPrice($foodItem)
{
    // Add your logic here to fetch food price from a database or API
    // For example:
    $foodPrices = array(
        'burger' => 5.99,
        'pizza' => 8.99,
        'pasta' => 12.99,
        'salad' => 7.99
    );

    // Check if the food item exists in the array
    if (array_key_exists($foodItem, $foodPrices)) {
        return $foodPrices[$foodItem];
    } else {
        return null;
    }
}

?>