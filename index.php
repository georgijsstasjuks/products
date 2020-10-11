<?php 
    declare(strict_types=1);
    require 'logic/connection_db.php';
    require 'logic/Product.php';
    $obj = new dbConnection();
    $products = new Product($obj);
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
    <link rel="stylesheet" href="css/styles.css">
    
</head>
<body>

   <div class="container-fluid pl-5 pr-5">
        <div class="row pt-5">
            <div class="col-6 text-left">
                <h2>Product list</h2>
            </div>
            <div class="col-6 text-right">
                <form action="" method="POST" id="actionForm">
                <select name="action" >
                    <option value="delete">Mass delete</option>
                    <option value="add">Add new product</option>
                </select>
                <input type="submit" value="Apply" name="apply">           
            </div>
        </div>        
<hr>

<div class="row pt-2 d-flex justify-content-around">

    <?php 
       $products = $products->index();
       foreach($products as $product){
     ?>
     <div class="p-1 mb-4 border border-dark productCard">
          <input class="delCheckbox" type="checkbox" name="card[]" value="<?php echo $product['SKU'] ?>" >
          <h5 class="text-center"><?php echo $product['SKU'] ?></h5>
          <p class="cardDesc text-center"><?php echo $product['name'] ?></p>
          <p class="cardDesc text-center"><?php echo number_format((int)$product['price'],2,'.','') ?> $</p>
          <p class="cardDesc text-center"><?php echo $product['characteristics'] ?></p>
      </div>
    
    <?php
      }
    ?>
   
</div> 
</form>
</div>

<script>
    let checkbox = document.getElementsByClassName('delCheckbox');
    for (var i = 0; i < checkbox.length; i++) {
        checkbox[i].addEventListener("click", checked);
    };

    function checked(){
        let action = this.parentNode.classList.contains('checked') ? 'remove' : 'add';
        this.parentNode.classList[action]('checked');
    };
window.addEventListener('DOMContentLoaded', function() {
  var form = document.getElementById('actionForm');
  form.addEventListener('submit', async (e)=>{
    e.preventDefault();
       await fetch('routing/routes.php',{
          method: 'POST',
          body: new FormData(actionForm)
        })
        .then((response) => {
             return response.text();
        })
        .then((text) => {
            let deletingEl = document.getElementsByClassName('checked');
            [].forEach.call(deletingEl, function(el){
             el.classList.add('del');
             setTimeout(function(){el.remove()},500);
            });  
           if(text.length>2){
               location.replace(text);
            }
           
      });
      });
  });

  </script>
</body>
</html>