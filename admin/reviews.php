<?php
include("inc/header.php");

$status = isset($_GET['status']) ? trim($_GET['status']) : 'all';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

if (!empty($_GET['action']) && !empty($_GET['id'])) {
  $id = intval($_GET['id']);
  if ($_GET['action'] === 'approve') {
    $stmt = $conn->prepare("UPDATE reviews SET status = 1 WHERE review_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: reviews.php?status=' . urlencode($status));
    exit;
  } elseif ($_GET['action'] === 'unapprove') {
    $stmt = $conn->prepare("UPDATE reviews SET status = 0 WHERE review_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: reviews.php?status=' . urlencode($status));
    exit;
  } elseif ($_GET['action'] === 'delete') {
    $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: reviews.php?status=' . urlencode($status));
    exit;
  }
}

$where = '';
if ($status === 'approved') {
  $where = 'WHERE r.status = 1';
} elseif ($status === 'pending') {
  $where = 'WHERE r.status = 0';
}

$sqlCount = "SELECT COUNT(*) AS cnt FROM reviews r $where";
$resCnt = $conn->query($sqlCount);
$totalRows = 0;
if ($resCnt && $row = $resCnt->fetch_assoc()) {
  $totalRows = intval($row['cnt']);
}
$totalPages = max(1, (int) ceil($totalRows / $perPage));
if ($page > $totalPages) {
  $page = $totalPages;
  $offset = ($page - 1) * $perPage;
}

$stmt = $conn->prepare("SELECT r.review_id, r.user_id, r.product_id, r.rating, r.review_text, r.status, r.created_at,
                                u.email,
                                p.p_name, p.p_image
                         FROM reviews r
                         LEFT JOIN users u ON u.id = r.user_id
                         LEFT JOIN products p ON p.p_id = r.product_id
                         $where
                         ORDER BY r.review_id DESC
                         LIMIT ? OFFSET ?");
$stmt->bind_param('ii', $perPage, $offset);
$stmt->execute();
$res = $stmt->get_result();
$reviews = [];
while ($r = $res->fetch_assoc()) {
  $reviews[] = $r;
}
$stmt->close();
?>

<div class="content-wrapper dark-mode">
  <section class="content-header dark-mode">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Reviews</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content dark-mode">
    <div class="container-fluid">
      <div class="card dark-mode">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div>

            <a href="reviews.php?status=all"
              class="btn btn-sm <?php echo $status === 'all' ? 'btn-primary' : 'btn-outline-primary'; ?>">All</a>
            <a href="reviews.php?status=approved"
              class="btn btn-sm <?php echo $status === 'approved' ? 'btn-success' : 'btn-outline-success'; ?>">Approved</a>
            <a href="reviews.php?status=pending"
              class="btn btn-sm <?php echo $status === 'pending' ? 'btn-warning' : 'btn-outline-warning'; ?>">Pending</a>
          </div>
          <div class="text-muted">Total: <?php echo $totalRows; ?></div>
        </div>
        <div class="card-body p-0">
          <?php if (empty($reviews)): ?>
            <div class="p-4 text-center">No reviews found.</div>
          <?php else: ?>
            <div class="p-3">
              <?php foreach ($reviews as $rv): ?>
                <?php $uemail = $rv['email'] ?? '';
                $uname = $uemail ? strtoupper(explode('@', $uemail)[0]) : 'USER'; ?>
                <?php $rating = max(0, min(5, intval($rv['rating']))); ?>
                <div class="col-md-12">
                  <div class="card card-success">
                    <div class="card-header">
                      <h3 class="card-title">Review #<?php echo intval($rv['review_id']); ?></h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool">
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                          <i class="fas fa-expand"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>

                      </div>
                    </div>
                    <div class="card-body p-0">
                      <table class="table table-sm table-bordered mb-0">
                        <tbody>
                          <tr>
                            <th style="width:160px">Review</th>
                            <td>#<?php echo intval($rv['review_id']); ?></td>
                          </tr>
                          <tr>
                            <th>User</th>
                            <td>
                              <div style="font-weight:600;display:inline-block;margin-right:8px;">
                                <?php echo htmlspecialchars($uname); ?></div>
                              <span class="text-muted"><?php echo htmlspecialchars($uemail); ?></span>
                            </td>
                          </tr>
                          <tr>
                            <th>Product</th>
                            <td>
                              <div style="display:flex;align-items:center;gap:10px;">
                                <img src="../product-images/<?php echo htmlspecialchars($rv['p_image'] ?? ''); ?>" alt=""
                                  style="width:60px;height:60px;object-fit:cover;">
                                <div>
                                  <div style="font-weight:600;"><?php echo htmlspecialchars($rv['p_name'] ?? 'Product'); ?>
                                  </div>
                                  <div class="text-muted">Product ID: <?php echo intval($rv['product_id']); ?></div>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <th>Rating</th>
                            <td style="color:#ffb703;font-size:16px;">
                              <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="<?php echo $i <= $rating ? 'fa-solid fa-star' : 'fa-regular fa-star'; ?>"></i>
                              <?php endfor; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Review Text</th>
                            <td><?php echo $rv['review_text'] ? htmlspecialchars($rv['review_text']) : '—'; ?></td>
                          </tr>
                          <tr>
                            <th>Status</th>
                            <td>
                              <span class="badge <?php echo intval($rv['status']) === 1 ? 'badge-success' : 'badge-warning'; ?>">
                                <?php echo intval($rv['status']) === 1 ? 'Approved' : 'Pending'; ?>
                              </span>
                            </td>
                          </tr>
                          <tr>
                            <th>Created At</th>
                            <td><?php echo htmlspecialchars($rv['created_at']); ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
        <?php if ($totalPages > 1): ?>
          <div class="card-footer d-flex justify-content-between align-items-center">
            <div>Page <?php echo $page; ?> of <?php echo $totalPages; ?></div>
            <nav>
              <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                  <a class="page-link"
                    href="reviews.php?status=<?php echo urlencode($status); ?>&page=<?php echo max(1, $page - 1); ?>">Prev</a>
                </li>
                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                  <a class="page-link"
                    href="reviews.php?status=<?php echo urlencode($status); ?>&page=<?php echo min($totalPages, $page + 1); ?>">Next</a>
                </li>
              </ul>
            </nav>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</div>

<?php
include("inc/footer.php");
?>