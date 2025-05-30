<?php
$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$users = [
    ["Alice", "Smith", "asmith", "Password1!"],
    ["Bob", "Johnson", "bjohnson", "Password2!"],
    ["Carol", "Williams", "cwilliams", "Password3!"],
    ["David", "Brown", "dbrown", "Password4!"],
    ["Eve", "Jones", "ejones", "Password5!"],
    ["Frank", "Miller", "fmiller", "Password6!"],
    ["Grace", "Davis", "gdavis", "Password7!"],
    ["Hank", "Garcia", "hgarcia", "Password8!"],
    ["Ivy", "Martinez", "imartinez", "Password9!"],
    ["Jack", "Anderson", "janderson", "Password10!"]
];

foreach ($users as $user) {
    [$firstName, $lastName, $login, $password] = $user;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, Login, Password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstName, $lastName, $login, $hashedPassword);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

echo "10 users inserted successfully.";
?>