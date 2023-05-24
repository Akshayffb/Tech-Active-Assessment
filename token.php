<?php

include_once 'config.php';

// Generate a token
$token = generateToken();

function generateToken() {
    // Generate a random token using your desired logic
    $token = bin2hex(random_bytes(16)); // Generate a 32-character random token

    return $token;
}

// Store the token in the database
$query = "INSERT INTO tokens (token) VALUES ('$token')";
$result = mysqli_query($con, $query);

if (!$result) {
    // Error occurred while storing the token
    $response = array(
        'success' => false,
        'message' => 'Error storing the token.'
    );
} else {
    // Token stored successfully
    $response = array(
        'success' => true,
        'token' => $token
    );
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tech Active - Generate Token</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Genrate Token</h2>
        <p>You can copy the generated token below:</p>
        <p><strong><?php echo $response['token']; ?></strong></p>
        <p>Click the button to proceed to the next page:</p>
        <a href="api/data.php?token=<?php echo $response['token']; ?>" class="btn btn-primary">Fetch Data</a>
        <a href="api/duplicate.php?token=<?php echo $response['token']; ?>" class="btn btn-primary">Show Duplicate Records</a>
    </div>
</body>
</html>
