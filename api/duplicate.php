<!DOCTYPE html>
<html>

<head>
    <title>Tech Active - User Data</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>User Data</h1>
        <div class="container d-flex justify-content-center">
        </div>

        <?php
        include_once './duplicate-func.php';

        // Verify the token
        $token = $_GET['token'];
        $validToken = verifyToken($token);

        if ($validToken) {
            // Retrieve the user count using the function
            $userCountData = getUserCount();
            $totalUsers = $userCountData['total_users'];

            // Retrieve the count of records in duplicate_count table
            $duplicateCountData = getDuplicateCount();
            $totalDuplicates = $duplicateCountData['total_duplicates'];

            if ($userCountData['status'] === 200 && $duplicateCountData['status'] === 200) {
                echo "<p>Total number of records: $totalUsers</p>";

                echo "<h2>Duplicate Percentage</h2>";
                echo "<p>Duplicate email percentage: " . calculateDuplicatePercentage($totalDuplicates, $totalUsers) . "%</p>";
            } else {
                echo "<p>Unable to fetch data.</p>";
            }
        } else {
            echo "<p>Invalid token.</p>";
        }


        function verifyToken($token)
        {
            global $con;

            $query = "SELECT token FROM tokens WHERE token = '$token'";
            $result = mysqli_query($con, $query);

            return ($result && mysqli_num_rows($result) > 0);
        }

        function getDuplicateCount()
        {
            global $con;

            $query = 'SELECT COUNT(*) AS total_duplicates FROM duplicate_count';
            $fetch = mysqli_query($con, $query);

            if ($fetch) {
                $result = mysqli_fetch_assoc($fetch);
                $totalDuplicates = $result['total_duplicates'];

                $data = [
                    'status' => 200,
                    'message' => 'Fetch duplicate count successfully',
                    'total_duplicates' => $totalDuplicates
                ];
                header('HTTP/1.0 200 Success');
                return $data;
            } else {
                $data = [
                    'status' => 500,
                    'message' => 'Server Error'
                ];
                header('HTTP/1.0 500 Server Error');
                return $data;
            }
        }

        function calculateDuplicatePercentage($totalDuplicates, $totalUsers)
        {
            if ($totalUsers > 0) {
                $percentage = ($totalDuplicates / $totalUsers) * 100;
                return round($percentage, 2);
            }
            return 0;
        }
        ?>

<span class="text-muted">API: <input type="text" class="form-control" readonly value="<?php echo "http://localhost/techactive/api/data.php?" . $_SERVER['QUERY_STRING']; ?>"> </span>
    </div>
</body>

</html>
