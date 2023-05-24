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

        <form class="mb-3" method="GET" action="data.php">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search by first name, last name, or email">
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="<?php echo "http://localhost/data.php?" . $_SERVER['QUERY_STRING']; ?>" class="btn btn-primary"> Home</a>
                </div>
            </div>
        </form>

        <?php
        include_once './Api-func.php';

        $ReqMethod = $_SERVER['REQUEST_METHOD'];

        if ($ReqMethod == 'GET') {
            $token = $_GET['token'];

            // Verify the token
            if (verifyToken($token)) {
                $page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the page number from the URL parameter
                $limit = 10; // Number of records per page
                $search = isset($_GET['search']) ? $_GET['search'] : ''; // Get the search term from the URL parameter

                // Get paginated user data based on search term
                $responseData = getPaginatedUserData($page, $limit, $search);

                $users = $responseData['data'];
                $totalPages = $responseData['total_pages'];
                $currentPage = $responseData['current_page'];
                $nextPage = $responseData['next_page'];

                if (!empty($users)) {
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) { ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo $user['first_name']; ?></td>
                                    <td><?php echo $user['last_name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['gender']; ?></td>
                                    <td><?php echo $user['ip_address']; ?></td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <nav>
                        <ul class="pagination">
                            <?php if ($currentPage > 1) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="data.php?page=<?php echo $currentPage - 1; ?>&token=<?php echo urlencode($token); ?>">Previous</a>
                                </li>
                            <?php } ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                    <a class="page-link" href="data.php?page=<?php echo $i; ?>&token=<?php echo urlencode($token); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($currentPage < $totalPages) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="data.php?page=<?php echo $nextPage; ?>&token=<?php echo urlencode($token); ?>">Next</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                <?php
                } else {
                    echo "<p>No data available.</p>";
                }
            } else {
                $data = [
                    'status' => 401,
                    'message' => 'Invalid token'
                ];
                header('HTTP/1.0 401 Unauthorized');
                echo json_encode($data);
            }
        } else {
            $data = [
                'status' => 405,
                'message' => $ReqMethod . 'This method is not allowed'
            ];
            header('HTTP/1.0 405 Not Allowed');
            echo json_encode($data);
        }

        function verifyToken($token)
        {
            global $con;

            $query = "SELECT token FROM tokens WHERE token = '$token'";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                return true; // Token is valid
            } else {
                return false; // Token is invalid
            }
        }

        function getPaginatedUserData($page, $limit, $search)
        {
            global $con;

            // Calculate the offset based on the page number and limit
            $offset = ($page - 1) * $limit;

            // Prepare the search condition
            $searchCondition = '';
            if (!empty($search)) {
                $search = mysqli_real_escape_string($con, $search);
                $searchCondition = "WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%'";
            }

            // Fetch the paginated user data
            $query = "SELECT * FROM users $searchCondition LIMIT $limit OFFSET $offset";
            $result = mysqli_query($con, $query);

            $data = array();

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }

            // Get the total number of records for pagination
            $totalCountQuery = "SELECT COUNT(*) as total FROM users $searchCondition";
            $totalCountResult = mysqli_query($con, $totalCountQuery);
            $totalCountRow = mysqli_fetch_assoc($totalCountResult);
            $totalRecords = $totalCountRow['total'];

            // Calculate the total number of pages
            $totalPages = ceil($totalRecords / $limit);

            // Calculate the next page number
            $nextPage = ($page < $totalPages) ? $page + 1 : $totalPages;

            $responseData = array(
                'data' => $data,
                'total_pages' => $totalPages,
                'current_page' => $page,
                'next_page' => $nextPage
            );

            return $responseData;
        }
        ?>
        <span class="text-muted">API: <input type="text" class="form-control" readonly value="<?php echo "http://localhost/techactive/api/data.php?" . $_SERVER['QUERY_STRING']; ?>"> </span>

    </div>
</body>

</html>
