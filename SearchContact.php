<?php
    $inData = getRequestInfo();

    $searchParam = $inData["searchParam"];
    $searchField = $inData["searchField"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 
    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        // Search for contacts based on the specified parameter and field
        $stmt = $conn->prepare("SELECT * FROM Contacts WHERE $searchField LIKE ?");
        $searchParam = "%$searchParam%";
        $stmt->bind_param("s", $searchParam);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $contacts = array();

            while ($row = $result->fetch_assoc()) {
                $contacts[] = $row;
            }

            returnWithInfo($contacts);
        } else {
            returnWithError("No contacts found matching the specified parameter");
        }

        $stmt->close();
        $conn->close();
    }

    function getRequestInfo() {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo json_encode($obj);
    }

    function returnWithError($err) {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

    function returnWithInfo($info) {
        $retValue = '{"contacts":' . json_encode($info) . ', "error":""}';
        sendResultInfoAsJson($retValue);
    }
?>


