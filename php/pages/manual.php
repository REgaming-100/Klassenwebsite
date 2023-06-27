<?php

$show = isset($_GET["show"]) ? $_GET["show"] : null;

?>
<!DOCTYPE html>
<html>
<head>
  <title>Unsere Klasse &ndash; Die Klassenwebsite</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/manual.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="/assets/js/general.js"></script>
  <script src="/assets/js/manual.js"></script>
</head>
<body>
  <nav>
    <a href="index.php"><img src="/assets/images/logo.png" alt="Logo" id="logo"></a>
    <a href="selection.php">Artikel</a>
    <a href="write.php">Schreib was!</a>
    <a id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
  </nav>
  <main>
    <h1>Anleitung zum Editor</h1>
    <p>Mit dem <a href="editor.php">Editor</a> kann man Artikel in einer leicht verständlichen Sprache schreiben, ähnlich einer MarkDown-Datei. Diese wird hier komplett erklärt, damit du so schnell wie möglich Artikel effizient schreiben kannst.</p>
    <div style="font-family: 'Chivo Mono', monospace; color: #dd0000; margin: 0 auto 20px; text-align: center; width: min(600px, 90%)">In der Beta-Version sind nur wenige Features verfügbar. Bald wird mehr hinzugefügt.</div>
    <h2
<?php
$currentShown = ($show != "drafts");
echo $currentShown ? '<h2 class="show-section" id="show-section-drafts">' : "<h2>";
echo 'Über Entwürfe';
echo $currentShown ? '<i class="fa-solid fa-caret-right"></i>' : '';
echo '</h2>';
echo $currentShown ? '<section class="showable-section" id="drafts">' : '';
?>
      <p>Wenn man einen Artikel erstellt oder bearbeitet, wird ein Entwurf erstellt. Der ist auf dem Server gespeichert und kann beliebig oft von allen bearbeitet werden. Solange an ihm geschrieben wird, wird er nicht in der Artikelauswahl angezeigt, sodass man ohne Bedenken Änderungen vornehmen kann.</p>
      <p>Wenn der Entwurf fertig ist, kann man ihn veröffentlichen. Das bedeutet, dass man ihn in der richtigen Ansicht lesen kann und er auch in der Artikelauswahl angezeigt wird. Wenn du einen Artikel bearbeitest, wird die alte Version durch die neue ersetzt. Sie bleibt aber in der Versionsgeschichte des Artikels, sodass man sie noch lesen kann.</p>
<?php
echo $currentShown ? '</section>' : '';

$currentShown = ($show != "tools");
echo $currentShown ? '<h2 class="show-section" id="show-section-tools">' : "<h2>";
echo 'Toolbar';
echo $currentShown ? '<i class="fa-solid fa-caret-right"></i>' : '';
echo '</h2>';
echo $currentShown ? '<section class="showable-section" id="tools">' : '';
?>
      <p>Am oberen Bildschirmrand befindet sich eine Toolbar mit Knöpfen, die verschiedene Aufgaben für den Editor erfüllen.</p>
      <h3><i class="fa-solid fa-floppy-disk"></i>Speichern</h3>
      <p>Lade hiermit den Entwurf auf den Server hoch. Dort ist er sicher gespeichert und kann jederzeit geladen und bearbeitet werden.</p>
      <h3><i class="fa-solid fa-cloud-arrow-down"></i>Laden</h3>
      <p>Lade die Version, die aktuell auf den Server gespeichert ist, in den Editor. Das kann hilfreich sein, wenn man aus Versehen einen Abschnitt gelöscht hat und ihn wiederherstellen möchte.</p>
      <h3><i class="fa-solid fa-arrow-up-from-bracket"></i>Veröffentlichen</h3>
      <p>Veröffentliche den Entwurf. Dadurch wird er in der Leseansicht lesbar und ggf. eine neue Version erstellt, um die alte zu ersetzen. Mehr dazu auf der <a href="write.php">Startseite</a>.</p>
      <h3><i class="fa-solid fa-book"></i>Anleitung</h3>
      <p>Dieser Knopf bringt dich hierher. Benutze ihn immer, wenn du etwas Hilfe beim Editor brauchst.</p>
      <h3><i class="fa-solid fa-house"></i>Home</h3>
      <p>Klicke darauf, um zur Startseite der Editor-Sektion zu kommen. Dort kannst du Artikel zum Schreiben auswählen und ein Paar Infos bekommen.</p>
<?php
echo $currentShown ? '</section>' : '';

$currentShown = $show != "format";
echo $currentShown ? '<h2 class="show-section" id="show-section-format">' : "<h2>";
echo 'Formatieren des Textes';
echo $currentShown ? '<i class="fa-solid fa-caret-right"></i>' : '';
echo '</h2>';
echo $currentShown ? '<section class="showable-section" id="format">' : '';
?>
      <h3>Text</h3>
      <p>Um ganz normalen Text zu schreiben, schreibt man diesen einfach. Der Editor erkennt diesen als Absatz an. Für einen neuen Absatz Enter drücken.</p>
      <h3>Überschriften</h3>
      <p id="headings">
        Der Editor bietet Überschriften von <testh1>ganz groß</testh1> bis <testh4>ganz klein</testh4>. Man muss nur den Escape-Character <code>&</code> und dahinter 1 bis 4 mal <code>#</code> eingeben, und man bekommt die gewünschte Größe. Beispiel:
      </p>
      <code>
        &# Erstes Kapitel<br>
        &## Der Anfang<br>
        Als unser mutiger Held auf ...
      </code>
      <h3 style="margin-top: 20px;">Zitate</h3>
      <p>Mit den Keywords <code>&amp;quote</code> und <code>&amp;quote end</code> kann man ein Zitat makieren. Diese müssen aber allein in einer Zeile stehen und das Zitat dazwischen. Beispiel:</p>
      <code>
        &amp;quote<br>
        Ich heiße Robert<br>
        &amp;quote end
      </code>
<?php
echo $currentShown ? '</section>' : '';
?>
  </main>
</body>
</html>