<?php
	$inData = getRequestInfo();

	$UserID = $inData["UserID"];
	$FirstName = $inData["FirstName"];
	$LastName = $inData["LastName"];
	$Phone = $inData["Phone"];
	$Email = $inData["Email"];
	

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

	if ($conn->connect_error) 
	{
		returnWithError($conn->connect_error);
	} 
	else
	{
		//Check for duplicate Contact
		$checkStmt = $conn->prepare("SELECT ID FROM Contacts WHERE UserID = ? AND FirstName = ? AND LastName = ? AND Phone = ? AND Email = ?");
		$checkStmt->bind_param("issss", $UserID, $FirstName, $LastName, $Phone, $Email);
		$checkStmt->execute();
		$checkStmt->store_result();

		if ($checkStmt->num_rows > 0)
		{
			$checkStmt->close();
			$conn->close();
			returnWithError("Contact already exists.");
			exit();
		}
		else
		{
			$checkStmt->close();
			$stmt = $conn->prepare("INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("ssssi", $FirstName, $LastName, $Phone, $Email, $UserID);
			$stmt->execute();
			$contact = array(
				"FirstName" => $FirstName,
				"LastName" => $LastName,
				"Phone" => $Phone,
				"Email" => $Email,
				"UserID" => $UserID
			);
			$stmt->close();
			$conn->close();
			returnWithSuccess("Contact created successfully.");
			exit();
		}
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

	function returnWithSuccess($msg, $contact = null)
	{
	if ($contact) {
		$contact["success"] = $msg;
		$retValue = json_encode($contact);
	} else {
		$retValue = '{"success":"' . $msg . '"}';
	}
	sendResultInfoAsJson($retValue);
	}
?>