<?php

// ZenoPay API endpoint
$url = 'https://api.zeno.africa/card';

// Data to be sent in the POST request
$data = array(
    'buyer_name' => 'John Doe',
    'buyer_phone' => '+254712345678',
    'buyer_email' => 'johndoe@example.com',
    'amount' => 1000,
    'account_id' => 'zp87778'
);

// Convert the data array to JSON
$jsonData = json_encode($data);

// Initialize cURL session
$ch = curl_init($url);

// Set the necessary cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return the response as a string
curl_setopt($ch, CURLOPT_POST, true);  // HTTP POST method
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);  // Attach the JSON data

// Set the request headers (Content-Type and Accept)
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',  // Indicate that we are sending JSON data
    'Accept: application/json'         // Expecting a JSON response
));

// Execute the POST request and get the response
$response = curl_exec($ch);

// Check if cURL encountered any errors
if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

// Close the cURL session
curl_close($ch);

// Decode the JSON response into an associative array
$responseData = json_decode($response, true);

// Output the response (for debugging purposes)
echo '<pre>';
print_r($responseData);
echo '</pre>';

?>
