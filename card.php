<?php

// ZenoPay API endpoint
$url = 'https://api.zeno.africa/card';

// Prepare the data to be sent in the POST request
$data = array(
    'buyer_name'    => 'John Doe',
    'buyer_phone'   => '+254712345678',
    'buyer_email'   => 'johndoe@example.com',
    'amount'        => 1000,
    'billing.country' => 'TZ', 
    'account_id'    => 'zp87778',
    'webhook_url'   => 'https://example.com/success',
    'redirect_url'  => 'https://example.com/success',
    'cancel_url'    => 'https://example.com/cancel',
);

// Validate input data
$validationErrors = validateInput($data);
if (!empty($validationErrors)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Validation failed',
        'errors' => $validationErrors,
    ]);
    exit;
}

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
if (curl_errno($ch)) {
    logError('cURL Error: ' . curl_error($ch));
    echo json_encode(['status' => 'error', 'message' => 'Request failed']);
    curl_close($ch);
    exit;
}

// Check for successful response
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($httpCode !== 200) {
    logError("Error: Received HTTP code $httpCode from API.");
    echo json_encode(['status' => 'error', 'message' => 'API request failed']);
    curl_close($ch);
    exit;
}

// Close the cURL session
curl_close($ch);

// Decode the JSON response into an associative array
$responseData = json_decode($response, true);

// Log the response for debugging purposes
logResponse($responseData);

// Output the response (for debugging purposes)
echo json_encode([
    'status' => 'success',
    'message' => 'Request was successful',
    'data' => $responseData
]);

/**
 * Validate required fields in the input data.
 *
 * @param array $data The data array to validate.
 * @return array List of validation errors (if any).
 */
function validateInput($data) {
    $errors = [];

    // Validate required fields
    if (empty($data['buyer_name'])) {
        $errors[] = 'Buyer name is required';
    }
    if (empty($data['buyer_phone'])) {
        $errors[] = 'Buyer phone is required';
    }
    if (empty($data['buyer_email']) || !filter_var($data['buyer_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid buyer email is required';
    }
    if (empty($data['amount']) || !is_numeric($data['amount'])) {
        $errors[] = 'Amount must be a valid number';
    }
    if (empty($data['account_id'])) {
        $errors[] = 'Account ID is required';
    }
    if (empty($data['redirect_url']) || !filter_var($data['redirect_url'], FILTER_VALIDATE_URL)) {
        $errors[] = 'Valid redirect URL is required';
    }
    if (empty($data['cancel_url']) || !filter_var($data['cancel_url'], FILTER_VALIDATE_URL)) {
        $errors[] = 'Valid cancel URL is required';
    }

    return $errors;
}

/**
 * Log error messages for debugging.
 *
 * @param string $message The error message to log.
 */
function logError($message) {
    // Log error message to a file
    file_put_contents('error_log.txt', '[' . date('Y-m-d H:i:s') . '] ' . $message . "\n", FILE_APPEND);
}

/**
 * Log the API response for debugging.
 *
 * @param mixed $response The response to log.
 */
function logResponse($response) {
    // Log response data to a file
    file_put_contents('response_log.txt', '[' . date('Y-m-d H:i:s') . '] ' . print_r($response, true) . "\n", FILE_APPEND);
}

?>
