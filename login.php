<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $password = $_POST['pass'];

   $sql = "SELECT * FROM `users` WHERE email = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email]);
   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if($row){
      $storedPassword = $row['password'];
      $isValidPassword = false;

      if(password_verify($password, $storedPassword)){
         $isValidPassword = true;
      }elseif(strlen($storedPassword) === 32 && md5($password) === $storedPassword){
         $isValidPassword = true;
      }elseif($password === $storedPassword){
         $isValidPassword = true;
      }

      if($isValidPassword){

         $passwordInfo = password_get_info($storedPassword);
         if(empty($passwordInfo['algo'])){
            $rehash = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update->execute([$rehash, $row['id']]);
         }

         if($row['user_type'] == 'admin'){

            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_page.php');

         }elseif($row['user_type'] == 'user'){

            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');

         }else{
            $message[] = 'no user found!';
         }

      }else{
         $message[] = 'incorrect email or password!';
      }

   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>
<body>

<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>
   
<section class="form-container">

   <form action="" method="POST">
      <h3>login now</h3>
      <input type="email" name="email" class="box" placeholder="enter your email" required>
      <input type="password" name="pass" class="box" placeholder="enter your password" required>
      <input type="submit" value="login now" class="btn" name="submit">
      <p>don't have an account? <a href="register.php">register now</a></p>
   </form>

</section>


</body>
</html>