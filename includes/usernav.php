<nav class="pcnav">

  <ul>
    <form method="GET" action="productdisplay.php" id="submit">  
    <li style="margin: 10px;"><a href="home.php"><img src="images/logo1.png" height="50px" width="200px"></a></li>

    <li><input type="text" name="search" class="searchbox" value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>" placeholder="Search for products over here...." autocomplete="off" style="border-radius: 0;height: 50px;"></li>

    <button type="submit" form="submit" style="float: left;margin-top: 10px;border:0;padding: 0;"><i class="fa fa-search fa-3x" aria-hidden="true" style="background-color: yellow;height: 50px;"></i></button>

    </form>
    
    <?php
      if (isset($_SESSION['uname']) && $_SESSION['utype']=="user") {?>
        <li class="li"><a href="logout.php" id="signin" style="margin-top: 10px;margin-left: 20px;"><i class="fas fa-sign-out-alt"></i>  LOGOUT</a></li>   
        <?php
      }
      else{?>
        <li class="li"><a href="signin.php" id="signin" style="margin-top: 10px;margin-left: 20px;"><i class="fas fa-user" aria-hidden="true"></i>  SIGN IN</a></li>
        <?php
      }
    ?>
        <li class="li" style="width: 200px;"><a href="issue.php" style="margin-top: 10px;margin-left: 20px;"><i class="fas fa-fist-raised"></i>  RAISE AN ISSUE</a></li>
  </ul>
  <ul>
    <li class="li"><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i>  HOME</a></li>
    <li class="li"><a href="productdisplay.php?type=men"><i class="fas fa-tshirt" aria-hidden="true"></i>  MEN'S WEAR</a></li>
    <li class="li"><a href="productdisplay.php?type=women"><i class="fas fa-female fa"></i>  WOMEN'S WEAR</a></li>
    <li class="li"><a href="productdisplay.php?type=kids"><i class=""></i>  KID'S WEAR</a></li>
    <li class="li"><a href="productdisplay.php?type=accessories"><i class="far fa-futbol" aria-hidden="true"></i>  ACCESSORIES</a></li>
    <li class="li"><a href="account.php"><i class="fas fa-user" aria-hidden="true"></i>  MY ACCOUNT</a></li>
    <li style="width: 100px;float: right;" class="li"><a href="cart.php"><i class="fas fa-shopping-cart fa-2x" aria-hidden="true"></i></a></li>
    <li style="width: 100px;float: right;" class="li"><a href="wishlist.php"><i class="fas fa-heart fa-2x" aria-hidden="true"></i></a></li>
  </ul>
</nav>