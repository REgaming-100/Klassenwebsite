<?php

require $mainDir."/php/private/uploads.php";

$allIds = getAllUploadIds();

?>
<!DOCTYPE html>
<html>
<head>
  <title>Klassenwebsite &ndash; Alle Dateien</title>
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/upload-overview.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="/assets/js/general.js"></script>
  <script src="/assets/js/upload-overview.js"></script>
</head>
<body>
  <nav>
    <a href="/index.php"><img src="/assets/images/logo.jpg" alt="Logo" id="logo"></a>
    <a href="/selection.php">Artikel</a>
    <a href="/write.php">Schreib was!</a>
    <a href="/uploads">Dateien</a>
    <a id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
  </nav>
  <main>
    <div id="search">
      <div id="top">
        <input type="text">
        <a href="upload.php" id="upload-new">
          <i class="fa-solid fa-plus"></i>Hochladen
        </a>
      </div>
      <div id="filters">
        <div upload-filter="image">
          <i class="fa-solid fa-image"></i>Bilder
        </div>
        <div upload-filter="video">
          <i class="fa-solid fa-film"></i>Videos
        </div>
        <div upload-filter="audio">
          <i class="fa-solid fa-volume-high"></i>Audio
        </div>
        <div upload-filter="document">
          <i class="fa-solid fa-file"></i>Dokumente
        </div>
        <div upload-filter="text">
          <i class="fa-solid fa-file-lines"></i>Text
        </div>
        <div upload-filter="code">
          <i class="fa-solid fa-code"></i>Code
        </div>
        <div upload-filter="other">
          <i class="fa-solid fa-ellipsis"></i>Andere
        </div>
      </div>
    </div>
    <div id="results"><?php echo getUploadOverview(true); ?></div>
  </main>
</body>
</html>