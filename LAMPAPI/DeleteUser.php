<?php
	$inData = getRequestInfo();
	
    $ID = $inData["ID"];

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("SELECT ID FROM Users WHERE ID = ?");
		$stmt->bind_param("i", $ID);
		$stmt->execute();
		$stmt->store_result();

		if($stmt->num_rows <= 0){
			$stmt->close();
			$conn->close();
			returnWithError("User does not exist");
		}
		else{
			$stmt = $conn->prepare(
				"DELETE FROM Users WHERE ID = ?"
			);
			$stmt->bind_param("i", $ID);
			$stmt->execute();
			$stmt->close();
			$conn->close();
			returnWithSuccess("User deleted.");
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