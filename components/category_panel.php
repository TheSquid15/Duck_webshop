<?php ?>

<div class="container mt-5 p-4 product_panel">
    <h3>Categories</h3>
    <div class="row">
        <div class="col-2 pl-4 d-flex align-items-center">
            <b><p class="m-0">Category ID</p></b>
        </div>
        <div class="col-8 pl-4 d-flex align-items-center">
            <b><p class="m-0">Category name</p></b>
        </div>
        <div class="col-2 pl-4 d-flex align-items-center justify-content-end">
            <b><p class="m-0">Edit/Delete</p></b>
        </div>
    </div>
<?php $dashboard->category_panel();?>
<a href="add_category.php"><button class="btn btn-primary mt-3">Add category</button></a>
</div>