<?php

// Check if request type is set
if (!isset($_REQUEST["request-type"])) {
  echo "Error: Request type not set";
  exit();
}

if ($_REQUEST["request-type"] == "search") {
  if (!isset($_REQUEST["search-term"])) {
    echo "Error: No search term provided";
    exit();
  }

  $json = file_get_contents($mainDir."/articles/keywords.json");
  $keywords = json_decode($json, true);
  $matchingArticles = [];
  $jsons = [];

  foreach (glob($mainDir."/articles/*", GLOB_ONLYDIR) as $articleId) {
    if (basename($articleId) != "DRAFTS") {
      foreach ($keywords[basename($articleId)] as $keywordPart) {
        if (str_contains($keywordPart, strtolower($_REQUEST["search-term"]))) {
          array_push($matchingArticles, basename($articleId));
          break;
        }
      }
    }
  }

  foreach ($matchingArticles as $article) {
    $newestVersion = max(glob($mainDir."/articles/$article/*.json"));
    $json = file_get_contents($newestVersion);
    $articleContent = json_decode($json, true);
    $allImages = searchArray($articleContent, "type", "img");
    $coverImage = isset($allImages[0]["content"]) ? $allImages[0]["content"] : "";
    array_push($jsons, [
      "id" => $article,
      "title" => $articleContent["title"],
      "subtitle" => $articleContent["subtitle"],
      "description" => $articleContent["description"],
      "infos" => $articleContent["infos"],
      "image" => $coverImage
    ]);
  }

  echo json_encode($jsons);

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


if ($_REQUEST["request-type"] == "article-exists") {
  if (is_dir($mainDir."/articles/".basename($_REQUEST["article-id"]))) {
    echo true;
  }
  else {
    echo false;
  }
}
else {
  echo "Error: Invalid request type";
}

function searchArray($array, $key, $value) {
  $results = array();
  search_r($array, $key, $value, $results);
  return $results;
}

function search_r($array, $key, $value, &$results) {
  if (!is_array($array)) {
    return;
  }

  if (isset($array[$key]) && $array[$key] == $value) {
    $results[] = $array;
  }

  foreach ($array as $subarray) {
    search_r($subarray, $key, $value, $results);
  }
}

?>