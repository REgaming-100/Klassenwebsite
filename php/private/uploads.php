<?php

function getUploadData($id) {
  global $mainDir;
  return json_decode(file_get_contents($mainDir."/assets/uploads/$id/$id.json"), true);
}

function getContentFilename($id) {
  return getUploadData($id)["filenames"]["basename"];
}

function getAllUploadIds() {
  global $mainDir;

  return array_map(function ($x) {
    return basename($x);
  }, glob($mainDir."/assets/uploads/*", GLOB_ONLYDIR));
}

function shortenFileSize($bytes) {
  $fileSize = $bytes;
  $fileSizeUnit = "";
  foreach (["k", "M", "G", "T", "P", "E", "Z"] as $factor) {
    if ($fileSize / 1000 >= 1) {
      $fileSizeUnit = $factor;
      $fileSize /= 1000;
    }
    else {
      break;
    }
  }

  return ["size" => $fileSize, "multiplier" => $fileSizeUnit];
}

function iconName($mimeType, $fileExtension, $isCode) {
  switch ($mimeType) {
    case "application/msword":
    case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
      $iconName = "file-word";
      break;
    case "application/vnd.ms-excel":
    case "application/vnd.ms-excel":
      $iconName = "file-excel";
      break;
    case "application/vnd.ms-powerpoint":
    case "application/vnd.openxmlformats-officedocument.presentationml.presentation":
      $iconName = "file-powerpoint";
      break;
    case "application/pdf":
      $iconName = "file-pdf";
      break;
    case "application/x-archive":
    case "application/x-compressed":
    case "application/x-zip-compressed":
    case "application/zip":
    case "multipart/x-zip":
    case "application/x-7z-compressed":
    case "application/x-tar":
    case "application/gzip":
    case "multipart/x-gzip":
      $iconName = "file-zipper";
      break;
    case "text/x-java":
    case "text/x-java-source":
    case "application/java-archive":
    case "application/java":
    case "application/java-byte-code":
    case "application/x-java-class":
      $iconName = "mug-hot";
      break;
  }

  if (!isset($iconName)) {
    if (in_array($fileExtension, ["java", "class", "jar"])) {
      $iconName = "mug-hot";
    }
  }

  if (!isset($iconName) && explode("/", $mimeType)[0] == "text" && $isCode) {
    $iconName = "code";
  }

  if (!isset($iconName)) {
    $iconName = [
      "application" => "rectangle-vertical-history",
      "audio" => "volume-high",
      "font" => "font",
      "image" => "image",
      "model" => "cube",
      "text" => "file-lines",
      "video" => "film"
    ][explode("/", $mimeType)[0]];
  }

  return $iconName;
}

function reverseGeocode($latitude, $longitude) {
  $opts = [
    "http" => [
      "method" => "GET",
      "header" => "User-Agent: Mozilla/5.0"
    ]
  ];

  $geocodeXml = file_get_contents("https://nominatim.openstreetmap.org/reverse?lat=$latitude&lon=$longitude", false, stream_context_create($opts));
  $geocodeArray = (array) simplexml_load_string($geocodeXml);

  $locationName = "";

  if (isset($geocodeArray["error"])) {
    $locationName = $geocodeArray["error"];
  }
  else {
    $addressparts = (array) $geocodeArray["addressparts"];

    if (isset($addressparts["amenity"])) {
      $locationName = $addressparts["amenity"];
    }
    else {
      $firstSet = [false, false];
      $locationName .= returnIfSet($addressparts["road"], "", "", $firstSet[0]);
      $locationName .= returnIfSet($addressparts["house_number"], " ", "", $firstSet[1]);
      if ($firstSet[0] || $firstSet[1]) { $locationName .= ", "; }
      $locationName .= returnIfSet($addressparts["postcode"], "", " ");
      if (isset($addressparts["village"])) {
        $locationName .= $addressparts["village"];
      } else {
        $locationName .= returnIfSet($addressparts["city"]);
      }
    }
  }

  return $locationName;
}

function getPreview($id, $fixed = false) {
  global $mainDir;

  $mimeType = getUploadData($id)["meta"]["type"];

  $return = "";
  if (explode("/", $mimeType)[0] == "text") {
    $return .= '<div class="content content-text'.(getUploadData($id)["data"]["code"] ? " content-code" : "").'">';
    $return .= str_replace("\n", "<br>", file_get_contents($mainDir."/assets/uploads/$id/".getContentFilename($id)));
    $return .= "</div>";
  }
  else if (explode("/", $mimeType)[0] == "image") {
   $return .= '<img class="content content-img" src="/upload/'.$id.'">';
  }
  else if ($mimeType == "application/pdf") {
    if ($fixed) {
      $return .= '<img class="content content-img" src="/upload/'.$id.'" type="application/pdf">';
    }
    else {
      $return .= '<embed class="content content-pdf" src="/upload/'.$id.'" type="application/pdf">';
    }
  }
  else {
    $return .= '<p id="no-preview">'.$fileData["filenames"]["extension"].'-Datei<br>Keine Vorschau verfügbar.</p>';
  }

  return $return;
}

?>