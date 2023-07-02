<?php

if (isset($_SESSION["verified"]) && $_SESSION["verified"]) {
  redirect();
}

if (isset($_POST["key"])) {
  $key = trim($_POST["key"]);
  $passwordVerified = password_verify(
    base64_encode(
      hash("sha256", $key, true)
    ),
    $hashedkey
  );

  if ($passwordVerified) {
    $_SESSION["verified"] = true;
    $_SESSION["name"] = $_POST["name"];
    redirect();
  } else {
    $error = "Du hast das Passwort falsch eingegeben!";
  }
}

function redirect() {
  $nextpage = isset($_GET["continue"]) ? $_GET["continue"] : "/index.php";
  $attrIdx = 0;
  foreach ($_GET as $attr => $value) {
    if ($attr != "continue") {
      $nextpage .= ($attrIdx == 0 ? "?" : "&") . "$attr=$value";
      $attrIdx++;
    }
  }
  header("Location: $nextpage");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Unsere Klassenwebsite &ndash; Login</title>
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/login.css">
</head>
<body>
  <div class="login-container">
    <form action="login.php<?php
$attrIdx = 0;
foreach ($_GET as $attr => $value) {
  echo ($attrIdx == 0 ? "?" : "&") . "$attr=$value";
  $attrIdx++;
}
    ?>" method="post" autocomplete="off">
      <h1>Anmeldung</h1>
      <div class="input-field">
        <i class="fa-solid fa-key"></i>
        <input name="key" type="password" placeholder="Passwort">
      </div>
      <div class="input-field">
        <i class="fa-solid fa-signature"></i>
        <input name="name" type="text" placeholder="Name (optional)">
      </div>
      <button type="submit">Login</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
  </div>
</body>
</html>
