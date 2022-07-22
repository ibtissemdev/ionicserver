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
  
  
  
  }
  
  catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }


// TODO : Prépare et exécute la requête de lecture de la table (try/catch)


try {

  $list = "SELECT * FROM foundlost WHERE status=0 ORDER BY date DESC";
  $sth = $conn->prepare($list);
  $sth->execute();
  $result = $sth->fetchall();
  $objet_perdu=json_encode($result);
  print_r($objet_perdu);


} catch (PDOException $e) {
  echo 'Impossible de traiter les données. Erreur : ' . $e->getMessage();
}
