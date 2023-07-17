<!DOCTYPE html>
<html>
<head>
  <title>Unsere Klasse &ndash; Die Klassenwebsite</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/index.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="/assets/js/general.js"></script>
  <script src="/assets/js/index.js"></script>
</head>
<body>
  <nav>
    <a href="index.php"><img src="/assets/images/logo.png" alt="Logo" id="logo"></a>
    <a href="selection.php">Artikel</a>
    <a href="write.php">Schreib was!</a>
    <a id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
  </nav>
  <main>
    <h1>Hallo<?php echo $_SESSION["name"] == "" ? "" : ", ".$_SESSION["name"]?></h1>
    <subtitle>Wilkommen bei der Klassenwebsite!</subtitle>
    <h2>Über diese Version</h2>
    <p>Ja, wir haben lange hierfür gebraucht. Seit März programmieren wir schon und sind nun endlich bei einem Punkt angekommen, bei dem wir euch das Ergebnis zeigen können. Bedenkt aber, dass das hier nur eine Beta-Version ist. Das heißt, sie ist verwendbar, aber nur eine Vorschau, bevor die erste fertige Version herausgebracht wird.</p>
    <p>Hab viel Spaß beim Ausprobieren der Klassenwebsite<?php echo $_SESSION["name"] == "" ? "" : ", ".$_SESSION["name"]?>!</p>
    <p>Wir Entwickler sind mittlerweile wieder am Arbeiten und versuchen, nicht allzu lange für den Release zu brauchen. Freut euch auf unzählige weitere Funktionen in Bälde.</p>
    <h2 id="show-features">Aktuelle Features<i class="fa-solid fa-caret-right"></i></h2>
    <section id="features">
      <div class="feature">
        <i class="fa-solid fa-newspaper"></i>
        <div>
          <h3>Artikel</h3>
          <p>Auf unserer Klassenwebsite sind Beiträge in Artikel aufgeteilt. Keine Sorge, nicht wie Zeitungsartikel, das wäre viel zu formell! Stattdessen sind sie ganz frei geschrieben und über alle Themen, die uns als Klasse betreffen. Freue dich auf einige großartige Artikel über aus unsere gemeinsame Zeit.</p>
        </div>
      </div>
      <div class="feature">
        <i class="fa-solid fa-file-pen"></i>
        <div>
          <h3>Editor</h3>
          <!-- Here's an easter egg… -->
          <p>Du kannst selbst Artikel schreiben! Und zwar ganz unkom<span id="pilz">pilz</span>iert. Du musst weder programmieren können, noch JSON verstehen, denn der Editor funktioniert mit ganz normalem Text. Lernen, wie genau das funktioniert und anfangen zu schreiben kannst du bei <a href="write.php">Schreib was!</a></p>
        </div>
      </div>
    </section>
  </main>
</body>
</html>