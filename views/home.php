<?php
AuthHelper::startSecureSession(); // Ensure secure session handling

// Calculate total price
function calculatePrice($pricePerRoom, $numRooms, $numDays) {
    return $pricePerRoom * $numRooms * $numDays;
}

// Default check-in and check-out dates
$start_date = $_POST['start_date'] ?? date('Y-m-d');
$end_date = $_POST['end_date'] ?? date('Y-m-d', strtotime('+1 day'));
$numDays = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
$numDays = $numDays > 0 ? $numDays : 1; // Ensure at least 1 day

// Handle form submission
$roomPrices = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['room'] as $index => $room) {
        $rtypeID = $room['rtype'];
        $numRooms = $room['numr'];
        $selectedRoom = array_filter($roomTypes, fn($r) => $r['RtypeID'] == $rtypeID);
        $selectedRoom = reset($selectedRoom);

        if ($selectedRoom) {
            $pricePerRoom = $selectedRoom['price'];
            $roomPrices[$index] = calculatePrice($pricePerRoom, $numRooms, $numDays);
        } else {
            $roomPrices[$index] = 0;
        }
    }
}
?>

<div class="container mt-5">
   <h2 class="text-center mb-4">Book Room</h2>
   <form id="textForm" action="<?= BASE_URL?>index.php?controller=Booking&action=add" method="post">
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
         <?php foreach ($_POST['room'] ?? [['rtype' => '', 'numr' => 1]] as $index => $room): ?>
         <div class="form-row justify-content-center room-row">
            <div class="form-group col-md-2">
               <label for="rtype_<?= $index ?>">Room Type</label>
               <select class="form-control rtype" id="rtype_<?= $index ?>" name="room[<?= $index ?>][rtype]" required>
                  <option value="">Select Room Type</option>
                  <?php foreach ($roomTypes as $roomType): ?>
                     <option value="<?= $roomType['RtypeID'] ?>" data-price="<?= $roomType['price'] ?>" <?= $room['rtype'] == $roomType['RtypeID'] ? 'selected' : '' ?>>
                        <?= $roomType['rtype'] ?>
                     </option>
                  <?php endforeach; ?>
               </select>
            </div>
            <div class="form-group col-md-2">
               <label for="numr_<?= $index ?>">Number of Rooms</label>
               <input type="number" class="form-control numr" name="room[<?= $index ?>][numr]" id="numr_<?= $index ?>" min="1" max="5" value="<?= $room['numr'] ?? 1 ?>" required>
            </div>
            <div class="form-group col-md-2">
               <label for="price_<?= $index ?>">Price</label>
               <input class="form-control price" type="text" name="room[<?= $index ?>][price]" id="price_<?= $index ?>" value="<?= $roomPrices[$index] ?? 0 ?>" readonly>
            </div>
         </div>
         <?php endforeach; ?>
      </div>
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4 text-right">
            <button id="addRoom" type="button" class="btn btn-secondary">Add Room</button>
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
      
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4 text-center">
            <button class="btn btn-primary" type="submit" name="book">Book</button>
         </div>
      </div>
   </form>
</div>

<script src="https://js.stripe.com/v3/"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function calculatePrice(row) {
        const start_date = new Date(document.getElementById('cin').value);
        const end_date = new Date(document.getElementById('cout').value);
        const numDays = Math.max((end_date - start_date) / (1000 * 60 * 60 * 24), 1); // Ensure at least 1 day

        const roomType = row.querySelector('.rtype');
        const numRooms = row.querySelector('.numr').value || 1;
        const priceField = row.querySelector('.price');

        const selectedOption = roomType.options[roomType.selectedIndex];
        const pricePerRoom = selectedOption.dataset.price || 0;

        const totalPrice = pricePerRoom * numRooms * numDays;
        priceField.value = totalPrice.toFixed(2);
    }

    function updateAllPrices() {
        document.querySelectorAll('.room-row').forEach(calculatePrice);
    }

    document.getElementById('cin').addEventListener('change', updateAllPrices);
    document.getElementById('cout').addEventListener('change', updateAllPrices);

    document.getElementById('roomContainer').addEventListener('change', function (event) {
        if (event.target.classList.contains('rtype') || event.target.classList.contains('numr')) {
            const row = event.target.closest('.room-row');
            calculatePrice(row);
        }
    });

    document.getElementById('addRoom').addEventListener('click', function () {
        const roomContainer = document.getElementById('roomContainer');
        const newIndex = roomContainer.querySelectorAll('.room-row').length;

        const newRow = document.createElement('div');
        newRow.className = 'form-row justify-content-center room-row';
        newRow.innerHTML = `
            <div class="form-group col-md-2">
               <label for="rtype_${newIndex}">Room Type</label>
               <select class="form-control rtype" id="rtype_${newIndex}" name="room[${newIndex}][rtype]" required>
                  <option value="">Select Room Type</option>
                  <?php foreach ($roomTypes as $roomType): ?>
                     <option value="<?= $roomType['RtypeID'] ?>" data-price="<?= $roomType['price'] ?>">
                        <?= $roomType['rtype'] ?>
                     </option>
                  <?php endforeach; ?>
               </select>
            </div>
            <div class="form-group col-md-2">
               <label for="numr_${newIndex}">Number of Rooms</label>
               <input type="number" class="form-control numr" name="room[${newIndex}][numr]" id="numr_${newIndex}" min="1" max="5" value="1" required>
            </div>
            <div class="form-group col-md-2">
               <label for="price_${newIndex}">Price</label>
               <input class="form-control price" type="text" name="room[${newIndex}][price]" id="price_${newIndex}" value="0" readonly>
            </div>
        `;
        roomContainer.appendChild(newRow);
    });
});
</script>