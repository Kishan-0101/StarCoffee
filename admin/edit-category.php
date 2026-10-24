<?php
include("inc/header.php");

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM category WHERE category_id = $category_id";
$result = mysqli_query($conn, $query);
$category = mysqli_fetch_assoc($result);
$category_name = $category ? $category['category_name'] : '';
$category_image = $category ? $category['category_image'] : '';

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper dark-mode">
    <!-- Content Header (Page header) -->
    <section class="content-header dark-mode">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Category</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Edit Category</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content dark-mode">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Update category details.</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="category_process.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">

                                <?php
                                if (isset($_SESSION['success'])) {
                                    echo '<p class="alert alert-success">' . $_SESSION['success'] . '</p>';
                                    unset($_SESSION['success']);
                                }
                                ?>

                                <div class="form-group">
                                    <label for="category">Category Name</label>
                                    <input type="text" value="<?php echo htmlspecialchars($category_name); ?>"
                                        class="form-control" name="cat_name" id="category"
                                        placeholder="Add new category">
                                </div>
                                <div class="form-group">
                                    <label for="category">Category Description</label>
                                    <textarea class="form-control"
                                        value="<?php echo htmlspecialchars($category_desciption); ?>" name="cat_desc"
                                        id="category" placeholder="Update Short Description." rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="category_img">Image
                                        <img width="50"
                                            src="../category-images/<?php echo htmlspecialchars($category_image); ?>"
                                            alt="Product image representing <?php echo htmlspecialchars($category_name); ?> displayed in the StarCoffee admin panel">
                                    </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="category_img"
                                                name="category_image">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
                                <button type="submit" class="btn btn-info">Update</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include("inc/footer.php");
?>