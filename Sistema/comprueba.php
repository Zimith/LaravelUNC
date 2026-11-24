<?php 
session_start();
$user = htmlspecialchars(trim($_POST['user']));
//$pass = sha1(md5(trim($_POST['pass']))); // encriptamos en MD5 para despues comprar (Modificado)
$pass = trim($_POST['pass']);

include_once("../Bibliotecas/dbLogin.php");
$log = new Login();

$result=$log->userExist($user, $pass);
	
if(mysqli_num_rows($result)){
	// devuelve 1 si encontro el usuario y el password
	$array=mysqli_fetch_array($result);
	$_SESSION["usuario"]=$array["username"];
	$_SESSION["tipo"]=$array["tipo"];
	$_SESSION["id"]=$array["id"];
	if ($_SESSION["tipo"] == 1)
		header("Location:./main.php");
	else if ($_SESSION["tipo"] == 5)
		header("Location:./main.php");
	else if ($_SESSION["tipo"] == 6)
		header("Location:./main.php");
		
}else 
	header("Location:./login.php?msg=1");
?>
