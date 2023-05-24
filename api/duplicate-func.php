<?php
include_once '../config.php';

function getUserCount()
{
    global $con;

    $query = 'SELECT COUNT(*) AS total_count FROM users';
    $fetch = mysqli_query($con, $query);

    if ($fetch) {
        $result = mysqli_fetch_assoc($fetch);
        $totalCount = $result['total_count'];

        $data = [
            'status' => 200,
            'message' => 'Fetch user count successfully',
            'total_users' => $totalCount
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


function getDuplicatePercentage()
{
    global $con;

    // Get total record count
    $query = 'SELECT COUNT(*) as total_records FROM duplicate_count';
    $fetch = mysqli_query($con, $query);

    if ($fetch) {
        $result = mysqli_fetch_assoc($fetch);
        $totalRecords = $result['total_records'];

        // Get duplicate record count
        $query = 'SELECT COUNT(*) as duplicate_count FROM duplicate_count GROUP BY email HAVING COUNT(*) > 1';
        $fetch = mysqli_query($con, $query);

        if ($fetch) {
            $result = mysqli_num_rows($fetch);
            $duplicateCount = $result;

            // Calculate duplicate percentage
            $duplicatePercentage = ($duplicateCount / $totalRecords) * 100;

            $data = [
                'status' => 200,
                'message' => 'Fetch duplicate percentage successfully',
                'duplicate_percentage' => $duplicatePercentage,
                'total_records' => $totalRecords
            ];
            header('HTTP/1.0 200 Success');
            return $data;
        } else {
            $data = [
                'status' => 500,
                'message' => 'Error in fetching duplicate count'
            ];
            header('HTTP/1.0 500 Server Error');
            return $data;
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Error in fetching total record count'
        ];
        header('HTTP/1.0 500 Server Error');
        return $data;
    }
}
