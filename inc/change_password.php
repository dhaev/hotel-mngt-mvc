
<?php
session_start();
 
if(isset($_POST['update'])||isset($_POST['oldpwd'])){

   $oldpwd=$_POST['oldpwd'];
   $newpwd=$_POST['newpwd'];
    $id=$_SESSION['CustomerID'];
   $email=$_SESSION['email']; 

   require_once __DIR__ . '/../config.php';
   require_once 'functions.php';

   if(pwdEmpty($oldpwd,$newpwd)!==false){
       echo('please fill all fields');
      exit();
   }
   if(cpwd($conn,$email,$oldpwd)!==false){
       echo('incorrect password');
      exit();
   }
   
   passwordUpdate($conn,$id,$newpwd);
   
 // cpwd($conn,$id,$oldpwd,$oldpwd);
      
}
else{
    header('location:../index.php');
   exit();
}
      
     