<?php  

	$dsn = "mysql:host=localhost;dbname=blog";

	try {
		$pdo = new PDO($dsn, 'root', '1245');
	} catch (PDOException $e) {
		echo $e->getMessage();
	}

?>