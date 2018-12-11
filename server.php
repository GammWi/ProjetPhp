<?php
session_start();

ini_set('display_errors', 1);


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
	$getName = $bdd->prepare('Select name from user where email=?');

       $email = htmlentities($_POST["email"]);
       $name = "";
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
                if($password==$donnee["password"])
                {
			$getName->execute(array($email));
			$donnee = $getName->fetch();
			$name = $donnee["name"];
		    $_SESSION['email']=$email;
                    $_SESSION['name']=$name;
             	    header('location: index.php');
                }
                else
                {
	      	    header("Location: login.php?error=2"); 
                } 
            }
       }
       else
       {	
	  header("Location: login.php?error=1");
       }
    } else {
	
       $checkUser = $bdd->prepare('Select * from user where email=?');
       
       $email = htmlentities($_POST['email']);
       $name = htmlentities($_POST['name']);
       $password = md5(htmlentities($_POST['password']));
       $password2 = md5(htmlentities($_POST['password2']));
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
	   $_SESSION['email']=$email;
	   $_SESSION['name']=$name;
		header("Location:index.php");
       }
	}
    }
?>
