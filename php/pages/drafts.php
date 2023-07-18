<!DOCTYPE html>
<html>
<head>
  <title>Klassenwebsite &ndash; Entwürfe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/drafts.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="/assets/js/general.js"></script>
  <script src="/assets/js/drafts.js"></script>
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
    <a id="back-to-write" href="write.php"><i class="fa-solid fa-angle-left"></i>Zurück zur Startseite</a>
    <h1>Entwürfe</h1>
    <subtitle>Hilf bei der Fertigstellung!</subtitle>
    <p class="narrow-p">Hier findest du eine Liste aller Entwürfe, die noch nicht veröffentlicht wurden. Fühl dich frei, sie zu bearbeiten und zu veröffentlichen.</p>
    <div id="results">
<?php

$drafts = [];
$monthNames = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];

foreach (glob($mainDir."/articles/DRAFTS/.*") as $draft) {
  $draft = basename($draft);

  if ($draft != "." && $draft != ".." && substr($draft, 0, 1) == ".") {
    $file = fopen($mainDir."/articles/DRAFTS/".$draft, "r");

    $title = substr(fgets($file), 0, -1);
    $subtitle = substr(fgets($file), 0, -1);
    $id = substr($draft, 1);
    fgets($file);
    $lastEditTimestamp = fgets($file);

    $lastEditFormatted = date("j. ", $lastEditTimestamp).$monthNames[date("n", $lastEditTimestamp) - 1].date(" o, H:i", $lastEditTimestamp);
    $deleteButtonClass = (time() - $lastEditTimestamp >= 259200) ? "" : " disabled";

    $drafts[] = "
    <div class=\"result\" article-id=\"$id\">
      <div id=\"infos\">
        <p id=\"article-id\"><code>$id</code></p>
        <div id=\"title-subtitle\">
          <h1>$title</h1>
          <subtitle>$subtitle</subtitle>
        </div>
        <div id=\"last-edit\">Zul. bearbeitet am $lastEditFormatted</div>
      </div>
      <div id=\"buttons\">
        <a class=\"edit-draft\"><i class=\"fa-solid fa-pen-to-square\"></i></a>
        <a class=\"delete-draft$deleteButtonClass\"><i class=\"fa-solid fa-trash-can\"></i></a>
      </div>
    </div>
  ";

    fclose($file);
  }
}

if (empty($drafts)) {
  echo '<p id="error">Es gibt zurzeit keine aktiven Entwürfe.</p>';
}
else {
  foreach ($drafts as $draft) {
    echo $draft;
  }
}

?>
    </div>
  </main>
</body>
</html>