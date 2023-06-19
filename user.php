<?php

declare(strict_types=1);

$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");

// $email = readline();
// $password = readline();
// $hash = password_hash($password, PASSWORD_ARGON2ID);

// $sql = 'INSERT INTO users (email, password) VALUES (?, ?);';
// $statement = $pdo->prepare($sql);
// $statement->bindValue(1, $email);
// $statement->bindValue(2, $hash);
// $statement->execute();

$stmt = $pdo->query("SELECT * FROM users;");
var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
