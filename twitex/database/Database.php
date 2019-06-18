<?php

namespace database;

class Database {

	private $dbh;

	public function __construct(String $host, String $name, String $user, String $pass, String $port)
	{
		try{
			$this->dbh = new \PDO("mysql:host=$host;port=$port;dbname=$name", $user, $pass);
		} catch(\PDOException $e) {
    		print "Erreur !: " . $e->getMessage() . "<br>"; // Affichage du message d'erreur
    		die(); // Arrêt du script
		}
	}

	public function getConnection() {
		return $this->dbh;
	}
}