<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $product_id = $conn->prepare("SELECT pid FROM `cart` WHERE id=?");
   $product_id->execute([$cart_id]);
   $pid = $product_id->fetch(PDO::FETCH_ASSOC)['pid'];
   $update_product = $conn->prepare("UPDATE `products` SET stock= stock+? WHERE id=?");
   $update_product->execute([$qty, $pid]);
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if(isset($_POST['update_qty'])){
  
   $cart_id = $_POST['cart_id'];
   $product_id = $_POST['product_id'];
   $quantity = $_POST['quantity'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   if($qty>$quantity){
   $update_product = $conn->prepare("UPDATE `products` SET stock= stock-? WHERE id=?");
}else{
   $update_product = $conn->prepare("UPDATE `products` SET stock= stock+? WHERE id=?");
}
$update_product->execute([$qty, $product_id]);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products shopping-cart">

   <h3 class="heading">Shopping cart</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
     
      
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){

             $select_products = $conn->prepare("SELECT stock FROM `products` WHERE id=?"); 
      $select_products->execute([$fetch_cart['pid']]);
      $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
      // echo "<pre>";
      // print_r($fetch_product);
      // echo "</pre>";
      // echo "Stock:".$fetch_product["stock"];
    
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <input type="hidden" name="product_id" value="<?= $fetch_cart['pid']; ?>">
      <input type="hidden" name="quantity"  value="<?= $fetch_cart['quantity']; ?>">
      <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
      <div class="name"><?= $fetch_cart['name']; ?></div>
      <div class="flex">
         <div class="price">Rs.<?= $fetch_cart['price']; ?>/-</div>
         <input type="number" name="qty" class="qty" min="1"  onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>"  max="<?= $fetch_product['stock']; ?>">
         <button type="submit" class="fas fa-edit" name="update_qty"></button>
      </div>
      <div class="sub-total"> Sub Total : <span>Rs <?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
      <input type="submit" value="delete item" onclick="return confirm('delete this from cart?');" class="delete-btn" name="delete">
   </form>
   <?php
   $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p>Grand Total : <span>Rs.<?= $grand_total; ?>/-</span></p>
      <a href="shop.php" class="option-btn">Continue Shopping.</a>
      <!-- <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">Delete All Items ?</a> -->
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Proceed to Checkout.</a>
   </div>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>