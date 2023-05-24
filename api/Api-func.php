<?php
include_once '../config.php';

function getUserData(){
    global $con;

    $query = 'SELECT * FROM users';
    $fetch = mysqli_query($con, $query);

    if($fetch){
        if(mysqli_num_rows($fetch) > 0){
            $result = mysqli_fetch_all($fetch, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'Fetch data successfully',
                'data' => $result
            ];
            header('HTTP/1.0 200 Success');
            return json_encode($data);
        }
        else{
            $data = [
                'status' => 404,
                'message' => 'No user found'
            ];
            header('HTTP/1.0 404 No user found');
            return json_encode($data);
        }
    }
    else{
        $data = [
            'status' => 500,
            'message' => 'Server Error'
        ];
        header('HTTP/1.0 500 Server Error');
        return json_encode($data);

    }
}

?>