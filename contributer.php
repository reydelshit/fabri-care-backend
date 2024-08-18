<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();
date_default_timezone_set('Asia/Manila');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        $sql = "SELECT 
                fullname,
                (SELECT COUNT(*) 
                FROM uploads 
                WHERE uploads.user_id = users.user_id) AS uploaded_image,
                (SELECT DAYNAME(upload_date) 
                FROM uploads 
                WHERE uploads.user_id = users.user_id 
                GROUP BY DAYNAME(upload_date) 
                ORDER BY COUNT(*) DESC 
                LIMIT 1) AS day_most_used
            FROM 
                users
            GROUP BY 
                fullname;";

        if (isset($sql)) {
            $stmt = $conn->prepare($sql);



            $stmt->execute();
            $student = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($student);
        }


        break;
}
