<?php
	$inData = getRequestInfo();

	$login = isset($inData["login"]) ? $inData["login"] : "";
	$password = isset($inData["password"]) ? $inData["password"] : "";

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error) 
	{
		http_response_code(500);
		returnWithError($conn->connect_error);
	} 
	else
	{
		// Get hashed password from DB
		$stmt = $conn->prepare("SELECT ID, FirstName, LastName, Password FROM Users WHERE Login = ?");
		$stmt->bind_param("s", $login);
		$stmt->execute();

		$result = $stmt->get_result();

		if ($row = $result->fetch_assoc())
		{
			// Check if submitted password matches the hashed one
			if (password_verify($password, $row["Password"])) {
				returnWithInfo($row["ID"], $row["FirstName"], $row["LastName"]);
			} else {
				http_response_code(401);
				returnWithError("Invalid password.");
			}
		}
		else
		{
			http_response_code(401);
			returnWithError("User not found.");
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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson($retValue);
	}
	
	function returnWithInfo($id, $firstName, $lastName)
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson($retValue);
	}
?>