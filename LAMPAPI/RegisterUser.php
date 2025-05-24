<?php
	$inData = getRequestInfo();
	
    $FirstName = $inData["FirstName"];
    $LastName = $inData["LastName"];
    $Login = $inData["Login"];
    $Password = $inData["Password"];

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
        $stmt = $conn->prepare("SELECT Login FROM Users WHERE Login = ?");
        $stmt->bind_param("s", $Login);
        $stmt->execute();
		$stmt->store_result();
        if($stmt->num_rows > 0){
            $stmt->close();
            $conn->close();
            returnWithError("Login already in use");
        }
        else{
            $stmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, Login, Password) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $FirstName, $LastName, $Login, $Password);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            returnWithError("");
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
	
?>