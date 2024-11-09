<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['send'])){

   // $name = $_POST['front'];
   // $name = filter_var($name, FILTER_SANITIZE_STRING);
   // $email = $_POST['back'];
   // $email = filter_var($email, FILTER_SANITIZE_STRING);
   // $number = $_POST['size'];
   // $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   // $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   // $select_message->execute([$name, $email, $number, $msg]);
   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE message = ?");
   $select_message->execute([$msg]);
   if($select_message->rowCount() > 0){
      $message[] = 'already Message send sucessfully!';
   }else{

      // $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      // $insert_message->execute([$user_id, $name, $email, $number, $msg]);
      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, message) VALUES(?,?)");
      $insert_message->execute([$user_id, $msg]);

      $message[] = 'Message send successfully!';

   }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="contact">

   <form action="" method="post">
      <h3>Message Us.</h3>
      
      <!-- <input type="text" name="front" placeholder="Front View Details" required maxlength="50" class="box">
      <input type="text" name="back" placeholder="BackSide View Details" required maxlength="50" class="box">
      <input type="text" name="size" placeholder="Size and Color Of Cloth" required maxlength="20" class="box"> -->
      <textarea name="msg" class="box" placeholder="Message Us" cols="30" rows="10"></textarea>
      <input type="submit" value="Place Order" name="send" class="btn">
   </form>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>