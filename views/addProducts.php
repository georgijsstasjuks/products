<?php 
declare(strict_types=1);
 session_start();

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
  display:flex!important;
}

.error{
  border-color: red!important;
  border-width: 2px;
}
</style>

</head>
<body>

<div class="container"> 

<?php 
if(isset($_SESSION['flash']))
  if($_SESSION['flash']=="success"){     
?>
<div class="alert alert-success text-center" role="alert">A new entry has been added!</div>

<?php }else{
    ?>
  <div class="alert alert-danger text-center" role="alert">This SKU is already used!</div>
  <?php }
  unset($_SESSION['flash']);
  ?>

<?php 
if(isset($_SESSION['required'])){
 
?>
<div class="alert alert-danger text-center" role="alert">All fields must be filled in!</div>

<?php } ?>

<form method="POST" action="" id="addNewProduct" class="container pt-5 mt-5">
  <div class="form-group row align-items-center">
    <label for="exampleInputEmail1" class="col-1">SKU</label>
    <input type="text" class="form-control col-9 validate" id="exampleInputEmail1" aria-describedby="emailHelp" name="SKU">
  </div>
  <div class="form-group row align-items-center">
    <label for="exampleInputPassword1" class="col-1">Name</label>
    <input type="text" class="form-control col-9 validate" id="exampleInputEmail1" aria-describedby="emailHelp" name="name" >
  </div>
  <div class="form-group row align-items-center">
    <label for="exampleInputPassword1" class="col-1">Price</label>
    <input type="number" class="form-control col-9 validate" id="exampleInputEmail1" aria-describedby="emailHelp" name="price">
  </div>


  <div class="form-group row align-items-center">
  <label for="exampleInputPassword1" class="col-2">Type swticher</label>
  <select id="type" name="type" class="col-2" aria-required="true">

    <option value="Size">
        DvD-disc
    </option>
    <option value="Weight">
        Book
    </option>
<option value="Dimension">
        Furniture
    </option>
</select>
  </div>
  
  <div class="form-group">

<div class="form-group hide size row align-items-center">
    <label for="text" class="col-1">Size</label>
    <input type="number" class="form-control furnitureInput col-9"
     id="exampleInputEmail1" aria-describedby="emailHelp" name="characteristics[MB]">
    <small id="emailHelp" class="form-text text-muted col-3">Please, enter the size of DvD-disc in MB.</small>
</div>

<div class="form-group hide weight row align-items-center">
    <label for="exampleInputPassword1" class="col-1">Weight</label>
    <input type="number" class="form-control furnitureInput col-9" 
     id="exampleInputEmail1" aria-describedby="emailHelp" name="characteristics[KG]">
    <small id="emailHelp" class="form-text text-muted col-3">Please, enter the weight of book.</small>
</div>


<div class="form-group dimension row align-items-center hide">
    <label for="exampleInputPassword1" class="col-1">Height</label>
    <input type="number" class="form-control furnitureInput col-9"
    id="exampleInputEmail1" aria-describedby="emailHelp" name="characteristics[]">
</div>

<div class="form-group  dimension row align-items-center hide">
    <label for="exampleInputPassword1" class="col-1">Widht</label>
    <input type="number" class="form-control furnitureInput col-9" 
    id="exampleInputEmail1" aria-describedby="emailHelp" name="characteristics[]">
</div>

<div class="form-group dimension row align-items-center hide">
    <label for="exampleInputPassword1" class="col-1">Length</label>
    <input type="number" class="form-control furnitureInput col-9"
    id="exampleInputEmail1" aria-describedby="emailHelp" name="characteristics[]">
    <small id="emailHelp" class="form-text text-muted col-3">Please, provide dimension dimension in HxWxL format.</small>
</div>

</div>
<input hidden name="action" value="save">

<div class="form-group row  justify-content-between align-items-center">
   <div class="col-6 col-md-5 col-sm-6 col-lg-4" >  <button type="submit" class="btn btn-success col-6 col-md-6 col-sm-6">Submit</button></div>
   <div class="col-6 col-md-5 col-sm-6 col-lg-4"> <a href="../" class="btn btn-primary col-8 col-md-7 col-sm-8 col-lg-6">Products</a></div>
   
    </div>

  </div>
</form>

</div>




<script>

window.addEventListener('DOMContentLoaded', function() {
  var select = document.getElementById('type'),
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
    }

    //clear all chracteristic inputs 
    function clear(){
      [].forEach.call(inputs, function(el){
            el.value="";
      });
    }
  select.addEventListener('change', change);
  select.addEventListener('change', clear);
  change()
  });
  </script>

<script>
window.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('addNewProduct');
  let inputs = document.getElementsByClassName('furnitureInput');
  let validate = document.getElementsByClassName('validate');
  var select = document.getElementById('type');
  //when form was submmited
  form.addEventListener('submit', async (e)=>{
    //cancel the default action(reload after submit)
  let errors = 0;
    e.preventDefault();
    //check if fields are empty 
    [].forEach.call(validate, function(el){         
            if(el.value=="") {
              el.classList.add('error');
              errors++;
            } else {
              if(el.classList.contains('error')){
                  el.classList.remove('error');
              }
            }
      });

      [].forEach.call(inputs, function(el){      
              if(el.value=="" && el.parentNode.classList.contains(select.value.toLowerCase())) {
                el.classList.add('error');
                errors++;
              } else {
                if(el.classList.contains('error')){
                    el.classList.remove('error');
                }
              }
        });

        //if all fields are not empty we can send a request to the server
      if(errors==0)
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
