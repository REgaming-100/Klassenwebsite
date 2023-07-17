<?php

// Start session (for login)
session_start();

// Setup Paths
$mainDir = __DIR__;
$requestURI = strtok($_SERVER["REQUEST_URI"], "?");
$filePath = $mainDir.$requestURI;

// Load all required functions
require $mainDir."/php/private/file-mime.php";

// Setup time
date_default_timezone_set("Europe/Berlin");
$dt = new DateTime("now");
$dt->setTimestamp(time());

// Write to log (only first part)
$logText = $dt->format("[D, d.m.o \\a\\t H:i:s T]") . "\n  from " . $_SERVER["REMOTE_ADDR"] . " / forwarded for " . $_SERVER["HTTP_X_FORWARDED_FOR"] . "\n  " . $_SERVER["REQUEST_METHOD"] . " " . $requestURI . "\n  ";

file_put_contents($mainDir."/requests.log", $logText, FILE_APPEND);

// Login handling
if (!(
  // Allow everything if logged in
  (isset($_SESSION["verified"]) && $_SESSION["verified"]) ||
  // Everything that can be accessed without being logged in
  str_starts_with($requestURI, "/assets/fontawesome") ||
  str_starts_with($requestURI, "/login.php") ||
  $requestURI == "/assets/css/login.css" ||
  $requestURI == "/assets/images/login-background.png" ||
  $requestURI == "/assets/images/favicon.ico" ||
  $requestURI == "/logout.php"
)) {
  // Log the status
  file_put_contents($mainDir."/requests.log", "Not logged in\n\n", FILE_APPEND);

  // Re-add all GET attributes
  $attributes = "?continue=".$_SERVER["PHP_SELF"];
  foreach ($_GET as $attr => $value) {
    if ($attr != "continue") {
      $attributes .= "&$attr=$value";
    }
  }

  // Redirect to login page
  header("Location: /login.php$attributes");
  exit();
}

if (
  // Specifically diallowed files or URIs
  $filePath == $mainDir."/README.md" ||
  $filePath == $mainDir."/requests.log" ||
  str_starts_with($filePath, $mainDir."/articles") ||
  str_starts_with($filePath, $mainDir."/php/private") ||
  str_starts_with($requestURI, "/assets/uploads")
) {
  httpResponse(403);
}

if (str_starts_with($requestURI, "/api")) {
  $filePath = $mainDir."/php".$requestURI;
}
if (preg_match("/^\/[A-Za-z0-9 ( )_\-,.]+\.php$/", $requestURI)) {
  $filePath = $mainDir."/php/pages".$requestURI;
}
if (preg_match("/^\/upload\/[0-9a-f]+\/?$/", $requestURI)) {
  require $mainDir."/php/private/uploads.php";

  $uploadId;
  preg_match("/(?<=^\/upload\/)[0-9a-f]+(?=\/?$)/", $requestURI, $uploadId);
  $uploadId = $uploadId[0];
  $filePath = $mainDir."/assets/uploads/$uploadId/".getContentFilename($uploadId);

  $cache = true;
  header("Content-Disposition: attachment; filename=".getContentFilename($uploadId));
}
if (preg_match("/^\/upload\/[0-9a-f]+\/view\/?$/", $requestURI)) {
  $explodedFilePath = explode("/", $filePath);
  $uploadId = $explodedFilePath[count($explodedFilePath) - 2];
  httpResponse(200);
  require $mainDir."/php/private/upload-viewer.php";
  exit();
}
if (preg_match("/^\/uploads\/?$/", $requestURI)) {
  httpResponse(200);
  require $mainDir."/php/private/upload-overview.php";
  exit();
}

if (rtrim($filePath, "/") == $mainDir) {
  // requesting "" or "/"; serve index.php
  httpResponse(200);
  require $mainDir."/php/pages/index.php";
  exit();
}

if ($filePath && is_file($filePath)) {
  if (
    // check that file is not outside of this directory for security
    strpos($filePath, $mainDir."/") === 0 &&
    // check for circular reference to router.php
    $filePath != $mainDir."/router.php" &&
    // don"t serve dotfiles
    substr(basename($filePath), 0, 1) != "."
  ) {
    httpResponse(200);

    if (strtolower(substr($filePath, -4)) == ".php") {
      require $filePath;
    }
    else {
      if (isset($cache) || preg_match("/\.(?:jpg|jpeg|png|gif|tiff|bmp|svg|mp4|avi|mov|wmv|flv|mkv|tff|otf|woff|woff2|eot|pfb)$/", $filePath)) {
        $lastModified = filemtime($filePath);
        $etagFile = md5_file($filePath);
        $ifModifiedSince = (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) ? $_SERVER["HTTP_IF_MODIFIED_SINCE"] : false);
        $etagHeader = (isset($_SERVER["HTTP_IF_NONE_MATCH"]) ? trim($_SERVER["HTTP_IF_NONE_MATCH"]) : false);

        header("Last-Modified: ". gmdate("D, d M Y H:i:s", $lastModified) ." GMT");
        header("Etag: ". $etagFile);
        header("Cache-Control: public, max-age=2678400");
        header_remove("Pragma");
        header_remove("Expires");

        if (@strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) == $lastModified || $etagHeader == $etagFile) {
          // File has not changed
          httpResponse(304);
          exit();
        }
      }

      serveFile($filePath);
    }
  }
  else {
    httpResponse(403);
  }
}
else {
  httpResponse(404);
}

function httpResponse($code) {
  global $mainDir;

  switch ($code) {
    case 200:
      file_put_contents($mainDir."/requests.log", "200 OK\n\n", FILE_APPEND);
      header("HTTP/1.1 200 OK");
      break;
    case 304:
      file_put_contents($mainDir."/requests.log", "304 Not Modified\n\n", FILE_APPEND);
      header("HTTP/1.1 304 Not Modified");
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

function serveFile($path) {
  header("Content-Type: ". mime_type($path));
  header("Content-Length: ". filesize($path));
  @readfile($path);
  httpResponse(200);
  $finfo = null;
  exit();
}

function returnIfSet(&$var, $pre = "", $post = "", &$isset = null) {
  if (isset($isset)) { $isset = isset($var); }
  return isset($var) ? $pre.$var.$post : null;
}

?>