<?php
	$inData = getRequestInfo();
	
	$firstName = $inData["firstName"];
	$lastName = $inData["lastName"];
	$phone = $inData["phone"];
	$email = $inData["email"];
	$userId = $inData["userId"];

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

	if ($conn->connect_error) 
	{
		returnWithError($conn->connect_error);
	} 
	else
	{
		// Check if contact with same email already exists for this user
		$checkStmt = $conn->prepare("SELECT ID FROM Contacts WHERE Email = ? AND UserID = ?");
		$checkStmt->bind_param("si", $email, $userId);
		$checkStmt->execute();
		$checkStmt->store_result();

		if ($checkStmt->num_rows > 0)
		{
			$checkStmt->close();
			$conn->close();
			returnWithError("Contact with this email already exists for this user.");
		}
		else
		{
			$checkStmt->close();
			$stmt = $conn->prepare("INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("ssssi", $firstName, $lastName, $phone, $email, $userId);
			$stmt->execute();
			$stmt->close();
			$conn->close();
			returnWithSuccess("Contact added successfully.");
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

	function returnWithSuccess($msg)
	{
		$retValue = '{"success":"' . $msg . '"}';
		sendResultInfoAsJson($retValue);
	}
?>