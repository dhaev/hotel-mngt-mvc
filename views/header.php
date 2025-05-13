<?php
AuthHelper::startSecureSession(); // Ensure secure session handling

// Ensure CSRF token is set in the session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Add Content Security Policy (CSP) header
// header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://code.jquery.com https://cdn.jsdelivr.net https://js.stripe.com/ " . BASE_URL . "public/js/; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://js.stripe.com/; img-src 'self' data:; font-src 'self' https://cdnjs.cloudflare.com; frame-src 'self' 'unsafe-inline' https://js.stripe.com;");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>Reservation Management</title>
    <!-- Include W3Schools CSS after Bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/reset.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/w3.css"> <!-- W3Schools CSS --><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>public/css/style.css">
    <script src="<?= BASE_URL ?>public/js/jQuery.js" defer></script>
    <script src="<?= BASE_URL ?>public/js/function.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4xF86dIHNDz0W1" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous" defer></script>

</head>
<body>
    <?php include BASE_PATH . 'views/sidenav.php'; ?>

    <div class="w3-bar w3-border w3-brown nav w3-display-container" id="headbar">
        <?php if (isset($_SESSION['CustomerID'])) { ?>
            <div class="w3-bar-item w3-display-right">
                <div class="w3-bar-item w3-right">
                <img src="<?= BASE_URL ?>public/img/<?= htmlspecialchars($_SESSION['image'], ENT_QUOTES, 'UTF-8') ?>" id="img1" class="w3-circle" alt="profile picture" style="width: 50px; height: 50px;">
               
                    <a href="<?= BASE_URL ?>index.php?controller=Profile&action=view" class="w3-bar-item w3-button w3-padding-16 w3-right">Profile</a>
                    <a href="<?= BASE_URL ?>index.php?controller=Auth&action=logout" class="w3-bar-item w3-button w3-padding-16 w3-right">Logout</a>
                    <a href="<?= BASE_URL ?>index.php?controller=Auth&action=changePassword" class="w3-bar-item w3-button w3-padding-16 w3-right">Change password</a>
              </div>  
            </div>
        <?php } else { ?>
            
            <a href="<?= BASE_URL ?>index.php?controller=Signup&action=signup" class="w3-bar-item w3-button w3-padding-16 w3-right">SignUp</a>
            <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-right" onclick="document.getElementById('id01').style.display='block'">Login</a>
            <a href="<?= BASE_URL ?>index.php?controller=Book&action=index" class="w3-bar-item w3-button w3-padding-16 w3-right">Book</a>
            <a href="<?= BASE_URL ?>index.php?controller=Home&action=index" class="w3-bar-item w3-button w3-padding-16 w3-right">Home</a>

            <a href="<?= BASE_URL ?>index.php?controller=Home&action=index" class="w3-bar-item w3-button w3-border">Home</a>
   <a href="<?= BASE_URL ?>index.php?controller=Booking&action=index" class="w3-bar-item w3-button w3-border-bottom w3-border-left w3-border-right">Reservations</a>
   <a href="<?= BASE_URL ?>index.php?controller=start_date&action=index" class="w3-bar-item w3-button w3-border-bottom w3-border-left w3-border-right">Check In</a>
   <a href="<?= BASE_URL ?>index.php?controller=end_date&action=index" class="w3-bar-item w3-button w3-border-bottom w3-border-left w3-border-right">Check Out</a>
   <a href="<?= BASE_URL ?>index.php?controller=Cancel&action=index" class="w3-bar-item w3-button w3-border-bottom w3-border-left w3-border-right">Cancelled</a>
   <a href="<?= BASE_URL ?>index.php?controller=Room&action=index" class="w3-bar-item w3-button w3-border-bottom">Rooms</a>
   <a href="<?= BASE_URL ?>index.php?controller=RoomType&action=index" class="w3-bar-item w3-button w3-border-bottom">Room Types</a>
   <a href="<?= BASE_URL ?>index.php?controller=Customer&action=index" class="w3-bar-item w3-button w3-border-bottom">Customers</a>
   <a href="<?= BASE_URL ?>index.php?controller=Employee&action=index" class="w3-bar-item w3-button w3-border">Employees</a>
            
        <?php } ?>

        <?php 

        if (!isset($_SESSION['CustomerID'])) {
            include BASE_PATH . 'views/auth/login.php';
        }
        
        if (isset($_SESSION['email'])) { ?>
            <button class="w3-button w3-bar-item w3-padding-12 w3-xlarge w3_open" id="openNav">&#9776;</button>
        
x
        <?php } ?>
    </div>

    <div id="main" class="w3-padding-large w3-padding-top-64">
        <!-- Main content goes here -->
