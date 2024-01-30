<?php
    $inData = getRequestInfo();

    $contactId = $inData["contactId"];
    $fieldToUpdate = $inData["fieldToUpdate"];
    $newValue = $inData["newValue"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 
    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        // Update contact field by ID
        $stmt = $conn->prepare("UPDATE Contacts SET $fieldToUpdate = ? WHERE ID = ?");
        $stmt->bind_param("si", $newValue, $contactId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            returnWithInfo("Contact updated successfully");
        } else {
            returnWithError("No contact found with the specified ID");
        }

        $stmt->close();
        $conn->close();
    }

    function getRequestInfo() {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err) {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

    function returnWithInfo($info) {
        $retValue = '{"info":"' . $info . '", "error":""}';
        sendResultInfoAsJson($retValue);
    }
?>