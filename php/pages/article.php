<?php

require $mainDir."/php/private/uploads.php";

$topic = isset($_GET["topic"]) ? $_GET["topic"] : null;

$allTopics = array_map(function ($x) { return basename($x); }, glob($mainDir."/articles/*", GLOB_ONLYDIR));

if (in_array($topic, $allTopics)) {
  $allVersions = array_map(function ($x) { return basename($x, ".json"); }, glob($mainDir."/articles/$topic/*.json"));

  if (isset($_GET["version"]) && in_array($_GET["version"], $allVersions)) {
    $version = $_GET["version"];
  }
  else {
    $newestMajor = 1;
    $newestMinor = 0;

    foreach ($allVersions as $version) {
      $splittedVersion = array_map(function($x) { return intval($x); }, preg_split("/-/", $version));

      if ($splittedVersion[0] > $newestMajor) {
        $newestMajor = $splittedVersion[0];
        $newestMinor = $splittedVersion[1];
      }
      else if ($splittedVersion[0] == $newestMajor  && $splittedVersion[1] > $newestMinor) {
        $newestMinor = $splittedVersion[1];
      }
    }

    $version = "$newestMajor-$newestMinor";
  }
}

$validArticleRequest = (in_array($topic, $allTopics) && in_array($version, $allVersions));

if ($validArticleRequest) {
  $json = file_get_contents("articles/$topic/$version.json");
  $articleData = json_decode($json, true);

  $monthNames = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];
  $timestamp = $articleData["infos"]["date"];
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Unsere Klassenwebsite<?php if ($validArticleRequest) echo " &ndash; ".$articleData["title"] ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/article.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="/assets/js/general.js"></script>
  <script src="/assets/js/article.js"></script>
</head>
<body>
  <nav>
    <a href="index.php"><img src="/assets/images/logo.png" alt="Logo" id="logo"></a>
    <a href="selection.php">Artikel</a>
    <a href="write.php">Schreib was!</a>
    <a id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
  </nav>
  <main>
