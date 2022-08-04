<?php
header('Access-Control-Allow-Origin: *');

// TODO : Définir les paramètres de connexion
try {

  // Connexion base de données 

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "ionicfoundlost";

  // TODO : Créer une instance de la classe PDO (connexion à la base)
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Erreur : " . $e->getMessage();
}


// TODO : Prépare et exécute la requête de lecture de la table (try/catch)

if (isset($_GET['id'])) {
  try {


    $list = "SELECT * FROM foundlost WHERE id_object=" . $_GET['id'];
    $sth = $conn->prepare($list);
    $sth->execute();
    $result = $sth->fetch();
    $objet_perdu = json_encode($result);
    print_r($objet_perdu);
  } catch (PDOException $e) {
    echo 'Impossible de traiter les données. Erreur : ' . $e->getMessage();
  }
} else {

  try {

    $list = "SELECT * FROM foundlost ORDER BY date DESC";
    $sth = $conn->prepare($list);
    $sth->execute();
    $result = $sth->fetchall();
    $objet_perdu = json_encode($result);
    print_r($objet_perdu);
  } catch (PDOException $e) {
    echo 'Impossible de traiter les données. Erreur : ' . $e->getMessage();
  }
}



