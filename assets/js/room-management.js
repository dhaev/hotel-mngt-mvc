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
                  ${window.roomTypes.map(room => `
                     <option value="${room.RtypeID}" data-price="${room.price}">
                        ${room.rtype}
                     </option>
                  `).join('')}
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
