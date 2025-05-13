<?php
if (!isset($reservation) || !isset($roomDetails)) {
    echo '<div class="alert alert-danger">Reservation data is missing.</div>';
    exit();
}

$start_date = $reservation['start_date'];
$end_date = $reservation['end_date'];
$num_days = $reservation['num_days'];
?>


<div class="container mt-5">
   <h2 class="text-center mb-4">Edit Reservation</h2>
   <form id="textForm"  action="<?= BASE_URL?>index.php?controller=Booking&action=edit" method="post">
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4">
            <label for="cin">Check in</label>
            <input type="date" class="form-control" name="start_date" id="cin" value="<?= htmlspecialchars($start_date, ENT_QUOTES, "UTF-8"); ?>" required>
         </div>
         <div class="form-group col-md-4">
            <label for="cout">Check out</label>
            <input type="date" class="form-control" name="end_date" id="cout" value="<?= htmlspecialchars($end_date, ENT_QUOTES, "UTF-8"); ?>" required>
         </div>
      </div>
      <div id="roomContainer">
         <?php foreach ($roomDetails as $index => $detail) { ?>
         <div class="form-row justify-content-center room-row">
            <div class="form-group col-md-2">
               <label for="rtype_<?= $index; ?>">Room Type</label>
               <select class="form-control rtype" id="rtype_<?= $index; ?>" name="room[<?= $index; ?>][rtype]" required>
               <?php foreach ($roomTypes as $roomType): ?>
                     <option value="<?= $roomType['RtypeID'] ?>" data-price="<?= $roomType['price'] ?>" <?= $detail['type_id'] == $roomType['RtypeID'] ? 'selected' : '' ?>>
                        <?= $roomType['rtype'] ?>
                     </option>
                  <?php endforeach; ?>
               </select>
            </div>
            <div class="form-group col-md-2">
               <label for="numr_<?= $index; ?>">Number of Rooms</label>
               <input type="number" class="form-control numr" name="room[<?= $index; ?>][numr]" id="numr_<?= $index; ?>" value="<?= htmlspecialchars($detail['num_rooms'], ENT_QUOTES, "UTF-8"); ?>" min="1" max="5" required>
            </div>
            <div class="form-group col-md-2">
               <label for="price_<?= $index; ?>">Price</label>
               <input class="form-control price" type="text" name="room[<?= $index; ?>][price]" id="price_<?= $index; ?>" value="<?= htmlspecialchars($detail['sub_total'] * $num_days, ENT_QUOTES, "UTF-8"); ?>" readonly>
            </div>
            <div class="form-group col-md-1 align-self-end">
               <button type="button" class="btn btn-danger remove-room">Remove</button>
            </div>
         </div>
         <?php } ?>
      </div>
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4 text-right">
            <button id="addRoom" type="button" class="btn btn-secondary">Add Room</button>
         </div>
      </div>

      <input type="hidden" name="reservation_id" id="reservation_id" value="<?= htmlspecialchars($reservation['id'], ENT_QUOTES, "UTF-8"); ?>">
      
      <div class="form-row justify-content-center">
         <div class="form-group col-md-4 text-center">
            <button class="btn btn-primary" type="submit" name="update">Update</button>
         </div>
      </div>
   </form>
</div>


<script>
function updateRoomTypeOptions() {
    const selects = document.querySelectorAll('.rtype');
    const selectedValues = Array.from(selects)
        .map(sel => sel.value)
        .filter(val => val !== '');

    selects.forEach(select => {
        const currentValue = select.value;
        Array.from(select.options).forEach(option => {
            if (option.value === "" || option.value === currentValue) {
                option.disabled = false;
            } else {
                option.disabled = selectedValues.includes(option.value);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    updateRoomTypeOptions();

    document.getElementById('roomContainer').addEventListener('change', function (event) {
        if (event.target.classList.contains('rtype')) {
            updateRoomTypeOptions();
        }
    });

    // If you have dynamic add room functionality, call updateRoomTypeOptions after adding a new row
});
</script>
<script>
    window.roomTypes = <?= json_encode($roomTypes); ?>;
</script>
<script src="/assets/js/room-management.js"></script>