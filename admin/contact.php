<?php
include("inc/header.php");
?>
<div class="content-wrapper dark-mode">
  <section class="content-header dark-mode">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Contacts</h1>
        </div>
      </div>
    </div>
  </section>
  <section class="content dark-mode">
    <div class="container-fluid">
      <div class="card dark-mode">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div>
            Contacts
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">All Contacts</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-sm table-bordered mb-0">
                  <thead>
                    <tr>
                      <th>Full Name</th>
                      <th>Email</th>
                      <th>Subject</th>
                      <th>Message</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT full_name, email, subject, message FROM contacts";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["full_name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["subject"] . "</td>";
                        echo "<td>" . $row["message"] . "</td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='4'>No contacts found</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


<?php
include("inc/footer.php");
?>