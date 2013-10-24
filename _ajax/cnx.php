<?php

	require_once($_real_path . './conf/connexion.inc.php');

	try{
		// si erreur il y'a php n'affichera pas l'eereu avec le mot de passe compris
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		 // On se connecte à MySQL
		$pdo = new PDO("$server:host=$host;port=$port;dbname=$base", "$user", "$pass", $pdo_options);

	}catch (Exception $e){
			die('Erreur : ' . $e->getMessage());
	}
?>