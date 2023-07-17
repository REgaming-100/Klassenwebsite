<!DOCTYPE html>
<html>
<head>
  <title>Unsere Klasse &ndash; Die Klassenwebsite</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/write.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="/assets/js/general.js"></script>
  <script src="/assets/js/write.js"></script>
</head>
<body>
  <nav>
    <a href="index.php"><img src="/assets/images/logo.png" alt="Logo" id="logo"></a>
    <a href="selection.php">Artikel</a>
    <a href="write.php">Schreib was!</a>
    <a id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
  </nav>
  <main>
    <h1>Hallo<?php echo $_SESSION["name"] == "" ? "" : ", ".$_SESSION["name"] ?></h1>
    <subtitle>Leiste deinen Beitrag zur Klassenwebsite!</subtitle>
    <p>Willkommen zum Editor, wo du eigene Artikel schreiben oder bereits vorhandene bearbeiten kannst. Der Editor ermöglicht es dir, den Artikel zu strukturieren, Bilder und Videos einzubinden und jederzeit eine Vorschau anzusehen. In der <a href="manual.php">Anleitung</a> gibt es weitere Informationen. Egal, ob du einen professionellen Bericht schreiben oder deinen Lehrerhass ausdrucken möchtest, der Editor ermöglicht dir das. Also leg los und lass deiner Kreativität freien Lauf!</p>
    <p style="font-family: 'Chivo Mono', monospace; color: #dd0000; text-align: left;">Einige der beschriebenen Funktionen sind noch nicht verfügbar.</p>
    <div id="help-drafts">
      <p>Hilf dabei, Entwürfe fertigzustellen! Dann können sie schneller veröffentlicht werden und liegen nicht ewig herum.</p>
      <div class="links">
        <a href="drafts.php">Zu den Entwürfen<i class="fa-solid fa-angle-right"></i></a>
        <a href="manual.php?show=drafts">Über Entwürfe<i class="fa-solid fa-angle-right"></i></a>
      </div>
    </div>
    <h2>Bearbeiten oder Erstellen</h2>
    <p>Wähle hier aus, wenn du einen Artikel erstellen oder bearbeiten möchtest. Wenn es schon einen Entwurf davon gibt, wird der geöffnet. Sonst wird ein neuer erstellt.</p>
    <div id="way-selector">
      <div id="edit-article-button" class="way">
        <i class="fa-solid fa-pen-to-square"></i>
        <h1>Bearbeite einen Artikel</h1>
      </div>
      <div id="create-article-button" class="way">
        <i class="fa-solid fa-file"></i>
        <h1>Erstelle einen neuen</h1>
      </div>
    </div>
    <div id="edit-article-content" class="article-content collapsed">
      <h1>Etwas verändern?</h1>
      <p>Kein Problem! Du kannst <a href="selection.php">einen Artikel öffnen</a> und auf <i class="fa-solid fa-pen-to-square"></i> klicken oder hier einen aussuchen:</p>
      <div id="minisearch" class="article-id-form">
        <input type="text" placeholder="Suche nach Artikeln">
      </div>
      <div id="search-results"></div>
    </div>
    <div id="create-article-content" class="article-content collapsed">
      <h1>Ein neues Thema?</h1>
      <p>Du kannst gleich anfangen zu schreiben, du musst nur kurz die Artikel-ID eingeben. Das ist eine Zeichenkette, die zur Identifikation des Artikels verwendet wird. Was die ist, ist eigentlich egal, sie sollte aber zum Artikel passen.</p>
      <p>Du kannst die Buchstaben <code>a-z</code>, die Zahlen <code>0-9</code> und das Sonderzeichen <code>-</code> verwenden. Eine gültige Artikel-ID wäre zu Beispiel <code>toni-braeunig</code>.</p>
      <form class="article-id-form">
        <input id="create-article-id-field" type="text" name="article-id" placeholder="Gib hier die Artikel-ID ein…" autocomplete="off">
        <input id="create-article-send" class="disabled" type="submit" value="Los geht's!">
        <p id="article-id-info"></p>
      </form>
    </div>
  </main>
</body>
</html>