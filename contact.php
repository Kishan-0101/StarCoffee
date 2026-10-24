<?php
session_start();
require_once __DIR__ . '/db.php';

// Initialize state
$errors = [];
$success = '';

$prefill_full_name = '';
$prefill_email = '';
$prefill_phone = '';

// Prefill from session user when available
if (isset($_SESSION['user'])) {
  if (is_array($_SESSION['user'])) {
    $prefill_email = $_SESSION['user']['email'] ?? '';
  } else {
    $prefill_email = (string) $_SESSION['user'];
  }
}

// Handle POST with PRG (Post/Redirect/Get)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $full_name = trim($_POST['full_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');
  $preferred_contact = trim($_POST['preferred_contact'] ?? 'email');
  $is_consent = isset($_POST['is_consent']) ? 1 : 0;


  if ($full_name === '') {
    $errors[] = 'Full name is required.';
  }
  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required.';
  }
  if ($phone === '') {
    $errors[] = 'Phone number is required.';
  }
  if ($subject === '') {
    $errors[] = 'Subject is required.';
  }
  if ($message === '' || mb_strlen($message) < 10) {
    $errors[] = 'Message is required (min 10 characters).';
  }

  $allowed_pref = ['email', 'phone', 'none'];
  if (!in_array($preferred_contact, $allowed_pref, true)) {
    $preferred_contact = 'email';
  }

  $user_id = null;
  if (isset($_SESSION['user_id'])) {
    $user_id = (int) $_SESSION['user_id'];
    if ($prefill_email === '') {
      $prefill_email = $email;
    }
  }

  if (!$errors) {
    $stmt = $conn->prepare("INSERT INTO contacts (user_id, full_name, email, phone, subject, message, preferred_contact, is_consent) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param(
      'issssssi',
      $user_id,
      $full_name,
      $email,
      $phone,
      $subject,
      $message,
      $preferred_contact,
      $is_consent
    );
    if ($stmt->execute()) {
      // Flash success and redirect to clear POST & form state
      $_SESSION['contact_success'] = 'Thank you! Your message has been sent.';
      header('Location: contact.php');
      exit;
    } else {
      $errors[] = 'Something went wrong. Please try again later.';
    }
    $stmt->close();
  }

  // Flash errors and form data, then redirect
  if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_form'] = [
      'full_name' => $full_name,
      'email' => $email,
      'phone' => $phone,
      'subject' => $subject,
      'message' => $message,
      'preferred_contact' => $preferred_contact,
      'is_consent' => $is_consent,
    ];
    header('Location: contact.php');
    exit;
  }
}
// GET: load flash if present, then clear
if (!empty($_SESSION['contact_success'])) {
  $success = (string) $_SESSION['contact_success'];
  unset($_SESSION['contact_success']);
}
if (!empty($_SESSION['contact_errors']) && is_array($_SESSION['contact_errors'])) {
  $errors = $_SESSION['contact_errors'];
  unset($_SESSION['contact_errors']);
}
if (!empty($_SESSION['contact_form']) && is_array($_SESSION['contact_form'])) {
  $prefill_full_name = $_SESSION['contact_form']['full_name'] ?? $prefill_full_name;
  $prefill_email = $_SESSION['contact_form']['email'] ?? $prefill_email;
  $prefill_phone = $_SESSION['contact_form']['phone'] ?? $prefill_phone;
  $subject = $_SESSION['contact_form']['subject'] ?? ($subject ?? '');
  $message = $_SESSION['contact_form']['message'] ?? ($message ?? '');
  $preferred_contact = $_SESSION['contact_form']['preferred_contact'] ?? ($preferred_contact ?? 'email');
  $is_consent = $_SESSION['contact_form']['is_consent'] ?? 0;
  unset($_SESSION['contact_form']);
}

include 'inc/header.php';
?>

<main class="main">
  <section class="contact section" id="contact">
    <div class="contact__container container grid">
      <div class="contact__info grid">
        <div>
          <h2 class="section__title">CONTACT US</h2>
          <?php if (!empty($success)): ?>
            <div class="alert alert-success" style="color:var(--white-color); margin: 0 0 12px;">
              <?php echo htmlspecialchars($success); ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($errors)): ?>
            <div class="alert alert-error" style="color:var(--white-color); margin: 0 0 12px;">
              <?php foreach ($errors as $e): ?>
                <div><?php echo htmlspecialchars($e); ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (empty($success)): ?>
            <form action="contact.php" method="POST" class="contact__form" id="contactForm" novalidate>
              <div class="form-grid">
                <div class="form-field">
                  <label for="full_name">Full name</label>
                  <input id="full_name" type="text" name="full_name" class="footer__input" required autocomplete="name"
                    maxlength="100" value="<?php echo htmlspecialchars($prefill_full_name); ?>">
                  <small class="field-error" data-error-for="full_name"></small>
                </div>

                <div class="form-field">
                  <label for="email">Email</label>
                  <input id="email" type="email" name="email" class="footer__input" required autocomplete="email"
                    maxlength="255" value="<?php echo htmlspecialchars($prefill_email); ?>">
                  <small class="field-error" data-error-for="email"></small>
                </div>

                <div class="form-field">
                  <label for="phone">Phone</label>
                  <input id="phone" type="text" name="phone" class="footer__input" required autocomplete="tel"
                    inputmode="tel" pattern="^[0-9()+\-\s]{7,20}$" maxlength="20"
                    value="<?php echo htmlspecialchars($prefill_phone); ?>">
                  <small class="field-error" data-error-for="phone"></small>
                </div>

                <div class="form-field">
                  <label for="subject">Subject</label>
                  <input id="subject" type="text" name="subject" class="footer__input" required maxlength="150"
                    value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>">
                  <small class="field-error" data-error-for="subject"></small>
                </div>

                <div class="form-field form-field--full">
                  <label for="message">Your message</label>
                  <textarea id="message" name="message" class="footer__input" rows="6" required minlength="10"
                    maxlength="2000"><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                  <small class="field-error" data-error-for="message"></small>
                </div>

                <div class="form-field">
                  <label for="preferred_contact">Preferred contact</label>
                  <select id="preferred_contact" name="preferred_contact" class="footer__input">
                    <option value="email" <?php echo (isset($preferred_contact) && $preferred_contact === 'email') ? 'selected' : ''; ?>>Email</option>
                    <option value="phone" <?php echo (isset($preferred_contact) && $preferred_contact === 'phone') ? 'selected' : ''; ?>>Phone</option>
                    <option value="none" <?php echo (isset($preferred_contact) && $preferred_contact === 'none') ? 'selected' : ''; ?>>No Preference</option>
                  </select>
                </div>

                <div class="form-field form-field--checkbox">
                  <label class="checkbox">
                    <input type="checkbox" name="is_consent" value="1" <?php echo !empty($is_consent) ? 'checked' : ''; ?>>
                    <span>I agree to be contacted about my inquiry.</span>
                  </label>
                </div>

                <div class="form-actions form-field--full">
                  <button type="submit" class="button">Send Message</button>
                </div>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>

      <div class="contact__images">
        <div class="contact__shape"></div>
        <img src="assets/img/contact-delivery.png" alt="image" class="contact__delivery">
      </div>
    </div>
  </section>
</main>

<?php include 'inc/footer.php'; ?>