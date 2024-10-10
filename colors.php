<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "sql12.freesqldatabase.com";
$db_name = "sql12736686";
$username = "sql12736686";   
$password = "mahs96aGR7";       

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check the request method
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'GET') {
        // Fetch the color settings
        $query = "SELECT `id`, `backgroundColor`, `titleColor`, `textfieldColor`, `loginColor`, `signinColor`, `logintextColor`, `signintextColor` FROM `theme`";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No colors found']);
        }
    } 

    elseif ($method == 'POST') {
        // Update the color settings
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['id']) && isset($data['backgroundColor']) && isset($data['titleColor']) &&
            isset($data['textfieldColor']) && isset($data['loginColor']) && isset($data['signinColor']) &&
            isset($data['logintextColor']) && isset($data['signintextColor'])) {

            // Prepare the UPDATE query
            $query = "UPDATE `colors` SET 
                `backgroundColor` = :backgroundColor,
                `titleColor` = :titleColor,
                `textfieldColor` = :textfieldColor,
                `loginColor` = :loginColor,
                `signinColor` = :signinColor,
                `logintextColor` = :logintextColor,
                `signintextColor` = :signintextColor
                WHERE `id` = :id";

            $stmt = $conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':backgroundColor', $data['backgroundColor']);
            $stmt->bindParam(':titleColor', $data['titleColor']);
            $stmt->bindParam(':textfieldColor', $data['textfieldColor']);
            $stmt->bindParam(':loginColor', $data['loginColor']);
            $stmt->bindParam(':signinColor', $data['signinColor']);
            $stmt->bindParam(':logintextColor', $data['logintextColor']);
            $stmt->bindParam(':signintextColor', $data['signintextColor']);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

            // Execute the query
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Color settings updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update color settings']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        }
    } 

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
