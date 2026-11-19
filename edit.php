<?php
// On démarre une session
session_start();

if($_POST){
    if(isset($_POST['id']) && !empty($_POST['id'])
    && isset($_POST['produit']) && !empty($_POST['produit'])
    && isset($_POST['prix']) && !empty($_POST['prix'])
    && isset($_POST['nombre']) && !empty($_POST['nombre'])
    && isset($_POST['categorie_id'])){
        // On inclut la connexion à la base
        require_once('connect.php');

        // On nettoie les données envoyées
        $id = strip_tags($_POST['id']);
        $produit = strip_tags($_POST['produit']);
        $prix = strip_tags($_POST['prix']);
        $nombre = strip_tags($_POST['nombre']);
        $categorie_id = !empty($_POST['categorie_id']) ? strip_tags($_POST['categorie_id']) : null;

        $sql = 'UPDATE `liste` 
                SET `produit`=:produit, `prix`=:prix, `nombre`=:nombre, `categorie_id`=:categorie_id 
                WHERE `id`=:id;';

        $query = $db->prepare($sql);

        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':produit', $produit, PDO::PARAM_STR);
        $query->bindValue(':prix', $prix, PDO::PARAM_STR);
        $query->bindValue(':nombre', $nombre, PDO::PARAM_INT);
        $query->bindValue(':categorie_id', $categorie_id, $categorie_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

        $query->execute();

        $_SESSION['message'] = "Produit modifié";
        require_once('close.php');

        header('Location: index.php');
        die();
    }else{
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}

// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['id']) && !empty($_GET['id'])){
    require_once('connect.php');

    // On nettoie l'id envoyé
    $id = strip_tags($_GET['id']);

    $sql = 'SELECT * FROM `liste` WHERE `id` = :id;';
    $query = $db->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $produit = $query->fetch();

    if(!$produit){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header('Location: index.php');
        die();
    }

    // Récupération de toutes les catégories
    $sqlCat = 'SELECT * FROM `categories` ORDER BY `nom_categorie` ASC;';
    $queryCat = $db->prepare($sqlCat);
    $queryCat->execute();
    $categories = $queryCat->fetchAll(PDO::FETCH_ASSOC);

    require_once('close.php');
}else{
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index.php');
    die();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit</title>

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
                <h1>Modifier un produit</h1>
                <form method="post">
                    <div class="form-group">
                        <label for="produit">Produit</label>
                        <input type="text" id="produit" name="produit" class="form-control" value="<?= $produit['produit']?>">
                    </div>
                    <div class="form-group">
                        <label for="categorie_id">Catégorie</label>
                        <select id="categorie_id" name="categorie_id" class="form-control">
                            <option value="">-- Sans catégorie --</option>
                            <?php foreach($categories as $categorie): ?>
                                <option value="<?= $categorie['id'] ?>" <?= ($categorie['id'] == $produit['categorie_id']) ? 'selected' : '' ?>>
                                    <?= $categorie['nom_categorie'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="text" id="prix" name="prix" class="form-control" value="<?= $produit['prix']?>">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="number" id="nombre" name="nombre" class="form-control" value="<?= $produit['nombre']?>">
                    </div>
                    <input type="hidden" value="<?= $produit['id']?>" name="id">
                    <button class="btn btn-primary">Envoyer</button>
                    <a href="index.php" class="btn btn-secondary">Retour</a>
                </form>
            </section>
        </div>
    </main>
</body>
</html>
