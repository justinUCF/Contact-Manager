<?php

	$inData = getRequestInfo();
	
	$searchResults = "";
	$searchCount = 0;

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error) 
	{
		returnWithError($conn->connect_error);
	} 
	else
	{
		$stmt = $conn->prepare("SELECT ID, FirstName, LastName, Phone, Email 
                        FROM Contacts 
                        WHERE FirstName LIKE ? AND UserID = ? 
                        LIMIT 20");
		$searchTerm = "%" . $inData["search"] . "%";
		$stmt->bind_param("si", $searchTerm, $inData["userId"]);
		$stmt->execute();

		$result = $stmt->get_result();
		
		while($row = $result->fetch_assoc())
		{
			if ($searchCount > 0)
			{
				$searchResults .= ",";
			}

			$searchCount++;
			$searchResults .= '{"ID" : ' . $row["ID"] . ', "FirstName" : "' . addslashes($row["FirstName"]) . '", "LastName" : "' . addslashes($row["LastName"]) . '", "Phone" : "' . addslashes($row["Phone"]) . '", "Email" : "' . addslashes($row["Email"]) . '"}';		}

		if ($searchCount == 0)
		{
			returnWithError("No Records Found");
		}
		else
		{
			returnWithInfo($searchResults);
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
    $retValue = '{"results":[],"error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
	}
	
	function returnWithInfo($searchResults)
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson($retValue);
	}
?>