<?php
if (in_array($topic, $allTopics)) {
  if (in_array($version, $allVersions)) {
?>
    <h1><?php echo $articleData["title"] ?></h1>
    <subtitle><?php echo $articleData["subtitle"] ?></subtitle>
    <div class="author-info">
      <p><?php
$timestampFormatted = date("j. ", $timestamp).$monthNames[date("n", $timestamp) - 1].date(" o \u\m H:i", $timestamp);

echo "Von ".$articleData["infos"]["author"].", ".$timestampFormatted;
      ?></p>
      <div>
        <div id="change-version">
          <div>Version</div>
          <select name="change-version" topic="<?php echo $topic ?>">
<?php
foreach($allVersions as $selectVersion) {
  echo "<option value=\"$selectVersion\"";
  if ($selectVersion == $version) {
    echo " selected";
  }
  $formattedVersion = str_replace("-", ".", $selectVersion);
  echo ">$formattedVersion</option>";
}
?>
          </select>
        </div>
        <a id="edit-article" topic="<?php echo $topic ?>">
          <i class="fa-solid fa-pen-to-square"></i>
        </a>
      </div>
    </div>
<?php
if (isset($articleData["properties"])) {
?>
    <div class="properties">
<?php
foreach($articleData["properties"] as $property) {
?>
      <div class="property">
        <i class="fa-solid fa-<?php echo $property["symbol"] ?>"></i>
        <h2><?php echo $property["title"] ?></h2>
        <p><?php echo $property["description"] ?></p>
      </div>
<?php
}
?>
    </div>
<?php
}
?>
    <article>
<?php

function iterateTags($elementArray) {
  foreach ($elementArray as $tag) {
    if (gettype($tag) == "string") {
      $string = str_replace("\n", "<br>", $tag);
      if ($string != "") {
        echo $string;
      }
    }
    else {
      $type = $tag["type"];
      $parameters = isset($tag["parameters"]) ? $tag["parameters"] : null;
      $content = $tag["content"];

      switch ($type) {
        case "a":
          echo '<a href="'.$parameters[0].'">';
          break;
        case "blockquote":
          echo '<blockquote><i id="block-icon" class="fa-solid fa-quote-left"></i>';
          break;
        case "personsays":
          echo '<personsays><img src="assets/images/profiles/'.$parameters[0].'.png"><div>';
          break;
        case "table":
          echo '<table class="'.implode(" ", $parameters).'">';
          break;
        case "file":
          echo getUploadArticleElement($tag);
          continue 2;
        default:
          echo "<$type>";
      }
      echo $type == "table" ? iterateTable($content) : iterateTags($content);
      switch ($type) {
        case "personsays":
          echo "</div>";
        default:
          echo "</$type>";
      }
    }
  }
}

function iterateTable($elementArray) {
  foreach ($elementArray as $row) {
    echo "<tr>";
    foreach ($row as $element) {
      echo "<td>";
      ($element) ? iterateTags($element) : "";
      echo "</td>";
    }
    echo "</tr>";
  }
}

echo iterateTags($articleData["body"]);

?>
      </article>
    </div>

<?php
}
else {
?>

<div class="content">
  <h1>Fehler</h1>
  <subtitle>Ungültige Version</subtitle>
  <article>
    <h2>Was ist los?</h2>
    <p>Die Version, die du mit <code>?version</code> angegeben hast, wurde nicht gefunden. Das heißt, niemand hat zu dieser Zeit den Artikel geändert.</p>
    <h2>Was tun?</h2>
    <p>Du kannst dir die <a href="?topic=<?php echo $_GET["topic"] ?>">neueste Version</a> des Artikels anschauen oder in den vorhandenen Versionen eine auswählen: <a>NOCH NICHT VERFÜGBAR</a></p>
  </article>
</div>

<?php
}
}
else {
if (!$topic) {
?>

<div class="content">
  <h1>Fehler</h1>
  <subtitle>Kein Thema</subtitle>
  <article>
    <h2>Was ist los?</h2>
    <p>Du hast die die Artikelseite geöffnet, ohne ein ein Thema anzugeben. Normalerweise geschieht das über das GET-Attribut <code>?topic</code>, aber das ist in diesem Fall nicht vorhanden oder leer.</p>
    <h2>Was tun?</h2>
    <p>Du kannst dir einen Artikel aus der Liste der Vorhandenen aussuchen. Wenn du eine Idee für ein Thema hast, schreib gerne darüber. Je mehr Themen wir haben, desto besser!</p>
    <div class="links">
      <a href="selection.php">Artikelauswahl<i class="fa-solid fa-angle-right"></i></a>
      <a href="write.php">Schreib was<i class="fa-solid fa-angle-right"></i></a>
    </div>
  </article>
</div>

<?php
}
elseif (preg_match("/^[a-z0-9\-]*$/", $topic) == false) {
?>

<div class="content">
  <h1>Fehler</h1>
  <subtitle>Ungültiges Zeichen</subtitle>
  <article>
    <h2>Was ist los?</h2>
    <p>Das Thema, dass du mit <code>?topic</code> angegeben hast, enthält ein oder mehrere ungültige Zeichen.</p>
    <h2>Was tun?</h2>
    <p>Du kannst dir einen Artikel aus der Liste der Vorhandenen aussuchen. Wenn du eine Idee für ein Thema hast, schreib gerne darüber. Je mehr Themen wir haben, desto besser!</p>
    <div class="links">
      <a href="selection.php">Artikelauswahl<i class="fa-solid fa-angle-right"></i></a>
      <a href="write.php">Schreib was<i class="fa-solid fa-angle-right"></i></a>
    </div>
  </article>
</div>

<?php
}
else {
?>

<div class="content">
  <h1>Fehler</h1>
  <subtitle>Ungültiges Thema</subtitle>
  <article>
    <h2>Was ist los?</h2>
    <p>Das Thema, das du mit <code>?topic</code> angegeben hast, wurde nicht gefunden.</p>
    <h2>Was tun?</h2>
    <p>Du kannst gerne einen Text über "<?php echo $topic ?>" schreiben <a id="edit-article-select" topic="<?php echo $topic ?>"><i class="fa-solid fa-pen-to-square"></i></a> oder einen Artikel aus der Liste der Vorhandenen Artikel <a href="selection.php">aussuchen</a>.</p>
  </article>
</div>

<?php
}
}
?>

  </main>
</body>
</html>