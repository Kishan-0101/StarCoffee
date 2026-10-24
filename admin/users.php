<?php
include("inc/header.php");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper dark-mode">
  <!-- Content Header (Page header) -->
  <section class="content-header dark-mode">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>All Users</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">All Users</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content dark-mode">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">All Users displayes here.</h3>
            </div>
            <div class="card-body">
              <table id="dtable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sr. No.</th>
                    <th>Users</th>
                    <th>User Email</th>
                    <th>User Password</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = "SELECT * FROM users ORDER BY id ASC";
                  $result = mysqli_query($conn, $query);
                  while ($row = mysqli_fetch_assoc($result)) {
                    # code...
                    echo '<tr>
                            <td>' . $row['id'] . '</td>
                            <td>' . strtoupper(explode('@', $row['email'])[0]) . '</td>
                            <td>' . $row['email'] . '</td>
                            <td>' . $row['password'] . '</td>
                          </tr>';
                  }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr. No.</th>
                    <th>Users</th>
                    <th>User Email</th>
                    <th>User Password</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include("inc/footer.php");
?>