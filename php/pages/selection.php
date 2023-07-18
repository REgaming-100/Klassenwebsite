<!DOCTYPE html>
<html>
<head>
  <title>Klassenwebsite &ndash; Alle Artikel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/selection.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="/assets/js/general.js"></script>
  <script src="/assets/js/selection.js"></script>
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
    <h1>Artikelauswahl</h1>
    <div id="search">
      <input type="text" placeholder="Nach Artikeln suchen">
    </div>
    <div class="articles"></div>
  </main>
</body>
</html>