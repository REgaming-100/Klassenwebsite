<?php
if (isset($_POST["key"])) {
  $key = $_POST["key"];
}
else if (isset($_GET["key"])) {
  $key = $_GET["key"];
}
else {
  echo "Error: No key given";
  exit();
}

echo base64_encode(hash("sha256", $key, true));
?>