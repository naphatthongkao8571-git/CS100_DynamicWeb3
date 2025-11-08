<?php
// Simple self-posting PHP page that renders the form and the result.
$resultHtml = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Read + trim inputs
  $name  = trim($_POST['name']  ?? '');
  $email = trim($_POST['email'] ?? '');
  $city  = trim($_POST['city']  ?? '');

  // (Optional) very simple server-side validation to mimic the JS intent
  if ($name !== '' && $email !== '' && $city !== '') {
    // Escape for safe HTML output
    $ename  = htmlspecialchars($name,  ENT_QUOTES, 'UTF-8');
    $eemail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $ecity  = htmlspecialchars($city,  ENT_QUOTES, 'UTF-8');

    // Build the result block (match JS example exactly)
    $newRecord = <<<HTML
      <div class="border p-3 bg-white rounded mb-2">
        <strong>Name:</strong> {$ename}<br>
        <strong>Email:</strong> {$eemail}<br>
        <strong>City:</strong> {$ecity}
      </div>
    HTML;

    // Append to previous data if submitted in same load
    $resultHtml = ($_POST['previous'] ?? '') . $newRecord;
  } else {
    // If any field is empty, keep previous and (optionally) show a bootstrap alert
    $resultHtml = $_POST['previous'] ?? '';
    $error = 'Please fill in all fields!';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Bootstrap Form Example (PHP)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-sm p-4">
    <h3 class="mb-4 text-primary">Registration Form</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-warning" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form id="myForm" method="POST" action="">
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input
          type="text"
          id="name"
          name="name"
          class="form-control"
          placeholder="Enter your name">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          placeholder="Enter your email">
      </div>

      <div class="mb-3">
        <label for="city" class="form-label">City</label>
        <input
          type="text"
          id="city"
          name="city"
          class="form-control"
          placeholder="Enter your city">
      </div>

      <!-- Hidden field to keep previously displayed data -->
      <input type="hidden" name="previous" value="<?= htmlspecialchars($resultHtml ?? '', ENT_QUOTES, 'UTF-8') ?>">

      <button type="submit" class="btn btn-primary w-100">Submit</button>
    </form>

    <div class="mt-4">
      <h5>Submitted Data:</h5>
      <!-- Match JS example: styled outer container -->
      <div id="output" class="border p-3 bg-white rounded">
        <?= $resultHtml ?>
      </div>
    </div>
  </div>
</div>

</body>
</html>
