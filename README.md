# ZenoPay-Card-Php
### ZenoPay Card API Integration

## Overview

The ZenoPay Card API allows you to seamlessly integrate card payment functionality into your application. This API enables you to create and process payment transactions by sending buyer details, transaction amount, and a unique account identifier. The API will process the payment and return a response that indicates the success or failure of the transaction.

This repository contains a simple PHP implementation for interacting with the ZenoPay Card API, making it easy to integrate card payment services into your PHP-based applications.

Note: All amounts should be provided in TZS (Tanzanian Shillings).

---

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [API Documentation](#api-documentation)
  - [Endpoint](#endpoint)
  - [Request Format](#request-format)
  - [Response Format](#response-format)
  - [Error Handling](#error-handling)
- [PHP Example Code](#php-example-code)
- [How to Use](#how-to-use-the-example)
- [Webhook Handling](#webhook-handling)
- [License](#license)
- [Support](#support)

---

## Requirements

- PHP 7.0 or higher
- cURL extension enabled in PHP
- An active ZenoPay account to obtain API access credentials

Ensure that your PHP environment meets the following prerequisites:

1. PHP 7.0 or higher is required for compatibility with modern API features.
2. The cURL extension must be enabled to send HTTP requests to the ZenoPay API.

To check if cURL is installed and enabled, you can run:

```bash
php -m | grep curl
```

If the `curl` module is not installed, you can install it. On Ubuntu, use the following command:

```bash
sudo apt-get install php-curl
```

---

## Installation

### Step 1: Clone the Repository

Clone the repository or download the `zenopay-card-api` folder to your project directory.

```bash
git clone https://github.com/ZenoPay/ZenoPay-Card-Php.git
```

### Step 2: Enable cURL Extension in PHP

Ensure that the cURL extension is enabled. If you're using Ubuntu, you can install it as follows:

```bash
sudo apt-get install php-curl
```

For other systems, follow the appropriate method to enable cURL.

### Step 3: Configure API Credentials

Open the provided PHP example file and update the `buyer_name`, `buyer_phone`, `buyer_email`, `amount`, and `account_id` fields with the correct data. You’ll need to replace these values with real buyer information and the unique account ID provided by ZenoPay.

---

## API Documentation

### Endpoint

The ZenoPay Card API endpoint is:

```
POST https://api.zeno.africa/card
```

### Request Format

To initiate a payment transaction, send a **POST** request to the API with a JSON payload that includes the following parameters:

| Parameter        | Type    | Description                                        | Example               |
|------------------|---------|----------------------------------------------------|-----------------------|
| `buyer_name`     | string  | Full name of the buyer                             | `"John Doe"`          |
| `buyer_phone`    | string  | Phone number of the buyer                          | `"255712345678"`     |
| `buyer_email`    | string  | Email address of the buyer                         | `"johndoe@example.com"`|
| `amount`         | float   | Amount to be paid by the buyer in the transaction  | `1500.00`              |
| `billing.country`     | string  | country short code    | `"TZ , KE , US , UK , UG , etc"`      |
| `account_id`     | string  | Unique account identifier for the transaction      | `"acc_12345xyz"`      |
| `webhook_url`   | string   | URL to receive payment notifications               | `"https://example.com/webhook"` |
| `redirect_url`   | string  | URL to redirect to after successful payment        | `"https://example.com/success"` |
| `cancel_url`     | string  | URL to redirect to if the payment is cancelled     | `"https://example.com/cancel"`  |

**Note:** All fields are required unless otherwise specified.

### Response Format

The API responds with a JSON object. A successful response looks like this:

```json
{
    "status": "success",
    "transaction_id": "txn_abc123xyz",
    "message": "Payment processed successfully",
    "data": {
        "buyer_name": "John Doe",
        "buyer_phone": "255712345678",
        "buyer_email": "johndoe@example.com",
        "amount": 150.00,
        "billing.country" : "TZ", 
        "account_id": "acc_12345xyz"
    }
}
```

#### Response Parameters

| Parameter         | Type    | Description                                               | Example                     |
|-------------------|---------|-----------------------------------------------------------|-----------------------------|
| `status`          | string  | The status of the transaction (`success` or `failure`)    | `"success"`                 |
| `transaction_id`  | string  | The unique transaction identifier                         | `"txn_abc123xyz"`           |
| `message`         | string  | A message describing the result of the transaction        | `"Payment processed successfully"` |
| `data`            | object  | The transaction data that was sent with the request       | See example above           |

### Error Handling

If there's an issue with the request or the processing of the transaction, the response might look like:

```json
{
    "status": "error",
    "message": "Invalid account_id"
}
```

#### Error Parameters

| Parameter    | Type   | Description                            | Example                     |
|--------------|--------|----------------------------------------|-----------------------------|
| `status`     | string | The error status (`error`)             | `"error"`                   |
| `message`    | string | A description of the error             | `"Invalid account_id"`      |

---

## PHP Example Code

The following PHP code demonstrates how to interact with the ZenoPay Card API.

```php
<?php

// ZenoPay API endpoint
$url = 'https://api.zeno.africa/card';

// Data to be sent in the POST request
$data = array(
    'buyer_name'   => 'John Doe',
    'buyer_phone'  => '255712345678',
    'buyer_email'  => 'johndoe@example.com',
    'amount'       => 15000,
    'billing.country' => 'TZ', 
    'account_id'   => 'your_account_id',
    'api_key'      =>'api_secret_key',
    'secret_key'   =>'your_secret_key',
    'webhook_url'  => 'https://example.com/webhook',
    'redirect_url' => 'https://example.com/success',
    'cancel_url'   => 'https://example.com/cancel',
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
    exit;
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
```

### How to Use the Example

1. **Edit the Parameters**: Modify the `buyer_name`, `buyer_phone`, `buyer_email`, `amount`, `account_id`, `redirect_url`, and `cancel_url` fields in the `$data` array with your own test data or real data from your application.
   
2. **Run the Script**: Upload the script to a server that supports PHP and has cURL enabled. Then, execute it via a browser or a command-line tool to make the POST request to the ZenoPay API.

3. **View the Response**: After executing the script, the response from the API will be printed. If the transaction is successful, it will contain transaction details. Otherwise, it will include error information.

---

## Webhook Handling

After a payment is processed, ZenoPay may send a **webhook** to notify you about the status of the transaction. You can use the following PHP code to handle the webhook and log the incoming data for further processing or debugging.

### Webhook PHP Example

```php
<?php

// Webhook handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data from the incoming webhook
    $data = file_get_contents('php://input');
    
    // Log the raw data with a timestamp
    file_put_contents('weblogs.txt', "[" . date('Y-m-d H:i:s') . "] WebHook Data: ".$data."\n", FILE_APPEND);
}

?>
```

### How the Webhook Works:
1. **Receiving Webhook Data**: This script listens for incoming **POST** requests (which ZenoPay sends for webhooks

) and reads the raw data from the request body using `file_get_contents('php://input')`.
   
2. **Logging Webhook Data**: The webhook data is logged to a file (`weblogs.txt`) along with a timestamp for reference. This log will help you debug and track transaction statuses or other data sent via the webhook.

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

---

## Support

For any issues or questions, please contact us at [support@zeno.africa](mailto:support@zeno.africa).

---

## Final Thoughts

This guide provides an easy-to-follow approach to integrating ZenoPay Card API into your PHP-based applications. With this integration, you can begin processing payments securely and efficiently. Always ensure that the `amount`, `buyer_email`, and other parameters are properly sanitized and validated in your production environment to prevent security issues.
