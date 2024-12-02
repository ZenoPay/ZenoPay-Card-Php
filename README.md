# ZenoPay-Card-Php
# ZenoPay Card API Integration

## Overview

The ZenoPay Card API allows you to integrate card payment functionality into your application. With this API, you can create a payment transaction by sending buyer details, the amount to be paid, and a unique account identifier. The API processes the payment and returns a response indicating the success or failure of the transaction.

This repository contains an example PHP implementation for interacting with the ZenoPay Card API.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [API Documentation](#api-documentation)
  - [Endpoint](#endpoint)
  - [Request Format](#request-format)
  - [Response Format](#response-format)
  - [Error Handling](#error-handling)
- [PHP Example Code](#php-example-code)
- [License](#license)

---

## Requirements

- PHP 7.0 or higher
- cURL extension enabled in PHP
- An active ZenoPay account to get the API access details

## Installation

1. Clone the repository or download the `zenopay-card-api` folder.
   ```bash
   git clone https://github.com/ZenoPay/ZenoPay-Card-Php.git
   ```
   
2. Ensure your PHP environment has the required cURL extension enabled. You can check this by running the following command:
   ```bash
   php -m | grep curl
   ```
   If it's not installed, you can install it based on your system. For example, on Ubuntu:
   ```bash
   sudo apt-get install php-curl
   ```

3. Modify the PHP code to include your ZenoPay API details.

---

## API Documentation

### Endpoint

The ZenoPay Card API endpoint is:

```
POST https://api.zeno.africa/card
```

### Request Format

To initiate a payment transaction, send a POST request to the API with a JSON payload that includes the following parameters:

| Parameter        | Type    | Description                                        | Example               |
|------------------|---------|----------------------------------------------------|-----------------------|
| `buyer_name`     | string  | Full name of the buyer                             | `"John Doe"`          |
| `buyer_phone`    | string  | Phone number of the buyer                          | `"+254712345678"`     |
| `buyer_email`    | string  | Email address of the buyer                         | `"johndoe@example.com"`|
| `amount`         | float   | Amount to be paid by the buyer in the transaction  | `150.00`              |
| `account_id`     | string  | Unique account identifier for the transaction      | `"acc_12345xyz"`      |

### Response Format

The API responds with a JSON object. Here’s an example of a successful transaction:

```json
{
    "status": "success",
    "transaction_id": "txn_abc123xyz",
    "message": "Payment processed successfully",
    "data": {
        "buyer_name": "John Doe",
        "buyer_phone": "+254712345678",
        "buyer_email": "johndoe@example.com",
        "amount": 150.00,
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

In case of an error, the response might look like:

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
    'buyer_name' => 'John Doe',
    'buyer_phone' => '+254712345678',
    'buyer_email' => 'johndoe@example.com',
    'amount' => 150.00,
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
```

### How to Use the Example

1. Replace the `buyer_name`, `buyer_phone`, `buyer_email`, `amount`, and `account_id` fields with your own data.
2. Run the script on a server with PHP and cURL enabled.
3. The script will send the POST request to the ZenoPay Card API and display the JSON response.

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Support

For any issues or questions, please contact support at [support@zeno.africa](mailto:support@zeno.africa).
