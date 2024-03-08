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
            case 'GET':
                $sql = "SELECT * FROM users";
                $path = explode('/', $_SERVER['REQUEST_URI']);
                if (isset($path[3]) && is_numeric($path[3])) {
                    $sql .= " WHERE id = :id";
                    $stmt = $db->prepare($sql); 
                    $stmt->bindParam(':id', $path[3]);
                    $stmt->execute();
                    $users = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $stmt = $db->prepare($sql); 
                    $stmt->execute();
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            
                echo json_encode($users);
                break;

                case 'PUT':
                    $user = json_decode(file_get_contents('php://input'));
                    $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':name', $user->name);
                    $stmt->bindParam(':email', $user->email);
                    $stmt->bindParam(':id', $user->id);
                    
                    if($stmt->execute()) {
                        $response = ['status' => 1, 'message' => 'Record updated successfully.'];
                    } else {
                        $response = ['status' => 0, 'message' => 'Failed to update record.'];
                    }
                    echo json_encode($response);
                    break;

                    case "DELETE": 
                        $sql = "DELETE FROM users WHERE id = :id";
                        $path = explode('/', $_SERVER['REQUEST_URI']);
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':id', $path[3]);
                        if ($stmt->execute()) {
                            $response = ['status' => 1, 'message' => 'deleted successfully.'];
                        } else {
                            $response = ['status' => 0, 'message' => 'Failed delete.'];
                        }
                        echo json_encode($response);
                        break;
                    
            
                
                
            


              
}
    
?>