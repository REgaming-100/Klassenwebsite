<?php

// Check if request type is set
if (!isset($_REQUEST["request-type"])) {
  echo "Error: Request type not set";
  exit();
}

if ($_REQUEST["request-type"] == "overview") {
  require $mainDir."/php/private/uploads.php";
  echo getUploadOverview();
}
else if ($_REQUEST["request-type"] == "upload") {
  if (empty($_REQUEST["title"])) {
    echo "Error: No title provided";
    exit();
  }
  
  $allUploadIds = array_map(function ($x) {
    return basename($x);
  }, glob($mainDir."/assets/uploads/*", GLOB_ONLYDIR));
  
  $currentId = bin2hex(random_bytes(8));
  while (in_array($currentId, $allUploadIds)) {
    $currentId = bin2hex(random_bytes(8));
  }
  
  mkdir($mainDir."/assets/uploads/$currentId");
  
  $jsonPath = $mainDir."/assets/uploads/$currentId/$currentId.json";
  $filePath = $mainDir."/assets/uploads/$currentId/".basename($_FILES["file"]["name"]);
  
  move_uploaded_file($_FILES["file"]["tmp_name"], $filePath);
  
  $pathInfo = pathinfo($filePath);
  $fileExtension = $pathInfo["extension"];
  $fileName = $pathInfo["filename"];
  $fileBasename = $pathInfo["basename"];
  
  $mimeType = mime_content_type($filePath);
  
  $specialData = [];
  
  if (explode("/", $mimeType)[0] == "text") {
    $specialData = [
      "code" => false
    ];
  
    foreach (json_decode(file_get_contents("https://gist.githubusercontent.com/aymen-mouelhi/82c93fbcd25f091f2c13faa5e0d61760/raw")) as $lang) {
      $lang = (array) $lang;
      if (isset($lang["extensions"]) && in_array(".".$fileExtension, (array) $lang["extensions"])) {
        $specialData["code"] = true;
        break;
      }
    }
  }
  
  if (explode("/", $mimeType)[0] == "image") {
    $imageSize = getimagesize($filePath);
    $specialData = [
      "width" => $imageSize[0],
      "height" => $imageSize[1],
    ];
  
    if ($mimeType == "image/jpeg") {
      $fileExif = exif_read_data($filePath, 0, true);
  
      if (isset($fileExif["EXIF"]["DateTimeOriginal"])) {
        $specialData["taken"] = strtotime($fileExif["EXIF"]["DateTimeOriginal"]);
      }
      if (isset($fileExif["GPS"])) {
        $gps = $fileExif["GPS"];
        $latParts = array_map(function ($val) {
          return explode("/", $val)[0] / explode("/", $val)[1];
        }, $gps["GPSLatitude"]);
        $lonParts = array_map(function ($val) {
          return explode("/", $val)[0] / explode("/", $val)[1];
        }, $gps["GPSLongitude"]);
        $location = [
          "latitude" => $latParts[0] + (($latParts[1] * 60) + $latParts[2]) / 3600,
          "longitude" => $lonParts[0] + (($lonParts[1] * 60) + $lonParts[2]) / 3600,
          "altitude" => explode("/", $gps["GPSAltitude"])[0] / explode("/", $gps["GPSAltitude"])[1]
        ];
  
        $specialData["location"] = $location;
      }
    }
  }
  
  $array = [
    "filenames" => [
      "basename" => $fileBasename,
      "name" => $fileName,
      "extension" => $fileExtension,
    ],
    "meta" => [
      "upload" => time(),
      "author" => $_SESSION["name"],
      "size" => filesize($filePath),
      "type" => $mimeType
    ],
    "data" => $specialData,
    "display" => [
      "title" => $_REQUEST["title"]
    ]
  ];
  
  if (!empty($_REQUEST["description"])) {
    $array["display"]["description"] = $_REQUEST["description"];
  }
  
  file_put_contents($jsonPath, str_replace("    ", "  ", json_encode($array, JSON_PRETTY_PRINT)));
  
  echo $currentId;
}
else {
  echo "Error: Invalid request type";
}

?>