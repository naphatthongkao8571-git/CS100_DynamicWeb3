<?php
// Simple self-posting PHP page that renders the form and the result.
$dataFile = 'data.json';
$resultHtml = '';

// Load existing data from JSON file
if (file_exists($dataFile)) {
  $records = json_decode(file_get_contents($dataFile), true) ?? [];
} else {
  $records = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name  = trim($_POST['name']  ?? '');
  $email = trim($_POST['email'] ?? '');
  $city  = trim($_POST['city']  ?? '');

  if ($name && $email && $city) {
    $records[] = ['name' => $name, 'email' => $email, 'city' => $city];
    file_put_contents($dataFile, json_encode($records, JSON_PRETTY_PRINT));
  } else {
    $error = 'Please fill in all fields!';
  }
}

// Rebuild HTML from saved data
foreach ($records as $r) {
  $ename  = htmlspecialchars($r['name'],  ENT_QUOTES, 'UTF-8');
  $eemail = htmlspecialchars($r['email'], ENT_QUOTES, 'UTF-8');
  $ecity  = htmlspecialchars($r['city'],  ENT_QUOTES, 'UTF-8');
  $resultHtml .= "
    <div class='border p-3 bg-white rounded mb-2'>
      <strong>Name:</strong> {$ename}<br>
      <strong>Email:</strong> {$eemail}<br>
      <strong>City:</strong> {$ecity}
    </div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bootstrap Form Example (PHP + JSON)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-sm p-4">
    <h3 class="mb-4 text-primary">Registration Form</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-warning" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email">
      </div>
      <div class="mb-3">
        <label for="city" class="form-label">City</label>
        <input type="text" id="city" name="city" class="form-control" placeholder="Enter your city">
      </div>
      <button type="submit" class="btn btn-primary w-100">Submit</button>
    </form>

    <div class="mt-4">
      <h5>Submitted Data:</h5>
      <div id="output" class="border p-3 bg-white rounded">
        <?= $resultHtml ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>
