<!DOCTYPE html>
<html>

<head>
    <title>User Data</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">

</head>

<body>
    <div class="container mt-5">
        <h1>User Data</h1>
        <?php

        if (isset($_GET['data'])) {
            $data = json_decode($_GET['data'], true);
            $users = $data['data'];
            $totalPages = $data['total_pages'];
            $currentPage = $data['current_page'];
            $nextPage = $data['next_page'];
            $token = isset($_GET['token']) ? $_GET['token'] : '';
        ?>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['first_name']; ?></td>
                            <td><?php echo $user['last_name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <nav>
                <ul class="pagination">
                    <?php if ($currentPage > 1) { ?>
                        <li class="page-item">
                            <a class="page-link" href="userData.php?page=<?php echo $currentPage - 1; ?>&token=<?php echo $token; ?>">Previous</a>
                        </li>
                    <?php } ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                        <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                            <a class="page-link" href="userData.php?page=<?php echo $i; ?>&token=<?php echo $token; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($currentPage < $totalPages) { ?>
                        <li class="page-item">
                            <a class="page-link" href="userData.php?page=<?php echo $nextPage; ?>&token=<?php echo $token; ?>">Next</a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>

        <?php } else {
            echo "No data available.";
        }

        ?>
    </div>
</body>

</html>
