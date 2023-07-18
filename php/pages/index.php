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
    <a href="/index.php"><img src="/assets/images/logo.jpg" alt="Logo" id="logo"></a>
    <a href="/selection.php">Artikel</a>
    <a href="/write.php">Schreib was!</a>
    <a href="/uploads">Dateien</a>
    <a id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
  </nav>
  <main>
    <h1>Hallo<?php echo $_SESSION["name"] == "" ? "" : ", ".$_SESSION["name"]?></h1>
    <subtitle>Wilkommen bei der Klassenwebsite!</subtitle>
    <p>Hey, Leute. Nach endloser Arbeit ist die erste Version unserer Klassenwebsite nun endlich fertig &#x1f973;&#x1f973;&#x1f389;</p>
    <p>Wir haben einige nützliche Features hinzugefügt, die diese Website zu einem abgeschlossenen Produkt machen. Wir hoffen, dass sie euch gefallen.</p>
    <p>Aber Produkt &#x2260; Projekt, denn wir werden hieran immer weiterarbeiten. Wie viel, ist noch nicht klar, aber die Klassenwebsite wird sich immer weiter entwickeln.</p>
    <p>Viel Spaß von den Entwicklern</p>
    <p style="text-align: right; color: #888888;">~ 18.07.2023</p>
<?php
$allNames = array_map(function ($file) {
  return pathinfo($file)["filename"];
}, glob($mainDir."/assets/images/profiles/*"));
if (!(in_array($_SESSION["name"], $allNames) || empty($_SESSION["name"]))) {
?>
    <div style="display:flex; flex-direction: row; justify-content: center; margin-bottom: 20px;">
      <a style="background-color: #ed6464; color: #ffffff; padding: 10px; border-radius: 12px; width: -webkit-fit-content; " href="/upload.php">
        <p style="font-size: 16px; margin-bottom: 4px; text-align: center;">Hey, <?php echo $_SESSION["name"]; ?>. Sieht so aus, als hättest du noch kein Profilbild.</p>
        <p style="color: #ffffff; font-weight: bold; font-size: 18px; text-align: center; margin: 0;">Lade eins hoch!</p>
      </a>
    </div>
<?php
}
?>
    <h2 id="show-features">Aktuelle Features<i class="fa-solid fa-caret-right"></i></h2>
    <section id="features">
      <div class="feature">
        <i class="fa-solid fa-newspaper"></i>
        <div>
          <h3>Artikel</h3>
          <p>Auf unserer Klassenwebsite sind Beiträge in Artikel aufgeteilt. Keine Sorge, nicht wie Zeitungsartikel, das wäre viel zu formell! Stattdessen sind sie ganz formlose Texte über alle Themen, die uns als Klasse betreffen.</p>
        </div>
      </div>
      <div class="feature">
        <i class="fa-solid fa-file-pen"></i>
        <div>
          <h3>Editor</h3>
          <!-- Here's an easter egg… -->
          <p>Du kannst selbst Artikel schreiben! Und zwar ganz unkom<span id="pilz">pilz</span>iert. Du musst weder programmieren können, noch JSON verstehen, denn der Editor funktioniert mit ganz normalem Text. Lerne, wie das genau funktioniert bei <a href="/write.php">Schreib was!</a></p>
        </div>
      </div>
      <div class="feature">
        <i class="fa-solid fa-photo-film"></i>
        <div>
          <h3>Medien</h3>
          <p>Unsere Klassenwebsite unterstützt auch Dateien aller Art! Du kannst sie in Artikeln einbinden oder einfach nur in ihnen stöbern. Alles dazu findest du unter <a href="/uploads">Dateien</a>.</p>
        </div>
      </div>
    </section>
  </main>
</body>
</html>