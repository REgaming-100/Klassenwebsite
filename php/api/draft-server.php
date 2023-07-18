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
$txtFilePath = $mainDir."/articles/DRAFTS/".$articleId.".txt";
$metaFilePath = $mainDir."/articles/DRAFTS/.".$articleId;
$newDraft = !(is_file($metaFilePath));
$newTopic = !(is_dir($mainDir."/articles/".$articleId));

if ($_REQUEST["request-type"] == "content-sync") {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    writeToDraftFiles(
      $_REQUEST["title"],
      $_REQUEST["subtitle"],
      $_REQUEST["description"],
      $_REQUEST["content"]
    );
  }
  else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $body = "";

    if ($newDraft) {
      if ($newTopic) {
        $title = "Titel";
        $subtitle = "Untertitel";
        $description = "Hier kommt eine kurze Beschreibung hin, die bei der Suche gezeigt wird.";
        $body = "Schreibe hier über das Thema. Über die Möglichkeiten zum Formatieren kannst du dich beim Buch in der Toolbar informieren.";
      }
      else {
        $json = file_get_contents(max(glob($mainDir."/articles/$articleId/*.json")));
        $articleAsArray = json_decode($json, true);

        $title = $articleAsArray["title"];
        $subtitle = $articleAsArray["subtitle"];
        $description = $articleAsArray["description"];
        $body = rtrim(iterateTags($articleAsArray["body"]), "\n");
      }
    }
    else {
      $file = fopen($metaFilePath, 'c+');
      $title = rtrim(fgets($file), "\n");
      $subtitle = rtrim(fgets($file), "\n");
      $description = rtrim(fgets($file), "\n");
      fclose($file);

      $body = "";
      $file = fopen($txtFilePath, 'c+');
      while(!feof($file)) {
        $body .= fgets($file);
      }
      fclose($file);
    }

    $responseArray = [
      "new" => $newDraft,
      "title" => $title,
      "subtitle" => $subtitle,
      "description" => $description,
      "body" => $body
    ];

    echo json_encode($responseArray);
  }
  else {
    echo "Error: Request method not allowed";
  }
}
else if ($_REQUEST["request-type"] == "delete-draft") {
  if (is_file($mainDir."/articles/DRAFTS/.$articleId")) {
    $file = fopen($mainDir."/articles/DRAFTS/.$articleId", "r");
    fgets($file); fgets($file); fgets($file);

    $lastEditTimestamp = fgets($file);

    if (time() - $lastEditTimestamp >= 259200) {
      unlink($mainDir."/articles/DRAFTS/.$articleId");
      unlink($mainDir."/articles/DRAFTS/$articleId.txt");
    }
    else {
      echo "Error: Draft's last edit must be at least 3 days ago";
    }
  }
  else {
    echo "Error: Draft doesn't exist";
  }
}
else {
  echo "Error: Invalid request type";
}

function writeToDraftFiles($title, $subtitle, $description, $body) {
  global $txtFilePath, $metaFilePath, $dt;

  $file = fopen($metaFilePath, 'w');
  fwrite($file, $title."\n");
  fwrite($file, $subtitle."\n");
  fwrite($file, $description."\n");
  fwrite($file, $dt->format("U"));
  fclose($file);

  $file = fopen($txtFilePath, 'w');
  fwrite($file, $body);
  fclose($file);
}

function iterateTags($elementArray) {
  $body = "";

  foreach ($elementArray as $tag) {
    if (gettype($tag) == "string") {
      $escapedDangers = $tag;
      foreach (["&", "*", "_", "~", "^", "ˋ"] as $danger) {
        $escapedDangers = str_replace($danger, "\\".$danger, $escapedDangers);
      }
      $body .= $escapedDangers;
    }
    else {
      $type = $tag["type"];
      $parameters = isset($tag["parameters"]) ? $tag["parameters"] : null;
      $content = $tag["content"];

      switch ($type) {
        case "h2":
          $body .= "&# ".iterateTags($content)."\n\n";
          break;
        case "h3":
          $body .= "&## ".iterateTags($content)."\n\n";
          break;
        case "h4":
          $body .= "&### ".iterateTags($content)."\n\n";
          break;
        case "h5":
          $body .= "&#### ".iterateTags($content)."\n\n";
          break;
        case "p":
          $body .= iterateTags($content)."\n\n";
          break;
        case "a":
          $body .= "[".$content[0]." | ".$parameters[0]."]";
          break;
        case "b":
          $body .= "^".iterateTags($content)."^";
          break;
        case "i":
          $body .= "*".iterateTags($content)."*";
          break;
        case "u":
          $body .= "_".iterateTags($content)."_";
          break;
        case "s":
          $body .= "~".iterateTags($content)."~";
          break;
        case "code":
          $body .= "`".iterateTags($content)."`";
          break;
        case "blockquote":
          $body .= "&quote\n";
          $body .= trim(iterateTags($content))."\n";
          $body .= "&quote\n\n";
          break;
        case "codeblock":
          $body .= "&code\n";
          $body .= trim(iterateTags($content))."\n";
          $body .= "&code\n\n";
          break;
        case "personsays":
          $body .= "&say ".$parameters[0]."\n";
          $body .= trim(iterateTags($content))."\n";
          $body .= "&say\n\n";
          break;
        case "table":
          $body .= "&table ".implode(" ", $parameters)."\n";
          $body .= iterateTable($content)."\n";
          $body .= "&table\n\n";
          break;
        case "file":
          if (preg_match("/&file [0-9a-f]+\n\n$/", $body)) {
            $body = substr($body, 0, -1);
          }
          $body .= "&file ".$content[0]."\n\n";
          break;
      }
    }
  }
  return $body;
}

function iterateTable($table) {
  $body = "";

  foreach ($table as $row) {
    foreach ($row as $element) {
      $body .= iterateTags($element);
      $body .= " | ";
    }
    $body = rtrim($body, " | ")."\n";
  }

  return rtrim($body, "\n");
}

?>