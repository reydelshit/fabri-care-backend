<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();
date_default_timezone_set('Asia/Manila');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        if (!isset($_GET['feedback_id'])) {
            $sql = "SELECT * FROM feedbacks";
        }

        if (isset($_GET['feedback_id'])) {
            $student_id = $_GET['feedback_id'];
            $sql = "SELECT * FROM feedbacks WHERE feedback_id = :feedback_id";
        }


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($feedback_id)) {
                $stmt->bindParam(':feedback_id', $feedback_id);
            }

            $stmt->execute();
            $student = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($student);
        }


        break;

    case "POST":
        $feedback = json_decode(file_get_contents('php://input'));

        $sql = "INSERT INTO feedbacks (feedback_id, feedback_message, feedback_rate, feedback_date, fullname) 
                        VALUES (:feedback_id, :feedback_message, :feedback_rate, :feedback_date, :fullname)";
        $stmt = $conn->prepare($sql);
        $feedback_date = date('Y-m-d');

        $stmt->bindParam(':feedback_id', $feedback->feedback_id);
        $stmt->bindParam(':feedback_message', $feedback->feedback_message);
        $stmt->bindParam(':feedback_rate', $feedback->feedback_rate);
        $stmt->bindParam(':feedback_date', $feedback_date);
        $stmt->bindParam(':fullname', $feedback->fullname);


        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "Feedback submitted successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Feedback submission failed"
            ];
        }

        echo json_encode($response);
        break;



    case "DELETE":
        $user = json_decode(file_get_contents('php://input'));
        $sql = "DELETE FROM feedbacks WHERE feedback_id = :feedback_id";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':feedback_id', $user->feedback_id);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "feedback_id deleted successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "feedback_id delete failed"
            ];
        }

        echo json_encode($response);
        break;
}
