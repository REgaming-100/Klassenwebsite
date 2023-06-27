<?php

// Check if request type is set
if (!isset($_REQUEST["request-type"])) {
  echo "Error: Request type not set";
  exit();
}


// For all request types from now, an article id is required
if (isset($_REQUEST["article-id"])) {
  if (!preg_match("/^[a-z0-9\-]*$/", basename($_REQUEST["article-id"]))) {
    echo "Error: Article id uses illegal characters";
    exit();
  }
}
else {
  echo "Error: No article id provided";
  exit();
}


$articleId = basename($_REQUEST["article-id"]);

if ($_REQUEST["request-type"] == "next-version") {
  echo str_replace("-", ".", getNextVersion());
}
else if ($_REQUEST["request-type"] == "publish") {
  require $mainDir."/php/private/interpreter.php";

  $nextVersion = getNextVersion();

  $txtFilePath = $mainDir."/articles/DRAFTS/$articleId.txt";
  $metaFilePath = $mainDir."/articles/DRAFTS/.$articleId";
  $jsonFilePath = $mainDir."/articles/$articleId/$nextVersion.json";
  $keywordFilePath = $mainDir."/articles/keywords.json";
  if (!is_file($txtFilePath)) { echo "Error: Text file of draft not found"; exit(); }
  if (!is_file($metaFilePath)) { echo "Error: Meta file of draft not found"; exit(); }

  $file = fopen($metaFilePath, 'c+');
  $title = rtrim(fgets($file));
  $subtitle = rtrim(fgets($file));
  $description = rtrim(fgets($file));
  fclose($file);

  $content = file_get_contents($txtFilePath);

  $body = interpret($content)["content"];
  $bodyArray = [
    "title" => $title,
    "subtitle" => $subtitle,
    "description" => $description,
    "infos" => [
      "author" => isset($_SESSION["name"]) ? $_SESSION["name"] : "Unknown",
      "date" => $dt->format("U"),
      "version" => str_replace("-", ".", $nextVersion)
    ],
    "body" => $body
  ];

  if (!is_dir(dirname($jsonFilePath))) {
    mkdir(dirname($jsonFilePath));
  }

  $json = str_replace("    ", "  ", json_encode($bodyArray, JSON_PRETTY_PRINT));
  $jsonFile = fopen($jsonFilePath, "c+");
  fwrite($jsonFile, $json);
  fclose($jsonFile);

  $json = file_get_contents($keywordFilePath);
  $keywordArray = json_decode($json, true);
  $keywordArray[$articleId] = [
    strtolower($articleId),
    strtolower($title),
    strtolower($subtitle),
    strtolower($description)
  ];
  $json = str_replace("    ", "  ", json_encode($keywordArray, JSON_PRETTY_PRINT));

  $keywordFile = fopen($keywordFilePath, "w");
  fwrite($keywordFile, $json);
  fclose($keywordFile);

  unlink($txtFilePath);
  unlink($metaFilePath);
}
else {
  echo "Error: Invalid request type";
}

function getNextVersion() {
  global $mainDir;
  global $articleId;

  if (empty(glob($mainDir."/articles/$articleId/*.json"))) {
    return "1-0";
  }

  $change = in_array($_REQUEST["change"], ["major", "minor"]) ? $_REQUEST["change"] : "minor";

  $newestMajor = 0;
  $newestMinor = 0;

  foreach (glob($mainDir."/articles/$articleId/*") as $globbed) {
    $version = substr(basename($globbed), 0, -4);
    $splittedVersion = array_map(function($x) { return intval($x); }, explode("-", $version));
    if ($splittedVersion[0] > $newestMajor) {
      $newestMajor = $splittedVersion[0];
      $newestMinor = $splittedVersion[1];
    }
    else if ($newestMajor == $splittedVersion[0] && $splittedVersion[1] > $newestMinor) {
      $newestMinor = $splittedVersion[1];
    }
  }

  if ($change == "major") {
    return $newestMajor + 1 . "-" . 0;
  }
  else {
    return $newestMajor . "-" . $newestMinor + 1;
  }
}

?>