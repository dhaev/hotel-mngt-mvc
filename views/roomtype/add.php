<div class="w3-content">              
<form class="add_room_type w3-row-padding" id="textForm" action="<?= BASE_URL ?>index.php?controller=RoomType&action=add" method="post" enctype="multipart/form-data">
   
   <div class="w3-col"><h2 class="w3-center w3-padding-large w3-margin-top w3-margin-bottom">Room Type</h2>
         </div><div class="w3-half">
          <p><img id='img' class="w3-input  w3-margin-bottom" src="img/noimage.png"  ></p>
      <p><input id='file' class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom"   type="file" onchange='showimg()'  name='file' required></p>
   </div>
      <div class="w3-half">
       <p>
                  <label class=" w3-padding-large ">Room Type</label></p><p>
      <input type="text" id="rtype" name="rtype" class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom"   placeholder="Room Type..." required></p>
      <p>
                  <label class=" w3-padding-large ">Price</label></p><p>
      <input type="number" id="price" class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom"   name="price" placeholder="price..." required></p>
      <p>
                  <label class=" w3-padding-large ">Description</label></p><p>
      <textarea  name="desc" id="desc" class="w3-input w3-border w3-round-small w3-padding-large w3-margin-top w3-margin-bottom"   placeholder="description..." required> </textarea></p> 
      </div>
           <div class="w3-col">  <p><button class="w3-input w3-round-xxlarge w3-padding-large w3-margin-top w3-margin-bottom"   id="chng" name="add" >Add</button></p>
       </div>
        
</form>    </div>