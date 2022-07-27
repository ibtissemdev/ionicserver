<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
// TODO: Définir les paramètres de connexion à la base
try {

  // Connexion base de données 

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "ionicfoundlost";


  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Erreur : " . $e->getMessage();
}
//  error_log(print_r($_GET,1));


// Récupérer le paramètre d’action de l’URL du client depuis $_GET[‘key’] 
// et nettoyer la valeur



$key = strip_tags($_GET['key']);

// print_r($key);
// print_r($_REQUEST['key']);

// var_dump($key);

// error_log(print_r($_GET,1));

// Récupérer les paramètres envoyés par le client vers l’API




$input = file_get_contents('php://input');

if (!empty($input) || isset($_GET)) {
  @$data = json_decode($input, true);

  @$description = strip_tags($data['description']);
  @$status = strip_tags($data['status']);
  @$date = strip_tags($data['date']);
  @$location = strip_tags($data['location']);
  @$firstname = strip_tags($data['firstname']);
  @$lastname = strip_tags($data['lastname']);
  @$email = strip_tags($data['email']);




  // En fonction du mode d’action requis
  switch ($key) {
      //Ajoute un nouvel enregistrement

    case "create":
      //   TODO: Filtrer les valeurs entrantes


      function valid_donnees($donnees)
      {
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
        return $donnees;
      }

      $description = valid_donnees($description);
      $location = valid_donnees($location);
      $firstname = valid_donnees($firstname);
      $lastname = valid_donnees($lastname);
      $email = valid_donnees($email);



      if (
        !empty($email)
        && filter_var($email, FILTER_VALIDATE_EMAIL)
      )

        //   TODO: Préparer la requête dans un try/catch
        try {
          // error_log("je suis ici : create");
          // print_r($key);
          // echo "je suis la"; 
          //   Ajout nouvelle entrée

          $objet = $conn->prepare("INSERT INTO foundlost (status,description, date, location, firstname, lastname,email)
      VALUES(:status, :description, :date, :location, :firstname, :lastname, :email)");

          $objet->bindParam(':status', $status, PDO::PARAM_INT);
          $objet->bindParam(':description', $description, PDO::PARAM_STR);
          $objet->bindParam(':date', $date, PDO::PARAM_STR);
          $objet->bindParam(':location', $location, PDO::PARAM_STR);
          $objet->bindParam(':firstname', $firstname, PDO::PARAM_STR);
          $objet->bindParam(':lastname', $lastname, PDO::PARAM_STR);
          $objet->bindParam(':email', $email, PDO::PARAM_STR);

          $objet->execute();
        } catch (PDOException $e) {
          echo 'Impossible de traiter les données. Erreur : ' . $e->getMessage();
        }

      break;

      // Mettre à jour un enregistrement existant
    case "update":
      //   TODO: Nettoyer les valeurs en provenant de l’URL client
      //   TODO: Préparer et exécuter la requête (dans un try/catch)
      // error_log("je suis la : update");
      break;

      // Supprimer un enregistrement existant
    case "delete":
      //   TODO: Nettoyer les valeurs de l’URL client (id_task)
      // error_log("je suis ici : delete");

      $id = filter_var($_GET['id']);

      //   TODO: Préparer et exécuter la requête (dans un try/catch)

      // error_log(print_r($recordID, 1));

      // var_dump($id);


      $sth = $conn->prepare("DELETE FROM foundlost WHERE id_object=$id");
      $sth->execute();
      // error_log(print_r($sth,1));

      break;
    
  } // fin switch
} // fin if