<?php
	// Log raw input for debugging
	file_put_contents("debug.txt", "RAW INPUT: " . file_get_contents('php://input') . "\n", FILE_APPEND);

	$inData = getRequestInfo();

	$FirstName = isset($inData["FirstName"]) ? trim($inData["FirstName"]) : "";
	$LastName  = isset($inData["LastName"]) ? trim($inData["LastName"]) : "";
	$Login     = isset($inData["Login"]) ? trim($inData["Login"]) : "";
	$Password  = isset($inData["Password"]) ? trim($inData["Password"]) : "";

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

	if ($conn->connect_error) 
	{
		logAndReturnError("DB connection failed: " . $conn->connect_error);
	} 
	else
	{
		$stmt = $conn->prepare("SELECT Login FROM Users WHERE Login = ?");
		if (!$stmt) {
			logAndReturnError("Prepare failed: " . $conn->error);
		}
		$stmt->bind_param("s", $Login);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0)
		{
			$stmt->close();
			$conn->close();
			logAndReturnError("User already exists.");
		}
		else
		{
			$stmt->close();
			$hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

			$stmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, Login, Password) VALUES (?, ?, ?, ?)");
			if (!$stmt) {
				logAndReturnError("Prepare failed: " . $conn->error);
			}

			$stmt->bind_param("ssss", $FirstName, $LastName, $Login, $hashedPassword);

			if (!$stmt->execute()) {
				logAndReturnError("Execute failed: " . $stmt->error);
			}

			$stmt->close();
			$conn->close();
			logSuccess("User created successfully.");
		}
	}

	// === Functions ===

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson($obj)
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function logAndReturnError($err)
	{
		file_put_contents("debug.txt", "ERROR: $err\n", FILE_APPEND);
		http_response_code(500);
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson($retValue);
		exit();
	}

	function logSuccess($msg)
	{
		file_put_contents("debug.txt", "SUCCESS: $msg\n", FILE_APPEND);
		$retValue = '{"success":"' . $msg . '"}';
		sendResultInfoAsJson($retValue);
	}
?>