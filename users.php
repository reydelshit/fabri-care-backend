<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();
date_default_timezone_set('Asia/Manila');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        if (!isset($_GET['user_Id'])) {
            $sql = "SELECT * FROM users";
        }

        if (isset($_GET['user_Id'])) {
            $user_Id = $_GET['user_Id'];
            $sql = "SELECT users.fullname, uploads.upload_date, users.user_Id, uploads.upload_longblob FROM users INNER JOIN uploads ON users.user_Id = uploads.user_id WHERE users.user_Id = :user_Id";
        }


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($user_Id)) {
                $stmt->bindParam(':user_Id', $user_Id);
            }

            $stmt->execute();
            $student = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($student);
        }


        break;


    case "DELETE":
        $user = json_decode(file_get_contents('php://input'));
        $sql = "DELETE FROM users WHERE user_Id = :user_Id";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':user_Id', $user->user_Id);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "user_Id deleted successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "user_Id delete failed"
            ];
        }

        echo json_encode($response);
        break;
}
