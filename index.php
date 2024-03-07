<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Methods: *');
    include 'dbc.php';

   $conn = new DbConnect();
    $db = $conn->connect();
    $method = $_SERVER['REQUEST_METHOD'];

    switch($method) {
        case 'POST':
            $user = json_decode(file_get_contents('php://input'));
            $sql = "INSERT INTO users( name, email) values(:name, :email)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':name', $user->name);
            $stmt->bindParam(':email', $user->email);
            if($stmt->execute()) {
                $data = ['status' => 1, 'message' => "successfully created"];
            } else {
                $data = ['status' => 0, 'message' => "Failed to create ."];
            }
            echo json_encode($data);
            break;
}
    
?>