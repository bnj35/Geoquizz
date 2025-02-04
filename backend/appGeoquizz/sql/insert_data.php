<?php

$host = "geoquizz.db";
$port = "5432";
$user = "root";
$password = "pass";

try {
    $pdoAuth = new PDO("pgsql:host=$host;port=$port;dbname=geoguizz_auth", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $pdoGame = new PDO("pgsql:host=$host;port=$port;dbname=geoguizz", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $mdp1 = password_hash('password1', PASSWORD_DEFAULT);
    $mdp2 = password_hash('password2', PASSWORD_DEFAULT);

    $user1Id = 'b2a93c47-da9f-4667-88ee-cf9e14b0b7d5';
    $user2Id = 'bcd5b814-1e41-4267-b8f8-459b541d96bf';

    $sqlUser = "
        INSERT INTO Users (id, email, password, role)
        VALUES
            ('$user1Id', 'user1@example.com', '$mdp1', 0),
            ('$user2Id', 'user2@example.com', '$mdp2', 0);
    ";

    $pdoAuth->exec($sqlUser);
    echo "✅ Utilisateurs insérés avec succès.\n";

    $partie1Id = '79800c66-1902-4521-a22f-aabd591489b3';
    $partie2Id = '86c0b5ac-cdcb-45c3-9b25-40cda261144a';

    $sqlPartie = "
        INSERT INTO Parties (id, token, nb_photos, score, theme)
        VALUES
            ('$partie1Id', 'abc123', 5, 10, 'Nature'),
            ('$partie2Id', 'xyz789', 10, 20, 'Architecture');
    ";

    $pdoGame->exec($sqlPartie);
    echo "✅ Parties insérées avec succès.\n";

    $sqlStats = "
        INSERT INTO Stats (user_id, score_tot, score_moyen, nb_partie, meilleur_coup, pire_coup)
        VALUES
            ('$user1Id', 50, 25, 2, 30, 5),
            ('$user2Id', 50, 25, 2, 30, 5);
    ";

    $pdoGame->exec($sqlStats);
    echo "✅ Statistiques insérées avec succès.\n";

    $sqlImage = "
        INSERT INTO Partie_Images (partie_id, image_id)
        VALUES
            ('$partie1Id', '94aa6aa9-6632-4aa7-a56a-ea658e0a19c2'),
            ('$partie2Id', '43215f14-37c8-498e-8e6d-a811200ee07a');
    ";

    $pdoGame->exec($sqlImage);
    echo "✅ Images associées aux parties.\n";

    $sqlPartieUser = "
        INSERT INTO Partie_Users (partie_id, user_id)
        VALUES
            ('$partie1Id', '$user1Id'),
            ('$partie1Id', '$user2Id'),
            ('$partie2Id', '$user1Id'),
            ('$partie2Id', '$user2Id');
    ";

    $pdoGame->exec($sqlPartieUser);
    echo "✅ Les utilisateurs ont été associés aux parties.\n";

} catch (PDOException $e) {
    echo "❌ Erreur lors de l'insertion des données : " . $e->getMessage() . "\n";
}