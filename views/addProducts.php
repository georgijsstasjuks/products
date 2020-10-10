<?php 
declare(strict_types=1);
require '../logic/Product.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<style>

.hide{
  display:none!important;
}

.show{
  display:block!important;
}
</style>

</head>
<body>



<div class="container">
  <div class="d-flex flex-row-reverse">
    <a href="../" class="btn btn-primary ">Product list</a>
  </div>

<?php 
if(isset($_SESSION['flash']))
  if($_SESSION['flash']=="success"){     
?>
<div class="alert alert-success" role="alert">A new entry has been added!</div>

<?php }else{
    ?>
  <div class="alert alert-danger" role="alert">This SKU is already used!</div>
  <?php }
  unset($_SESSION['flash']);
  ?>

<?php 
if(isset($_SESSION['required'])){
 unset($_SESSION['required']);
?>
<div class="alert alert-danger" role="alert">All fields must be filled in!</div>

<?php } ?>
 



<form method="POST" action="" id="addNewProduct">
  <div class="form-group">
    <label for="exampleInputEmail1">SKU</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="SKU" required>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Name</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="name" required>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Price</label>
    <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="price" required>
  </div>



  
<select id="selectedCharacteristic" name="selectedCharacteristic" class="main_field" aria-required="true">

    <option value="Size" selected="selected">
        DvD-disc
    </option>
    <option value="Weight">
        Book
    </option>
    <option value="Dimension">
        Furniture
    </option>
</select>

<div class="form-group hide size">
    <label for="exampleInputPassword1">Size</label>
    <input type="text" class="form-control furnitureInput" id="exampleInputEmail1" aria-describedby="emailHelp" name="characteristics[]">MB
    <small id="emailHelp" class="form-text text-muted">Please, enter the size of DvD-disc in MB.</small>
</div>

<div class="form-group hide weight">
    <label for="exampleInputPassword1">Weight</label>
    <input type="text" class="form-control furnitureInput" id="exampleInputEmail1" aria-describedby="emailHelp" name="characteristics[]">kg
    <small id="emailHelp" class="form-text text-muted">Please, enter the weight of book.</small>
</div>

<div class="form-group hide dimension">
    <label for="exampleInputPassword1">Height</label>
    <input type="number" class="form-control furnitureInput" id="exampleInputEmail1" aria-describedby="emailHelp" name="characteristics[]">
    <label for="exampleInputPassword1">Widht</label>
    <input type="number" class="form-control furnitureInput" id="exampleInputEmail1" aria-describedby="emailHelp" name="characteristics[]">
    <label for="exampleInputPassword1">Length</label>
    <input type="number" class="form-control furnitureInput" id="exampleInputEmail1" aria-describedby="emailHelp" name="characteristics[]">
    <small id="emailHelp" class="form-text text-muted">Please, provide dimension dimension in HxWxL format.</small>
</div>
<input hidden name="action" value="save">
  <button type="submit" class="btn btn-primary">Submit</button>

</form>

</div>

<script>

window.addEventListener('DOMContentLoaded', function() {
  var select = document.getElementById('selectedCharacteristic'),
  hide = document.getElementsByClassName('hide');
  inputs = document.getElementsByClassName('furnitureInput');
    function change()
    {   
      //for each element which have 'hide' class
      [].forEach.call(hide, function(el) {
        //check, does the current element have a selected class
            var action = el.classList.contains(select.value.toLowerCase()) ? "add" : "remove"
            el.classList[action]('show'); 
      });  
      //clear all chracteristic inputs when we change a type of product
      [].forEach.call(inputs, function(el){
            el.value="";
      });
    }
  select.addEventListener('change', change);
  change()
  });
  </script>

<script>
window.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('addNewProduct');
  //wen form was submmited
  form.addEventListener('submit', async (e)=>{
    //cancel the default action(reload after submit)
    e.preventDefault();
        fetch('../routing/routes.php',{
          method: 'POST',
          body: new FormData(addNewProduct) //sent to server all data from form
        })
        .then((response) => {
             return response.text();//get only text part from all server response
        })
        .then((text) => {
          location.reload();
      });
      });
  });
  </script>


</body>
</html>
