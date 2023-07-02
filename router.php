<?php

session_start();

$mainDir = __DIR__;
$requestURI = $_SERVER["REQUEST_URI"];
chdir($mainDir);

date_default_timezone_set("Europe/Berlin");
$dt = new DateTime("now");
$dt->setTimestamp(time());

file_put_contents(
  $mainDir."/requests.log",
  $dt->format("[D, d.m.o \\a\\t H:i:s T]") . "\n  from " . $_SERVER["REMOTE_ADDR"] . " / forwarded for " . $_SERVER["HTTP_X_FORWARDED_FOR"] . "\n  " . $_SERVER["REQUEST_METHOD"] . " " . $requestURI . "\n  ",
  FILE_APPEND
);

$hashedkey = "\$2y\$10\$/sPKF/4FWvIQS5TqBGqPi.AMGrjeOJEbUlxe3LiaMSjSwwMrIEZqC";

// Login handling
if (!(
  // Allow everything if logged in
  (isset($_SESSION["verified"]) && $_SESSION["verified"]) ||
  // For API usage (key is sent through $_GET)
  // To get an encoded key, SHA256 hash it and then base64 encode that
  (isset($_GET["key"]) && password_verify($_GET["key"],$hashedkey)) ||
  str_starts_with($requestURI, "/api/encode-key.php") ||
  // Everything that can be accessed without being logged in
  str_starts_with($requestURI, "/assets/fontawesome") ||
  str_starts_with($requestURI, "/login.php") ||
  $requestURI == "/assets/css/login.css" ||
  $requestURI == "/assets/images/login-background.png" ||
  $requestURI == "/assets/images/favicon.ico" ||
  $requestURI == "/logout.php"
)) {
  // Redirect to login page when logged out and file only for logged in
  file_put_contents($mainDir."/requests.log", "Not logged in\n\n", FILE_APPEND);

  $attributes = "?continue=".$_SERVER["PHP_SELF"];
  foreach ($_GET as $attr => $value) {
    if ($attr != "continue") {
      $attributes .= "&$attr=$value";
    }
  }

  header("Location: /login.php$attributes");
  exit();
}

$filePath = $mainDir.$requestURI;
$attributeRegEx = "/\?[^#\s]+=[^#\s]*(\&[^#\s]+=[^#\s]*)*|#[^?&\s]*/";
$filePath = preg_replace($attributeRegEx, "" , $filePath);
$filePath = rtrim($filePath, "/");
if (dirname($filePath) == $mainDir."/api") {
  $filePath = $mainDir."/php/api/".basename($filePath);
}
if (strtolower(substr($filePath, -4)) == '.php' && dirname($filePath) == $mainDir) {
  $filePath = $mainDir."/php/pages/".basename($filePath);
}
if (preg_match("/^\/upload\/[0-9a-f]+\/?$/", str_replace($mainDir, "", $filePath))) {
  require $mainDir."/php/private/uploads.php";
  $explodedFilePath = explode("/", $filePath);
  $uploadId = $explodedFilePath[count($explodedFilePath) - 1];
  $path = $mainDir."/assets/uploads/$uploadId/".getContentFilename($uploadId);
  header("Content-Type: ".mime_content_type($path));
  echo file_get_contents($path);
  httpResonse(200);
  exit();
}
if (preg_match("/^\/upload\/[0-9a-f]+\/view\/?$/", str_replace($mainDir, "", $filePath))) {
  $explodedFilePath = explode("/", $filePath);
  $uploadId = $explodedFilePath[count($explodedFilePath) - 2];
  require $mainDir."/php/private/upload-viewer.php";
  httpResonse(200);
  exit();
}

if (rtrim($filePath, '/') == $mainDir) {
  // requesting "" or "/"; serve index.php
  require $mainDir."/php/pages/index.php";
  httpResonse(200);
  exit();
}

if (
  // specifically diallowed files or folders
  $filePath == $mainDir."/README.md" ||
  $filePath == $mainDir."/requests.log" ||
  str_starts_with($filePath, $mainDir."/articles") ||
  str_starts_with($filePath, $mainDir."/php/private") ||
  str_starts_with($filePath, $mainDir."/assets/uploads")
) {
  httpResonse(403);
}

if ($filePath && is_file($filePath)) {
  if (
    // check that file is not outside of this directory for security
    strpos($filePath, $mainDir."/") === 0 &&
    // check for circular reference to router.php
    $filePath != $mainDir."/router.php" &&
    // don't serve dotfiles
    substr(basename($filePath), 0, 1) != '.'
  ) {
    httpResonse(200);
    if (strtolower(substr($filePath, -4)) == '.php') {
      // php file
      require $filePath;
    }
    else {
      // other file
      return false;
    }
  } else {
    // disallowed file
    httpResonse(403);
  }
}
else {
  httpResonse(404);
}

function httpResonse($code) {
  global $mainDir;

  switch ($code) {
    case 200:
      file_put_contents($mainDir."/requests.log", "200 OK\n\n", FILE_APPEND);
      break;
    case 403:
      file_put_contents($mainDir."/requests.log", "403 Forbidden\n\n", FILE_APPEND);
      header("HTTP/1.1 403 Forbidden");
      echo "403 Forbidden";
      exit();
      break;
    case 404:
      file_put_contents($mainDir."/requests.log", "404 Not Found\n\n", FILE_APPEND);
      header("HTTP/1.1 404 Not Found");
      echo "404 Not Found";
      exit();
      break;
  }
}

function returnIfSet(&$var, $pre = "", $post = "", &$isset = null) {
  if (isset($isset)) { $isset = isset($var); }
  return isset($var) ? $pre.$var.$post : null;
}

?>