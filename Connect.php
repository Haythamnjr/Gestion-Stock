<?php

// Classe abstraite pour gérer la connexion à la base de données
abstract class Connexion
{
    protected ?PDO $Conect = null;

    public function __construct()
    {
        // Paramètres de connexion à la base de données
        $Host = 'localhost';
        $Utilisateur = 'root';
        $password = '';
        $charset = 'utf8';
        $DATABASE = 'gestion_stock';

        try {
            // Initialiser PDO avec le mode d'erreur exception
            if(!$this->Conect){
                $this->Conect = new PDO("mysql:dbname=$DATABASE;host=$Host;charset=$charset", $Utilisateur, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    
                ]);
            }
        } catch (Exception $e) {
            die("Il y'a une probléme " . $e->getMessage());
        }
    }
}
