<?php

AuthHelper::startSecureSession(); // Ensure secure session handling

if (isset($_SESSION['email'])) {
?>
<div class="w3-sidebar w3-mobile w3-bar-block w3-card w3-animate-left w3-brown w3-padding-top-64 w3-third" style="display:none" id="mySidebar">
  <button class=" w3-button w3-large w3-display-topright w3_close"> &times;</button>

   <a href="<?= BASE_URL ?>index.php?controller=Home&action=index" class="w3-bar-item w3-button w3-border">Home</a>
   <a href="<?= BASE_URL ?>index.php?controller=Reservations&action=view" class="w3-bar-item w3-button w3-border-bottom w3-border-left w3-border-right">Reservations</a>
   <a href="<?= BASE_URL ?>index.php?controller=start_date&action=view" class="w3-bar-item w3-button w3-border-bottom w3-border-left w3-border-right">Check In</a>
   <a href="<?= BASE_URL ?>index.php?controller=end_date&action=view" class="w3-bar-item w3-button w3-border-bottom w3-border-left w3-border-right">Check Out</a>
   <a href="<?= BASE_URL ?>index.php?controller=Cancel&action=view" class="w3-bar-item w3-button w3-border-bottom w3-border-left w3-border-right">Cancelled</a>
   <a href="<?= BASE_URL ?>index.php?controller=Rooms&action=view" class="w3-bar-item w3-button w3-border-bottom">Rooms</a>
   <a href="<?= BASE_URL ?>index.php?controller=RoomTypes&action=view" class="w3-bar-item w3-button w3-border-bottom">Room Types</a>
   <a href="<?= BASE_URL ?>index.php?controller=Customers&action=view" class="w3-bar-item w3-button w3-border-bottom">Customers</a>
   <a href="<?= BASE_URL ?>index.php?controller=Employees&action=view" class="w3-bar-item w3-button w3-border">Employees</a>
</div>

<?php 
}
?>