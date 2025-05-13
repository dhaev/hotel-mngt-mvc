//-----PREVENT-SUBMIT----------
  $(document).ready(function(){

    console.log('my js is ready');
   $('.w3_open').click(function () {
    $("#main").css({'marginLeft':'15%'});
    $("#mySidebar").css({'width':'15%'});
    $("#mySidebar").css({'display':'block'});
  });

  $('.w3_close').click(function () {
    $("#headbar").css({'marginLeft':'0%'});
    $("#main").css({'marginLeft':'0%'});
    $("#mySidebar").css({'display':'none'});
   
  });


    });

    
function showimg() {
  var file = document.getElementById('file').files[0];
  if (file) {
      var reader = new FileReader();
      reader.onload = function(e) {
          var img = document.getElementById('img');
          img.src = e.target.result;
      }
      reader.readAsDataURL(file);
  }
}

function showEditMenu() {
  document.getElementById('view_menu').style.display = 'none';
  document.getElementById('edit_menu').style.display = 'block';
}

function cancelUpload() {
  var img = document.getElementById('img');
  var fileInput = document.getElementById('file');
  fileInput.value = '';
  img.src= document.getElementById('org_img').value;
  console.log('kjhgffgh' + document.getElementById('org_img').value);
  document.getElementById('view_menu').style.display = 'block';
  document.getElementById('edit_menu').style.display = 'none';
}



function checkRoomAvailability() {
  var checkinDate = $('#cin').val();
  var checkoutDate = $('#cout').val();

  $.ajax({
    url: 'index.php?controller=Room&action=checkRoomAvailability',
    type: 'GET',
    data: { start: checkinDate, end: checkoutDate },
    dataType: 'json',
    success: function(data) {
      window.roomAvailability = data; // Store room availability data globally
      updateAllRoomTypes(); // Update room types based on availability
      updateAllPricesAndAvailability(); // Update prices and availability for all room types
    },
    error: function() {
      alert('Failed to check room availability');
    }
  });
}

function populateRoomTypes(index = 0) {
  var roomTypeSelect = $(`#rtype_${index}`);
  var selectedValue = roomTypeSelect.val(); // Get the currently selected value
  roomTypeSelect.empty(); // Clear existing options

  // Populate room types based on availability
  roomAvailability.forEach(function(room) {
    var option = $('<option>', { value: room.RtypeID, text: room.rtype });
    if (selectedValue && selectedValue == room.RtypeID) {
      option.attr('selected', 'selected'); // Retain the selected value
    }
    roomTypeSelect.append(option);
  });
}
function priceCalculation(pricePerRoom, numRooms, numDays) {
  if(numRooms && numRooms > 0) {
  var totalPrice = pricePerRoom * numRooms * numDays;
  return totalPrice;
}}
function updatePriceAndAvailability(target) {
  var row = $(target).closest('.room-row');
  var roomTypeSelect = row.find('.rtype');
  var numRoomsInput = row.find('.numr');
  var priceInput = row.find('.price');
  var messageDiv = row.find('.availability-message');

  var selectedRoom = roomAvailability.find(function(room) {
    return room.RtypeID == roomTypeSelect.val();
  });

  var checkinDate = new Date($('#cin').val());
  var checkoutDate = new Date($('#cout').val());
 
  if (selectedRoom && checkinDate && checkoutDate && !isNaN(checkinDate) && !isNaN(checkoutDate)) {
    var pricePerRoom = selectedRoom.price;
    var numRooms = numRoomsInput.val() || 1; // Default to 0 if numRooms is not set
    var timeDiff = Math.abs(checkoutDate - checkinDate);
    var numDays = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
  

    if (selectedRoom.no_of_available_rooms >= 5) {
      numRoomsInput.attr('max', 5);
      messageDiv.text('').hide();
    } else{// if (selectedRoom.no_of_available_rooms < 5) {
      numRoomsInput.attr('max', selectedRoom.no_of_available_rooms);
      messageDiv.text(`Only ${selectedRoom.no_of_available_rooms} room(s) left`).show();
    }

    if (selectedRoom.no_of_available_rooms <= numRooms) {
      numRoomsInput.val(selectedRoom.no_of_available_rooms);
      priceInput.val(priceCalculation(pricePerRoom, selectedRoom.no_of_available_rooms, numDays));
      messageDiv.text(`Only ${selectedRoom.no_of_available_rooms} room(s) left`).show();
    }else if (selectedRoom.no_of_available_rooms > numRooms) {
      numRoomsInput.val(numRooms);
      priceInput.val(priceCalculation(pricePerRoom, numRooms, numDays));
    } else {
      numRoomsInput.val(numRooms);
      priceInput.val(priceCalculation(pricePerRoom, numRooms, numDays));
      // messageDiv.text('').hide();
    }
    
    
  } else {
    priceInput.val(0);
    messageDiv.hide();
  }
}

function updateAllPricesAndAvailability() {
  $('.room-row').each(function() {
    var roomTypeSelect = $(this).find('.rtype');
    if (roomTypeSelect.val()) {
      updatePriceAndAvailability(roomTypeSelect);
    }
  });
}

function updateAllRoomTypes() {
  $('.room-row').each(function(index) {
    populateRoomTypes(index);
  });
}

