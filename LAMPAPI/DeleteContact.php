<?php
    $inData = getRequestInfo();

    $contactId = $inData["contactId"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 
    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        // Delete contact by ID
        $stmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ?");
        $stmt->bind_param("i", $contactId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            returnWithInfo("Contact deleted successfully");
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