<?php
    $inData = getRequestInfo();

    $ID = $inData["ID"];
    $UserID = $inData["UserID"];
    $FirstName = $inData["FirstName"];
    $LastName = $inData["LastName"];
    $Phone = $inData["Phone"];
    $Email = $inData["Email"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        $stmt = $conn->prepare("UPDATE Contacts SET FirstName=?, LastName=?, Phone=?, Email=? WHERE ID=? AND UserID=?");
        $stmt->bind_param("ssssii", $FirstName, $LastName, $Phone, $Email, $ID, $UserID);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            returnWithSuccess("Contact updated successfully.");
        } else {
            returnWithError("No changes made or contact not found.");
        }

        $stmt->close();
        $conn->close();
    }

    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err)
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

    function returnWithSuccess($msg)
    {
        $retValue = '{"success":"' . $msg . '"}';
        sendResultInfoAsJson($retValue);
    }
?>