<?php
// On démarre une session
session_start();

// On inclut la connexion à la base dès le début
require_once('connect.php');

if($_POST){
    if(isset($_POST['produit']) && !empty($_POST['produit'])
    && isset($_POST['prix']) && !empty($_POST['prix'])
    && isset($_POST['nombre']) && !empty($_POST['nombre'])
    && isset($_POST['categorie_id'])){

        $currentDirectory = getcwd();
        $uploadDirectory = "/images/";
    
        $fileName = $_FILES['image']['name'];
        $fileTmpName  = $_FILES['image']['tmp_name'];
    
        $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName); 
    
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload == 1) { $messageimage = "Image uploadée";}
        else { $messageimage = "Problème upload image";}

        // On nettoie les données envoyées
        $produit = strip_tags($_POST['produit']);
        $prix = strip_tags($_POST['prix']);
        $nombre = strip_tags($_POST['nombre']);
        $imageprod = $fileName;
        $categorie_id = !empty($_POST['categorie_id']) ? strip_tags($_POST['categorie_id']) : null;

        $sql = 'INSERT INTO `liste` (`produit`, `prix`, `nombre`, `image_produit`, `actif`, `categorie_id`) 
                VALUES (:produit, :prix, :nombre, :imageprod, 1, :categorie_id);';

        $query = $db->prepare($sql);

        $query->bindValue(':produit', $produit, PDO::PARAM_STR);
        $query->bindValue(':prix', $prix, PDO::PARAM_STR);
        $query->bindValue(':nombre', $nombre, PDO::PARAM_INT);
        $query->bindValue(':imageprod', $imageprod, PDO::PARAM_STR);
        $query->bindValue(':categorie_id', $categorie_id, $categorie_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

        $query->execute();

        $_SESSION['message'] = "Produit ajouté. $messageimage";
        require_once('close.php');

        header('Location: index.php');
        die();
    }else{
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}

// Récupération de toutes les catégories pour le <select>
$sqlCat = 'SELECT * FROM `categories` ORDER BY `nom_categorie` ASC;';
$queryCat = $db->prepare($sqlCat);
$queryCat->execute();
$categories = $queryCat->fetchAll(PDO::FETCH_ASSOC);

require_once('close.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <?php
                    if(!empty($_SESSION['erreur'])){
                        echo '<div class="alert alert-danger" role="alert">
                                '. $_SESSION['erreur'].'
                            </div>';
                        $_SESSION['erreur'] = "";
                    }
                ?>
                <h1>Ajouter un produit</h1>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="produit">Produit</label>
                        <input type="text" id="produit" name="produit" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="categorie_id">Catégorie</label>
                        <select id="categorie_id" name="categorie_id" class="form-control">
                            <option value="">-- Choisir une catégorie (optionnel) --</option>
                            <?php foreach($categories as $categorie): ?>
                                <option value="<?= $categorie['id'] ?>">
                                    <?= $categorie['nom_categorie'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="text" id="prix" name="prix" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="number" id="nombre" name="nombre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="image">Image du produit</label>
                        <input type="file" id="image" name="image" class="form-control">
                    </div>

                    <button class="btn btn-primary">Envoyer</button>
                    <a href="index.php" class="btn btn-secondary">Retour</a>
                </form>
            </section>
        </div>
    </main>
</body>
</html>
