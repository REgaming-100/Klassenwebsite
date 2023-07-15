<!DOCTYPE html>
<html>
<head>
  <title>Unsere Klasse &ndash; Upload</title>
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/upload.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="/assets/js/general.js"></script>
  <script src="/assets/js/upload.js"></script>
</head>
<body>
  <nav>
    <a href="index.php"><img src="/assets/images/logo.png" alt="Logo" id="logo"></a>
    <a href="selection.php">Artikel</a>
    <a href="write.php">Schreib was!</a>
    <a id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
  </nav>
  <main>
    <input type="text" id="title" placeholder="Titel">
    <textarea id="description" placeholder="Beschreibung"></textarea>
    <input type="file" id="upload-file" hidden>
    <p>Titel und Datei müssen vorhanden sein. Die Beschreibung ist optional. Zurzeit kann man nur eine Datei gleichzeitig hochladen.</p>
    <label id="upload-button"></label>
    <div id="stay-check">
      <input type="checkbox" id="stay">
      <label for="stay">Auf dieser Seite bleiben</label>
    </div>
    <button id="submit" class="disabled">Hochladen</button>
  </main>
</body>
</html>