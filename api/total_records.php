<?php
include_once '../config.php';

function getTotalRecords()
{
    global $con;

    $query = 'SELECT COUNT(*) as total_records FROM users';
    $fetch = mysqli_query($con, $query);

    if ($fetch) {
        $result = mysqli_fetch_assoc($fetch);

        $data = [
            'status' => 200,
            'message' => 'Fetch total records successfully',
            'total_records' => $result['total_records']
        ];
        header('HTTP/1.0 200 Success');
        return json_encode($data);
    } else {
        $data = [
            'status' => 500,
            'message' => 'Server Error'
        ];
        header('HTTP/1.0 500 Server Error');
        return json_encode($data);
    }
}

echo getTotalRecords();

?>