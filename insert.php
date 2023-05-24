<?php

include_once 'config.php';

if (isset($_POST['submit'])) {
    if ($_FILES['file']['name']) {
        $filename = explode('.', $_FILES['file']['name']);
        if ($filename[1] == 'csv') {
            if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $handle = fopen($_FILES['file']['tmp_name'], 'r');

                // Skip the header row
                fgetcsv($handle);

                while ($data = fgetcsv($handle)) {
                    $id = mysqli_real_escape_string($con, $data[0]);
                    $first_name = mysqli_real_escape_string($con, $data[1]);
                    $last_name = mysqli_real_escape_string($con, $data[2]);
                    $email = mysqli_real_escape_string($con, $data[3]);
                    $gender = mysqli_real_escape_string($con, $data[4]);
                    $ip_address = mysqli_real_escape_string($con, $data[5]);

                    // Check if the email already exists in the users table
                    $existingEmailQuery = "SELECT COUNT(*) as count FROM users WHERE email = '$email'";
                    $result = mysqli_query($con, $existingEmailQuery);
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['count'];

                    if ($count > 0) {
                        // Email already exists, insert into duplicate table
                        $duplicateQuery = "INSERT INTO duplicate_count (email, count) VALUES ('$email', $count + 1)";
                        mysqli_query($con, $duplicateQuery);
                    } else if (!empty($email)) {
                        // Email is unique, insert into users table
                        $query = "INSERT INTO users (id, first_name, last_name, email, gender, ip_address) VALUES ('$id', '$first_name', '$last_name', '$email', '$gender', '$ip_address')";
                        mysqli_query($con, $query);
                    }
                }

                fclose($handle);

                // Set success message
                $message = 'Data inserted successfully.';
                header("Location: index.php?success=" . urlencode($message));
                exit();
            } else {
                // Set error message for file upload error
                $message = 'Error uploading the file.';
                header("Location: index.php?error=" . urlencode($message));
                exit();
            }
        } else {
            // Set error message for invalid file format
            $message = 'Invalid file format. Only CSV files are allowed.';
            header("Location: index.php?error=" . urlencode($message));
            exit();
        }
    } else {
        // Set error message for no file selected
        $message = 'No file selected.';
        header("Location: index.php?error=" . urlencode($message));
        exit();
    }
}
