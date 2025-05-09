<?php
require_once 'config.php';
require_once 'header.php';

$id = $_GET['id'];
$sql = 'SELECT * FROM room_type WHERE RtypeID = ?';
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo('Failed to connect');
    exit();
}
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
?>
<script>
   $(function () {
      $('#img').attr('src', 'img/rtype/<?= $row['image']; ?>');
      $('#preview').attr('src', 'img/rtype/<?= $row['image']; ?>');
      $('.rtype').val('<?= $row['rtype']; ?>');
      $('.id').val('<?= $id; ?>');
      $('#headtitle').html('Edit <?= $row['rtype']; ?>');
      $('#price').val('<?= $row['price']; ?>');
      $('#desc').val('<?= $row['description']; ?>');
   });
</script>
<?php } ?>
<div class="w3-content w3-row-padding">              
<div class="w3-col">
    <h2 id="headtitle" class="w3-center w3-padding-large w3-margin-top w3-margin-bottom"></h2>
</div>
<div class="w3-half">
    <form id="imageForm" action="inc/update_rtype_image.php?id=<?= $id; ?>" method="post" enctype="multipart/form-data">
        <div id="view_menu" class="w3-row-padding w3-center">
            <p><img id='img' class="w3-input w3-margin-bottom" src="" style="max-width: 600px; height: 385px;"></p>
            <p><input type="text" id="img_rtype" name="rtype" class="rtype w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" required hidden></p>
            <p><input type="text" id="img_rtype_id" name="id" class="id w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" required hidden></p>
            <div class="w3-col">
                <p><button type="button" class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom w3-blue" id="editimg" name="edit_rtype_image" onclick="showEditMenu()">Edit</button></p>
            </div>
        </div>
        <div id="edit_menu" class="w3-row-padding w3-center" style="display: none;">
            <p><img id='preview' class="w3-input w3-margin-bottom" src="" style="max-width: 600px; height: 300px;"></p>
            <div class="w3-col">
                <p><input id='file' class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" type="file" onchange='showimg()' name='file'></p>
            </div>
            <div class="w3-half">
                <button class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom w3-green" type="submit" id='uploadImage' name="uploadImage">Save</button>
            </div>
            <div class="w3-half">
                <button class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom w3-red" type="button" id='canceluploadImage' name="canceluploadImage" onclick="cancelUpload()">Cancel</button>
            </div>
        </div>
    </form>
</div>
<div class="w3-half">
    <form id="textForm" action="inc/update_rtype.php" method="post">
        <p><label class="w3-padding-large">Room Type</label></p>
        <p><input type="text" id="rtype" name="rtype" class="rtype w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" placeholder="Room Type..." required></p>
        <p><label class="w3-padding-large">Price</label></p>
        <p><input type="number" id="price" class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" name="price" placeholder="Price..." required></p>
        <p><label class="w3-padding-large">Description</label></p>
        <p><textarea name="desc" id="desc" class="w3-input w3-border w3-round-small w3-padding-large w3-margin-top w3-margin-bottom" placeholder="Description..." required></textarea></p>
        <p><input type="text" name="rtid" class="id w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" required hidden></p>
        <div class="w3-col">
            <p><button class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" id="chng" name="edit_rtype_text">Save</button></p>
        </div> 
    </form>
</div>
</div>
<script>
function showEditMenu() {
    document.getElementById('view_menu').style.display = 'none';
    document.getElementById('edit_menu').style.display = 'block';
}
function cancelUpload() {
    document.getElementById('view_menu').style.display = 'block';
    document.getElementById('edit_menu').style.display = 'none';
}
</script>
<?php
require_once 'footer.php';
?>