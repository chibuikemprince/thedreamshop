 <nav class="navbar navbar-expand-lg navbar-light bg-dark fixed py-2">
     <a class="navbar-brand text-primary text-uppercase font-weight-bold" href="#">
         <h3>Dreams Tuck Shop</h3>
     </a>
     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
     </button>


     
     <div class="collapse navbar-collapse" id="navbarNav">
         <ul class="navbar-nav">
             <li class="nav-item ">
                 <a class="nav-link text-light text-uppercase font-weight-bold px-3" href="index.php"> <i class="fas fa-home text-primary"></i> Home <span class="sr-only">(current)</span></a>
             </li>
             <?php
             if(isset($_SESSION["userid"]))
             {
                 ?>
              <li class="nav-item">
                 <a class="nav-link text-light text-uppercase font-weight-bold px-3" href="logout.php"> <i class="fas fa-sign-out-alt text-danger"></i> Logout</a>
             </li>
                 <?php
             }
             ?>
         

         </ul>
     </div>
 </nav>

 <style>
.popup {
    position: fixed;
    left: 77%;
    top: 30%;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
    background-color: white;
    border: 1px solid #ccc;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: none; /* Hidden by default */
    z-index: 1000;
}

.popup-header {
    background-color: #f1f1f1;
    padding: 10px;
    text-align: center;
}

.popup-content {
    padding: 10px;
}

</style>