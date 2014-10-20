<?php

	class MySQL extends PDO
	{
		private static $_instance;

		public function __construct() {}

		public static function getInstance()
		{
			require_once("config.php");

			if (!isset(self::$_instance))
			{
				try
				{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					self::$_instance = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_MDP, $pdo_options);
				}
				catch (PDOException $e)
				{
					echo 'Error : ' . $e->getMessage() . '<br />';
				}
			}
			
			return self::$_instance;
		}

		public static function exist($table, $column_name, $value)
		{
			$bdd = self::getInstance();

			$requete = $bdd->prepare('SELECT * FROM '.$table.' WHERE '.$column_name.' = ?');
			$requete->bindValue(1, $value);
			$requete->execute();
			if($requete->rowCount() > 0)
				return true;
			else
				return false;
		}
	}