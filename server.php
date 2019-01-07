<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    try
    {
        $bdd = new PDO('mysql:host=51.255.49.92:3306;dbname=wishlist;charset=utf8','roger','roger',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $e)
    {
            die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['login'])) {
	$login = $_POST["log"]; // a decontaminer
	if(strpos($login,"@")===false)
	{
		// si pas @
		$checkUser = $bdd->prepare('Select * from user where name=?');
	}
	else
	{
		// si @
       		$checkUser = $bdd->prepare('Select * from user where email=?');
      	}
	$username = htmlentities($_POST["log"]);
       $password = htmlentities($_POST["password"]);
       $checkUser->execute(array($username));
       $rows = $checkUser->rowCount();
       if($rows==1)
       {
          
                $donnee=$checkUser->fetch();
                if(password_verify($password,$donnee["password"]))
       		{
		    $name = $donnee["name"];
		    $id = $donnee[0];
		    $email = $donnee["email"];
		    $_SESSION['email']=$email;
                    $_SESSION['name']=$name;
		    $_SESSION['id']=$id;
		if(isset($_POST["remember"]))
		{
			$time=time()+60*60*24*365;
			$cookieRemember['id']=$id;
			$cookieRemember['name']=$name;
			$cookieRemember=serialize($cookieRemember);
			setcookie("rememberMe",$cookieRemember,$time);
		}
        //     	var_dump($donnee);
	//	var_dump($_POST);
	//	var_dump($_SESSION);   
	//	header('location: oui.php');
		header('location: index.php?oui=1');
                }
                else
                {
		//var_dump($checkUser);
		 // var_dump($_SESSION);
	     	  header("Location: login.php?error=2"); 
                } 
       }
       else
       {	
	//var_dump($checkUser);
	//var_dump($_POST);
	//echo $login;
   	header("Location: login.php?error=1");
       }
    } else {
	
       $checkUser = $bdd->prepare('Select * from user where email=?');
       
       $email = htmlentities($_POST['email']);
       $name = htmlentities($_POST['name']);
       $password = password_hash(htmlentities($_POST['password']),PASSWORD_BCRYPT);
       $password2 = password_hash(htmlentities($_POST['password2']),PASSWORD_BCRYPT);
	if($_POST['password']!=$_POST['password2'])
	{
	 header("location: register.php?error=2");
	}
	else
	{
       $checkUser->execute(array($email));
       $rows = $checkUser->rowCount();
       if($rows==1)
       {
		header("Location:register.php?error=1");
       }
       else
       {
	   $addUser = $bdd->prepare('INSERT INTO `wishlist`.`user` ( `email`, `name`, `password`) VALUES (?, ?, ?)');
           $addUser->execute(array($email, $name, $password));
		$getId = $bdd->prepare('select id from user where email=?');
		$getId->execute(array($email));
		$donnee = $getId->fetch();
		$id = $donnee['id'];
	   $_SESSION['email']=$email;
	   $_SESSION['name']=$name;
		$_SESSION['id']=$id;
		header("Location:index.php");
       }
	}
    }
?>
