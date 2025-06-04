<?php
	$inData = getRequestInfo();
	
	$UserID = $inData["UserID"];
    $ID = $inData["ID"];
	error_log("DeleteContact: UserID=$UserID, ID=$ID");

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("SELECT ID FROM Contacts WHERE UserID = ? AND ID = ?");
		$stmt->bind_param("ii", $UserID, $ID);
		$stmt->execute();
		$stmt->store_result();

		if($stmt->num_rows <= 0){
			$stmt->close();
			$conn->close();
			returnWithError("Contact does not exist");
		}
		else{
			$stmt = $conn->prepare("DELETE FROM Contacts WHERE UserID = ? AND ID = ?");
			$stmt->bind_param("ii", $UserID, $ID);
		if ($stmt->execute()) {
            error_log("DeleteContact: Contact deleted.");
            returnWithSuccess("Contact deleted.");
        } else {
            error_log("DeleteContact error: " . $stmt->error);
            returnWithError("Failed to delete contact.");
        }
			$stmt->close();
			$conn->close();
		}
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithSuccess($msg)
	{
		$retValue = '{"success":"' . $msg . '"}';
		sendResultInfoAsJson($retValue);
	}
	
?>