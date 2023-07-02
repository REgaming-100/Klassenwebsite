<?php

require $mainDir."/php/private/uploads.php";

$fileData = getUploadData($uploadId);

$fileSizeArray = shortenFileSize($fileData["meta"]["size"]);
$fileSize = $fileSizeArray["size"];
$fileSizeUnit = $fileSizeArray["multiplier"];

$mimeType = $fileData["meta"]["type"];

$iconName = iconName($mimeType, $fileData["filenames"]["extension"], returnIfSet($fileData["data"]["code"]));

?>
<!DOCTYPE html>
<html>
<head>
  <title>Unsere Klassenwebsite &mdash; <?php echo $fileData["display"]["title"] ?></title>
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/upload-viewer.css">
</head>
<body>
  <nav>
    <a href="index.php"><img src="/assets/images/logo.png" alt="Logo" id="logo"></a>
    <a href="selection.php">Artikel</a>
    <a href="write.php">Schreib was!</a>
    <a id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
  </nav>
  <main>
    <div id="title">
      <h1><?php echo $fileData["display"]["title"] ?></h1>
      <span id="type">
        <i class="fa-solid fa-<?php echo $iconName ?>"></i>
        <?php echo $fileData["filenames"]["extension"] ?>
      </span>
    </div>
    <div id="content">
<?php echo getPreview($uploadId) ?>
    </div>
    <div id="sidebar">
      <a id="download" href="/upload/<?php echo $uploadId ?>" download>
        <i class="fa-solid fa-download"></i>
        <div>Download</div>
      </a>
      <div class="category-label">Datei</div>
      <div class="category">
        <div class="simple-info">
          <i class="fa-solid fa-cloud-arrow-up"></i>
          <span><?php echo date("d.m.Y H:i", $fileData["meta"]["upload"]) ?></span>
        </div>
        <div class="simple-info">
          <i class="fa-solid fa-user"></i>
          <span><?php echo $fileData["meta"]["author"] ?></span>
        </div>
        <div class="simple-info">
          <i class="fa-solid fa-hard-drive"></i>
          <span><?php echo round($fileSize, 1) . " " . $fileSizeUnit . "B" ?></span>
        </div>
      </div>
<?php if (str_starts_with($fileData["meta"]["type"], "image")) { ?>
      <div class="category-label">Bild</div>
      <div class="category">
        <div class="simple-info">
          <i class="fa-solid fa-vector-square"></i>
          <span><?php echo $fileData["data"]["width"]." x ".$fileData["data"]["height"]." Pixel" ?></span>
        </div>
<?php if (isset($fileData["data"]["taken"])) { ?>
        <div class="simple-info">
          <i class="fa-solid fa-clock"></i>
          <span><?php echo date("d.m.Y H:i", $fileData["data"]["taken"]) ?></span>
        </div>
<?php
}
if (isset($fileData["data"]["location"])) {
  $lat = $fileData["data"]["location"]["latitude"];
  $lon = $fileData["data"]["location"]["longitude"];

  $locationName = reverseGeocode($lat, $lon);
?>
        <div class="simple-info">
          <i class="fa-solid fa-location-dot"></i>
          <span><?php print_r($locationName) ?></span>
        </div>
<?php } ?>
      </div>
<?php
}
if (isset($fileData["display"]["description"])) {
?>
      <div class="category-label">Beschreibung</div>
      <div class="category">
        <div class="text-info"><?php echo $fileData["display"]["description"] ?></div>
      </div>
<?php
}
?>
    </div>
  </main>
</body>
</html>