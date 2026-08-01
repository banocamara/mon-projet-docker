<?php
$host = 'db';
$dbname = 'mon_site_db';
$username = 'root';
$password = 'rootpassword';

$message = "";

try {
    // Connexion PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Création de la table avec une colonne "image"
    $sqlTable = "CREATE TABLE IF NOT EXISTS utilisateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        image VARCHAR(255) DEFAULT NULL
    )";
    $conn->exec($sqlTable);

    // 2. Traitement du formulaire lorsqu'il est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $imageName = null;

        // Gestion du fichier image téléchargé
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['avatar']['tmp_name'];
            $fileName = $_FILES['avatar']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Extensions autorisées
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                // Renommer l'image de manière unique pour éviter les doublons
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = './uploads/';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $imageName = $newFileName;
                }
            }
        }

        // Insertion dans MySQL si les champs texte sont remplis
        if (!empty($nom) && !empty($email)) {
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, image) VALUES (:nom, :email, :image)");
            $stmt->execute([
                'nom' => $nom,
                'email' => $email,
                'image' => $imageName
            ]);
            $message = "Utilisateur ajouté avec succès !";
        }
    }

    // 3. Récupération de tous les utilisateurs pour les afficher
    $stmt = $conn->query("SELECT * FROM utilisateurs ORDER BY id DESC");
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $erreur = "Erreur de base de données : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Site Docker - Gestion d'utilisateurs</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0d1117;
            color: #c9d1d9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .main-container {
            max-width: 700px;
            width: 100%;
        }
        .box {
            background-color: #161b22;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
            border: 1px solid #30363d;
            margin-bottom: 25px;
        }
        h1, h2 {
            color: #58a6ff;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 14px;
            font-weight: 600;
        }
        input[type="text"], input[type="email"], input[type="file"] {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #30363d;
            background-color: #0d1117;
            color: white;
            font-size: 14px;
        }
        button {
            background-color: #238636;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        button:hover {
            background-color: #2ea043;
        }
        .alert-success {
            color: #3fb950;
            background: rgba(63, 185, 80, 0.1);
            padding: 10px;
            border-radius: 6px;
            border: 1px solid rgba(63, 185, 80, 0.4);
            margin-bottom: 15px;
        }
        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .user-card {
            background: #21262d;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #30363d;
            text-align: center;
        }
        .user-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid #58a6ff;
        }
        .default-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #30363d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            font-size: 24px;
        }
        .user-name {
            font-weight: bold;
            font-size: 16px;
            color: white;
        }
        .user-email {
            font-size: 12px;
            color: #8b949e;
            word-break: break-all;
        }
    </style>
</head>
<body>

    <div class="main-container">
        <div class="box">
            <h1>Ajouter un profil 🚀</h1>
            <?php if (!empty($message)): ?>
                <div class="alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if (isset($erreur)): ?>
                <div style="color: #f85149; margin-bottom: 15px;"><?php echo $erreur; ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="nom">Nom complet :</label><br>
                    <input type="text" id="nom" name="nom" required style="width: 100%; box-sizing: border-box;">
                </div>
                <div>
                    <label for="email">Adresse Email :</label><br>
                    <input type="email" id="email" name="email" required style="width: 100%; box-sizing: border-box;">
                </div>
                <div>
                    <label for="avatar">Photo de profil (Image) :</label><br>
                    <input type="file" id="avatar" name="avatar" accept="image/*" style="width: 100%; box-sizing: border-box;">
                </div>
                <button type="submit">Enregistrer dans Docker / MySQL</button>
            </form>
        </div>

        <div class="box">
            <h2>Liste des profils en base de données</h2>
            <?php if (empty($utilisateurs)): ?>
                <p style="color: #8b949e;">Aucun utilisateur enregistré pour le moment.</p>
            <?php else: ?>
                <div class="user-grid">
                    <?php foreach ($utilisateurs as $user): ?>
                        <div class="user-card">
                            <?php if (!empty($user['image']) && file_exists('./uploads/' . $user['image'])): ?>
                                <img src="./uploads/<?php echo htmlspecialchars($user['image']); ?>" alt="Avatar" class="user-avatar">
                            <?php else: ?>
                                <div class="default-avatar">👤</div>
                            <?php endif; ?>
                            
                            <div class="user-name"><?php echo htmlspecialchars($user['nom']); ?></div>
                            <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>