<?php
session_start();

ini_set('display_errors', 1);
		
error_reporting(e_al1);



    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=wishlist;charset=utf8','roger','roger',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $e)
    {
            die('Erreur : ' . $e->getMessage());
    }

    if (isset($_POST['login'])) {
       $checkUser = $bdd->prepare('Select * from user where email=?');

       $email = htmlentities($_POST["email"]);
       $name = htmlentities($_POST["name"]);
       $password = md5(htmlentities($_POST["password"]));
       $checkUser->execute(array($email));
       $rows = $checkUser->rowCount();
       if($rows==1)
       {	
           $checkPwd = $bdd->prepare('Select password from user where email=?');
           $checkPwd->execute(array($email));
	   $rows = $checkPwd->rowCount();
           if($rows==1)
            {
                $donnee=$checkPwd->fetch();
	var_dump($donnee);
                if($password==$donnee["password"])
                {
		    echo("co");
                    $_SESSION['email']=$email;
                    $_SESSION['name']=$name;
             	    // header('location: index.php');
                }
                else
                {
			echo("mvs mdp");
			var_dump($_POST);
			var_dump($donnee);
               	    // header('Location: login.php?error=2'); 
                } 
            }
       }
       else
       {	
		echo("no user");
		var_dump($_POST);
//      	  header('Location: login.php?error=1'); 
       }
    } else {
	
       $checkUser = $bdd->prepare('Select * from user where email=?');
       
       $email = htmlentities($_POST['email']);
       $name = htmlentities($_POST['name']);
       $password = md5(htmlentities($_POST['password']));
       $password2 = md5(htmlentities($_POST['password2']));
	if($_POST['password']!=$_POST['password2'])
	{
		var_dump($_POST);
		echo("error mdp");
	 // header('location: register.php?error=2');
	}
	else
	{
       $checkUser->execute(array($email));
       $rows = $checkUser->rowCount();
       if($rows==1)
       {
		echo("error user");
		var_dump($_POST);
	 // header('Location: register.php?error=1'); 
       }
       else
       {
	   $addUser = $bdd->prepare('INSERT INTO `wishlist`.`user` ( `email`, `name`, `password`) VALUES (?, ?, ?)');
           $addUser->execute(array($email, $name, $password));
	   $_SESSION['email']=$email;
	   $_SESSION['name']=$name;
		var_dump($_POST);
		echo("add suer");
         //  header('location: login.php');
       }
	}
    }
?>
