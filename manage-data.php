<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
// TODO: Définir les paramètres de connexion à la base
class Database {
  // Connexion à la base de données
  private $host = "localhost";
  private  $db_name = "ionicfoundlost";
  private  $username = "root";
  private  $password = "";
  protected  $pdo;
 
  public function getPdo()
  {
    // TODO: Créer une instance de la classe PDO
          $this->pdo = new PDO("mysql:host=" . $this->host.";dbname=".$this->db_name, $this->username, $this->password, [
             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
             PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
         ]);
        
 return $this->pdo;
  } 
     
 }
print_r($_GET);


// Récupérer le paramètre d’action de l’URL du client depuis $_GET[‘key’] 
// et nettoyer la valeur

$key = strip_tags($_GET['action']);
print_r($key);


// Récupérer les paramètres envoyés par le client vers l’API
$input = file_get_contents('php://input');

if (!empty($input)) {
 $data = json_decode($input, true);

 $description = strip_tags($data['description']);
    $status = strip_tags($data['status']);
 $date = strip_tags($data['date']);
 $location = strip_tags($data['location']);
 $firstname = strip_tags($data['firstname']);
 $lastname = strip_tags($data['lastname']);
 $email = strip_tags($data['email']);

     // En fonction du mode d’action requis
    switch ($key) {
  //Ajoute un nouvel enregistrement
 case "create":
//   TODO: Filtrer les valeurs entrantes


//   TODO: Préparer la requête dans un try/catch
try {

  //   Ajout nouvelle entrée
  
      $objet = $this->pdo->prepare("INSERT INTO foundlost (status,description, date, location, firstname, lastname,email)
      VALUES(:status, :description, :date, :location, :firstname, :lastname, :email)");
  
      $objet->bindParam(':status',$status);
      $objet->bindParam(':description',$description);
      $objet->bindParam(':date',$date);
      $objet->bindParam(':location',$location);
      $objet->bindParam(':firstname',$firstname);
      $objet->bindParam(':lastname',$lastname);
      $objet->bindParam(':email',$email);
      
      $objet->execute();
  
      
  }
  
  catch(PDOException $e){
      echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
  }
  



break;

  // Mettre à jour un enregistrement existant
 case "update":
//   TODO: Nettoyer les valeurs en provenant de l’URL client
//   TODO: Préparer et exécuter la requête (dans un try/catch)
  break;

  // Supprimer un enregistrement existant
 case "delete":
//   TODO: Nettoyer les valeurs de l’URL client (id_task)
//   TODO: Préparer et exécuter la requête (dans un try/catch)
  break;
   } // fin switch
} // fin if