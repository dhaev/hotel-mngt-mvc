<?php

if (!isset($roomType) || empty($roomType)) {
    echo '<div class="alert alert-danger">Room Type not found.</div>';
    exit();
}
?>
<div class="w3-content w3-row-padding">              
<div class="w3-col">
    <h2 id="headtitle" class="w3-center w3-padding-large w3-margin-top w3-margin-bottom">
        Edit <?= htmlspecialchars($roomType['rtype'], ENT_QUOTES, "UTF-8"); ?>
    </h2>
</div>

    <form id="imageForm" action="<?= BASE_URL?>index.php?controller=RoomType&action=edit"method="post" enctype="multipart/form-data">
 <div class="w3-half">
       <input type="hidden" name="id" value="<?= htmlspecialchars($roomType['RtypeID'], ENT_QUOTES, "UTF-8"); ?>">
    <div id="img_menu" class="w3-row-padding w3-center">
            <p><img id='img' class="w3-input w3-margin-bottom" src="<?= BASE_URL  .'/public/'?><?= htmlspecialchars($roomType['image'], ENT_QUOTES, "UTF-8"); ?>" style="max-width: 600px; height: 385px;"></p>
            <p><input type="text" id="org_img"  value="<?= BASE_URL  .'/public/'?><?= htmlspecialchars($roomType['image'], ENT_QUOTES, "UTF-8"); ?>" required hidden></p>
            <p><input type="text" id="img_rtype" name="rtype" class="rtype w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" value="<?= htmlspecialchars($roomType['rtype'], ENT_QUOTES, "UTF-8"); ?>" required hidden></p>
            <p><input type="text" id="img_rtype_id" name="id" class="id w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" value="<?= htmlspecialchars($roomType['RtypeID'], ENT_QUOTES, "UTF-8"); ?>" required hidden></p>
        </div>
        <div id="view_menu" class="w3-row-padding w3-center">
            <div class="w3-col">
                <p><button type="button" class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom w3-blue" id="editimg" name="edit_rtype_image" onclick="document.getElementById('view_menu').style.display='none';document.getElementById('edit_menu').style.display='block';">Edit</button></p>
            </div>
        </div>
        <div id="edit_menu" class="w3-row-padding w3-center" style="display: none;">
            <div class="w3-col">
                <p><input id='file' class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" type="file" onchange='showimg()' name='file'></p>
            </div>
            <div class="w3-half">
                <button class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom w3-green" type="submit" id='uploadImage' name="uploadImage">Save</button>
            </div>
            <div class="w3-half">
                <button class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom w3-red" type="button" id='canceluploadImage' name="canceluploadImage" onclick= "cancelUpload();" >Cancel</button>
            </div>
        </div>

</div>
<div class="w3-half">
        <input type="hidden" name="id" value="<?= htmlspecialchars($roomType['RtypeID'], ENT_QUOTES, "UTF-8"); ?>">
        <p><label class="w3-padding-large">Room Type</label></p>
        <p><input type="text" id="rtype" name="rtype" class="rtype w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" value="<?= htmlspecialchars($roomType['rtype'], ENT_QUOTES, "UTF-8"); ?>" placeholder="Room Type..." required></p>
        <p><label class="w3-padding-large">Price</label></p>
        <p><input type="number" id="price" class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" name="price" value="<?= htmlspecialchars($roomType['price'], ENT_QUOTES, "UTF-8"); ?>" placeholder="Price..." required></p>
        <p><label class="w3-padding-large">Description</label></p>
        <p><textarea name="desc" id="desc" class="w3-input w3-border w3-round-small w3-padding-large w3-margin-top w3-margin-bottom" placeholder="Description..." required><?= htmlspecialchars($roomType['description'], ENT_QUOTES, "UTF-8"); ?></textarea></p>
        <p><input type="text" name="rtid" class="id w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" value="<?= htmlspecialchars($roomType['RtypeID'], ENT_QUOTES, "UTF-8"); ?>" required hidden></p>
        <div class="w3-col">
            <p><button class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom" id="chng" name="edit_rtype_text">Save</button></p>
        </div> 

</div>
</form>
</div>