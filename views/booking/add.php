<?php
// Calculate total price
function calculatePrice($pricePerRoom, $numRooms, $numDays) {
    return $pricePerRoom * $numRooms * $numDays;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process booking form submission
    // Validate and save booking details to the database
}

// Default check-in and check-out dates
$start_date = date('Y-m-d');
$end_date = date('Y-m-d', strtotime('+1 day'));
?>

<div class="container mt-5">
   <h2 class="text-center mb-4">Book Room</h2>
   <form id="textForm" action="" method="post">
      <div class="form-row justify-content-center dates">
         <div class="form-group col-md-4">
            <label for="cin">Check in</label>
            <input type="date" class="form-control date" name="start_date" id="cin" value="<?= $start_date ?>" required>
         </div>
         <div class="form-group col-md-4">
            <label for="cout">Check out</label>
            <input type="date" class="form-control date" name="end_date" id="cout" value="<?= $end_date ?>" required>
         </div>
      </div>
      <div id="roomContainer">
         <?php
         // Generate initial room row
         $index = 0;
         ?>
         <div class="form-row justify-content-center room-row">
            <div class="form-group col-md-2">
               <label for="rtype_<?= $index ?>">Room Type</label>
               <select class="form-control rtype" id="rtype_<?= $index ?>" name="room[<?= $index ?>][rtype]" required>
                  <option value="">Select Room Type</option>
                  <?php foreach ($roomTypes as $room): ?>
                     <option value="<?= $room['RtypeID'] ?>"><?= $room['rtype'] ?></option>
                  <?php endforeach; ?>
               </select>
            </div>
            <div class="form-group col-md-2">
               <label for="numr_<?= $index ?>">Number of Rooms</label>
               <input type="number" class="form-control numr" name="room[<?= $index ?>][numr]" id="numr_<?= $index ?>" min="1" max="5" required>
            </div>
            <div class="form-group col-md-2">
               <label for="price_<?= $index ?>">Price</label>
               <input class="form-control price" type="text" name="room[<?= $index ?>][price]" id="price_<?= $index ?>" value="0" readonly>
            </div>
         </div>
      </div>
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4 text-right">
            <button id="addRoom" type="submit" class="btn btn-secondary">Add Room</button>
         </div>
      </div>
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4">
            <label for="fname">Firstname</label>
            <input type="text" class="form-control" name="fname" id="fname" value="" required>
         </div>
         <div class="form-group col-md-4">
            <label for="lname">Lastname</label>
            <input type="text" class="form-control" name="lname" id="lname" value="" required>
         </div>
      </div>
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="Email" value="" required>
         </div>
         <div class="form-group col-md-4">
            <label for="phone">Phone number</label>
            <input type="tel" class="form-control" name="phone" id="phone" value="" required>
         </div>
      </div>
      <!-- <div class="form-row justify-content-center">
         <div class="form-group col-md-4">
            <label for="card-element">Credit or debit card</label>
            <div id="card-element">
            </div>
            <div id="card-errors" role="alert"></div>
         </div>
      </div> -->
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4 text-center">
            <button class="btn btn-primary" type="submit" name="book">Book</button>
         </div>
      </div>
   </form>
</div>

<script>
    window.roomTypes = <?= json_encode($roomTypes); ?>;
</script>
<script src="/assets/js/room-management.js"></script>