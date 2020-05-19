<?php ?>

<div class="container mt-2 p-4 product_panel">
    <h3>Products</h3>
    <div class="row">
        <div class="col-1 pl-4 d-flex align-items-center">
            <b><p class="m-0">Image</p></b>
        </div>
        <div class="col-4 pl-4 d-flex align-items-center">
            <b><p class="m-0">Name</p></b>
        </div>
        <div class="col-3 d-flex align-items-center">
            <b><p class="m-0">Category</p></b>
        </div>
        <div class="col-2 pl-1 d-flex align-items-center">
            <b><p class="m-0">Price</p></b>
        </div>
        <div class="col-2 pr-4 d-flex align-items-center justify-content-end">
            <b><p class="m-0">Edit/Delete</p></b>
        </div>
    </div>
    <?php $dashboard->product_panel();?>
    <a href="upload_item.php"><button class="btn btn-primary mt-3">Add item</button></a>
</div>

