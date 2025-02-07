<?php

$host = "geoquizz.db";
$port = "5432";
$user = "root";
$password = "pass";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=postgres", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $pdo->exec("CREATE DATABASE geoquizz_auth;");

    echo "Bases de données créées avec succès !\n";

} catch (PDOException $e) {
    echo "Erreur lors de la création des bases : " . $e->getMessage() . "\n";
    exit;
}

try {
    $pdoAuth = new PDO("pgsql:host=$host;port=$port;dbname=geoquizz_auth", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $sqlUsers = "
        CREATE TABLE IF NOT EXISTS Users (
            id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role INT NOT NULL DEFAULT 0
        );
    ";

    $pdoAuth->exec($sqlUsers);
    echo "Table Users créée avec succès !\n";
    $stmt = $pdoAuth->query("SELECT * FROM information_schema.tables WHERE table_name = 'users'");
    if ($stmt->rowCount() == 0) {
        echo "⚠️ La table Users n'existe pas dans geoquizz_auth !\n";
    } else {
        echo "✅ La table Users a bien été créée dans geoquizz_auth.\n";
    }


} catch (PDOException $e) {
    echo "Erreur lors de la création de la table Users : " . $e->getMessage() . "\n";
}

try {
    $pdoGame = new PDO("pgsql:host=$host;port=$port;dbname=geoquizz", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $sqlTables = "
        CREATE TABLE IF NOT EXISTS Parties (
            id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
            nom VARCHAR(255) NOT NULL,
            token VARCHAR(255) NOT NULL UNIQUE,
            status INT NOT NULL DEFAULT 0,
            temps INT NOT NULL DEFAULT 60,
            distance INT NOT NULL DEFAULT 100,
            nb_photos INT NOT NULL,
            score INT NOT NULL DEFAULT 0,
            theme VARCHAR(100) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS Stats (
            id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
            user_id UUID NOT NULL,
            score_tot INT NOT NULL DEFAULT 0,
            score_moyen INT NOT NULL DEFAULT 0,
            nb_partie INT NOT NULL DEFAULT 0,
            meilleur_coup INT NOT NULL DEFAULT 0,
            pire_coup INT NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS Partie_Images (
            partie_id UUID NOT NULL,
            image_id UUID NOT NULL,
            PRIMARY KEY (partie_id, image_id),
            FOREIGN KEY (partie_id) REFERENCES Parties(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS Partie_Users (
            partie_id UUID NOT NULL,
            user_id UUID NOT NULL,
            PRIMARY KEY (partie_id, user_id),
            FOREIGN KEY (partie_id) REFERENCES Parties(id) ON DELETE CASCADE
        );
    ";

    $pdoGame->exec($sqlTables);
    echo "Tables Parties, Stats et Partie_Images créées avec succès !\n";

} catch (PDOException $e) {
    echo "Erreur lors de la création des tables : " . $e->getMessage() . "\n";
}
