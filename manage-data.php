<?php
//Accepte les requêtes venant de n'importe quel site
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



$key = strip_tags($_GET['key']); // supprime les balises html et php

// print_r($key);
// print_r($_REQUEST['key']);

// var_dump($key);

// error_log(print_r($_GET,1));

// Récupérer les paramètres envoyés par le client vers l’API


$input = file_get_contents('php://input'); //données récupérées et entrées dans le fichier php en chaîne de caractère/ en sortie : output

//  error_log(print_r($input,1));

if (!empty($input) || isset($_GET)) {
  $data = json_decode($input, true);
  

  // En fonction du mode d’action requis
  switch ($key) {
      //Ajoute un nouvel enregistrement

    case "create":
      //   TODO: Filtrer les valeurs entrantes
      error_log("je suis dans le create");

      $status = strip_tags($data['status']);
      $date = strip_tags($data['date']);
      
      function valid_donnees($donnees)
      {
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
        return $donnees;
      }

      $description = valid_donnees(strip_tags($data['description']));
      $location = valid_donnees(strip_tags($data['location']));
      $firstname = valid_donnees(strip_tags($data['firstname']));
      $lastname = valid_donnees(strip_tags($data['lastname']));
      $email = valid_donnees(strip_tags($data['email']));


      if (
        !empty($email)
        && filter_var($email, FILTER_VALIDATE_EMAIL)
        && !empty($description)
        && strlen($description) <=50
        && preg_match("#^[A-Za-zéè '-]+$#",$description)
        && !empty($location)
        && strlen($location) <=20
        && preg_match("#^[A-Za-zéè '-]+$#",$location)
        && !empty($firstname)
        && strlen($firstname) <=20
        && preg_match("#^[A-Za-zéè '-]+$#",$firstname)
        && !empty($lastname)
        && strlen($lastname) <=20
        && preg_match("#^[A-Za-zéè '-]+$#",$lastname)

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

    case "user":
      //   TODO: Filtrer les valeurs entrantes
      error_log("je suis ici");

      // $email_user = strip_tags($data['email_user']);
      // $password = strip_tags($data['password']);

      function valid_donnees($donnees)
      {
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
        return $donnees;
      }



      $email_user = valid_donnees(strip_tags($data['email_user']));
      $password = password_hash(valid_donnees(strip_tags($data['password'])), PASSWORD_DEFAULT);


      if (
        !empty($email_user)
        && filter_var($email_user, FILTER_VALIDATE_EMAIL) 

      )

        //   TODO: Préparer la requête dans un try/catch
        try {
          // error_log("je suis ici : create");
          // print_r($key);
          // echo "je suis la"; 
          //   Ajout nouvelle entrée

          // error_log(print_r($objet["email_user"],1));
          $user = "SELECT email_user FROM user WHERE email_user='$email_user'";
          $sth = $conn->prepare($user);
          $sth->execute();
          $result = $sth->fetch();

if(empty($result['email_user'])){

          $user = $conn->prepare("INSERT INTO user (email_user,password)
      VALUES(:email_user, :password)");


          $user->bindParam(':email_user', $email_user, PDO::PARAM_STR);
          $user->bindParam(':password', $password, PDO::PARAM_STR);

          $user->execute();
          $util=true;
        } else {
          $util=false;
          
        }
        $verif = json_encode($util);
          print_r($verif);

        } catch (PDOException $e) {
          echo 'Impossible de traiter les données. Erreur : ' . $e->getMessage();
      //     $user=false;
      //     $user_new = json_encode($user);
      //  print_r($user_new);
        }


      break;

      // Mettre à jour un enregistrement existant
    case "update":
      //   TODO: Nettoyer les valeurs en provenant de l’URL client
      $id = filter_var($_GET['id']);
      //   TODO: Préparer et exécuter la requête (dans un try/catch)
      // error_log("je suis la : update");
        $status = "";
      try {
     
        if ($status == 0) {
          $sth = $conn->prepare("UPDATE foundlost SET status=1 where id_object=$id");
          $sth->execute();
        }

        if ($status == 1) {
          $sth = $conn->prepare("UPDATE foundlost SET status=0 where id_object=$id");
          $sth->execute();
        }

        // error_log('Statut modifié')  ;

      } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
      }


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

    case "verif":

      try {
       
        $email_user = strip_tags($data['email_user']);
        $password = strip_tags($data['password']);

        // error_log($email_user);


        $user = "SELECT password,email_user FROM user WHERE email_user='$email_user'";
        $sth = $conn->prepare($user);
        $sth->execute();
        $result = $sth->fetch();

        if (empty($result['email_user'])) {
          $utilisateur = false; }

          else if(count($result) > 0) {
            if (password_verify($password,  $result['password']) && $result['email_user'] == $email_user) {
              $utilisateur = true;
            } else {
              $utilisateur = false;
            }
          }
        
        $verif = json_encode($utilisateur);
        print_r($verif);
      } catch (PDOException $e) {
        echo 'Impossible de traiter les données. Erreur : ' . $e->getMessage();
      }

      break;
      default: echo "erreur : action inconnue";
  } // fin switch
} // fin if