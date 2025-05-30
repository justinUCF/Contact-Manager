<?php
    // Read incoming JSON
    $inData = getRequestInfo();

    // Prepare JSON builders
    $searchResults = "";
    $searchCount   = 0;

    // Connect
    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
        exit();
    }

    // Query for all contacts of this user
    $stmt = $conn->prepare(
        "SELECT ID, FirstName, LastName, Phone, Email 
           FROM Contacts 
          WHERE UserID = ?"
    );
    $stmt->bind_param("i", $inData["UserID"]);
    $stmt->execute();
    $result = $stmt->get_result();

    // Build JSON array of contact objects
    while ($row = $result->fetch_assoc()) {
        if ($searchCount > 0) {
            $searchResults .= ",";
        }
        $searchCount++;
        $searchResults .= '{"ID":'   . $row["ID"] . 
                          ',"FirstName":"' . addslashes($row["FirstName"]) .
                          '","LastName":"'  . addslashes($row["LastName"])  .
                          '","Phone":"'     . addslashes($row["Phone"])     .
                          '","Email":"'     . addslashes($row["Email"])     .
                          '"}';
    }

    // Return either the list or “no records” error
    if ($searchCount == 0) {
        returnWithError("No Contacts Found");
    } else {
        returnWithInfo($searchResults);
    }

    // Cleanup
    $stmt->close();
    $conn->close();

    // ———————— helper functions ————————

    function getRequestInfo() {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj) {
        header('Content-Type: application/json');
        echo $obj;
    }

    function returnWithError($err) {
        $retValue = '{"id":0,"FirstName":"","LastName":"","Phone":"","Email":"","error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

    function returnWithInfo($searchResults) {
        $retValue = '{"results":[' . $searchResults . '],"error":""}';
        sendResultInfoAsJson($retValue);
    }
?>
