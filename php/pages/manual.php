<?php

$show = returnIfSet($_GET["show"]);
if (isset($show)) {
  $showArray = explode(",", $show);
} else {
  $showArray = [];
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Klassenwebsite &ndash; Editor-Anleitung</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/manual.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="/assets/js/general.js"></script>
  <script src="/assets/js/manual.js"></script>
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
    <h1>Anleitung zum Editor</h1>
    <p>Mit dem <a href="editor.php">Editor</a> kann man Artikel in einer leicht verständlichen Sprache schreiben, ähnlich einer MarkDown-Datei. Diese wird hier komplett erklärt, damit du so schnell wie möglich Artikel effizient schreiben kannst.</p>
<?php
$currentShown = in_array("drafts", $showArray);

echo $currentShown ? '<h2>' : '<h2 class="show-section" id="show-section-drafts">';
echo 'Über Entwürfe';
echo $currentShown ? '' : '<i class="fa-solid fa-caret-right"></i>';
echo '</h2>';
echo '<section '.($currentShown ? '' : 'class="showable-section" ').' id="drafts">';
?>
      <p>Wenn man einen Artikel erstellt oder bearbeitet, wird ein Entwurf erstellt. Der ist auf dem Server gespeichert und kann beliebig oft von allen bearbeitet werden. Solange an ihm geschrieben wird, wird er nicht in der Artikelauswahl angezeigt, sodass man ohne Bedenken Änderungen vornehmen kann.</p>
      <p>Wenn der Entwurf fertig ist, kann man ihn veröffentlichen. Das bedeutet, dass man ihn in der richtigen Ansicht lesen kann und er auch in der Artikelauswahl angezeigt wird. Wenn du einen Artikel bearbeitest, wird die alte Version durch die neue ersetzt. Sie bleibt aber in der Versionsgeschichte des Artikels, sodass man sie noch lesen kann.</p>
    </section>
<?php
$currentShown = in_array("tools", $showArray);

echo $currentShown ? '<h2>' : '<h2 class="show-section" id="show-section-tools">';
echo 'Toolbar';
echo $currentShown ? '' : '<i class="fa-solid fa-caret-right"></i>';
echo '</h2>';
echo '<section '.($currentShown ? '' : 'class="showable-section" ').' id="tools">';
?>
      <p>Am oberen Bildschirmrand befindet sich eine Toolbar mit Knöpfen, die verschiedene Aufgaben für den Editor erfüllen.</p>
      <h3 id="tools-save"><i class="fa-solid fa-floppy-disk"></i>Speichern</h3>
      <p>Lade hiermit den Entwurf auf den Server hoch. Dort ist er sicher gespeichert und kann jederzeit geladen und bearbeitet werden.</p>
      <h3 id="tools-load"><i class="fa-solid fa-cloud-arrow-down"></i>Laden</h3>
      <p>Lade die Version, die aktuell auf den Server gespeichert ist, in den Editor. Das kann hilfreich sein, wenn man aus Versehen einen Abschnitt gelöscht hat und ihn wiederherstellen möchte.</p>
      <h3 id="tools-publish"><i class="fa-solid fa-arrow-up-from-bracket"></i>Veröffentlichen</h3>
      <p>Veröffentliche den Entwurf. Dadurch wird er in der Leseansicht lesbar und ggf. eine neue Version erstellt, um die alte zu ersetzen. Mehr dazu auf der <a href="/write.php">Startseite</a>.</p>
      <h3 id="tools-files"><i class="fa-solid fa-photo-film"></i>Dateien</h3>
      <p>Hier kannst du Dateien (Bilder, Videos, etc.) vom Server der Klassenwebsite einfügen. Du kannst hier auch neue Sachen hochladen.</p>
      <h3 id="tools-manual"><i class="fa-solid fa-book"></i>Anleitung</h3>
      <p>Dieser Knopf bringt dich hierher. Benutze ihn immer, wenn du etwas Hilfe beim Editor brauchst.</p>
      <h3 id="tools-home"><i class="fa-solid fa-house"></i>Home</h3>
      <p>Klicke darauf, um zur Startseite der Editor-Sektion zu kommen. Dort kannst du Artikel zum Schreiben auswählen und ein Paar Infos bekommen.</p>
    </section>
<?php
$currentShown = in_array("format", $showArray);

echo $currentShown ? '<h2>' : '<h2 class="show-section" id="show-section-format">';
echo 'Formatieren des Textes';
echo $currentShown ? '' : '<i class="fa-solid fa-caret-right"></i>';
echo '</h2>';
echo '<section '.($currentShown ? '' : 'class="showable-section" ').' id="format">';
?>
      <h3>Text</h3>
      <p>Um ganz normalen Text zu schreiben, schreibt man diesen einfach. Der Editor erkennt diesen als Absatz an. Für einen neuen muss man einfach eine leere Zeile zwischen Texten lassen.</p>
      <p>Man kann aber auch Modifikationen am Aussehen des Textes vornehmen. Diese sind je nach Stärke/Länge in drei Kategorien geteilt: Zwischenzeilige Tags (inline Tags), Vollzeilige Tags (singleline Tags) und Mehrzeilige Tags (multiline Tags). Die Namen beschreiben sie zwar sehr genau, aber sie haben ein paar erwähnenswerte Unterschiede.</p>
      <h3>Zwischenzeilige Tags</h3>
      <p>Die zwischenzeiligen Tags nutzen statt dem Escape-Character <code>&amp;</code> ein eigenes Zeichen, wie zum Beispiel <code>^</code> für fett, <code>*</code> für kursiv oder <code>_</code> unterstrichen, und man muss sie einfach in einer Zeile einbauen. Das spezifische Zeichen steht am Anfang und am Ende von dem modifizierten Abschnitt, wie zum Beispiel <code>Der Unterricht mit ^ihm^ war…</code>.</p>
      <h4>Fett</h4>
      <p>Mit den Zirkumflexen <code>^</code> kann Text fett gemacht werden.</p>
      <h4>Kursiv</h4>
      <p>Mit den Asterisken <code>*</code> kann Text kursiv gemacht werden.</p>
      <h4>Unterstrichen</h4>
      <p>Mit den Unterstrichen <code>_</code> kann Text unterstrichen werden.</p>
      <h4>Durchgestrichen</h4>
      <p>Mit den Tilden <code>~</code> kann Text durchgestrichen werden.</p>
      <h4>Code</h4>
      <p>Mit den Rückenzecken <code>`</code> kann Text als Code formatiert werden.</p>

      <h3>Vollzeilige Tags</h3>
      <p>Vollzeilige Tags erstellt man mit dem Escape-Character am Anfang der Zeile und dem Namen des Tags dahinter. Ein Leerzeichen hinter dem Tag differenziert den Tag und den Inhalt dessen. Ein vollzeiliger Tag kann den Inhalt stark verändert anzeigen, wie zum Beispiel als Überschrift, oder mit dem Inhalt externe Medien laden. Ein Beispiel wären die Überschriften.</p>
      <h4>Überschriften</h4>
      <p id="headings">
        Der Editor bietet Überschriften von <testh1>ganz groß</testh1> bis <testh4>ganz klein</testh4>. Man muss nur den Escape-Character <code>&amp;</code> und dahinter 1 bis 4 mal <code>#</code> eingeben, und man bekommt die gewünschte Größe. Beispiel:
      </p>
      <codeblock>&amp;# Erstes Kapitel
&amp;## Der Anfang
Als unser mutiger Held auf …</codeblock>
      <h4>Dateien</h4>
      <p style="margin-bottom: 0;">Mit dem "File"-Tag kann man Bilder in einen Artikel einfügen. Man gibt das Schlüsselwort <code>&amp;file</code> und dahinter die einzigartige ID der Datei (bspw. <code>6ec01f980d735665</code>). Die ID kannst du in der Dateiansicht finden, über dem Download-Knopf:</p>
      <div class="file">
        <img src="/assets/images/where-to-find-upload-id.png">
      </div>
      <p>Dort steht die ID, hinter dem <code>#</code>. Oder du benutzt ganz einfach das genau <a href="?show=tools,format#tools-files">dafür vorgesehene Tool</a>. Klicke auf <i class="fa-solid fa-photo-film"></i> und wähle eine oder mehrere Datei(en) aus</p>
      <h3>Mehrzeilige Tags</h3>
      <p>Mehrzeilige Beanspruchung mehrere Zeilen auf einmal, erlauben dafür aber auch, dass der Inhalt mehrere Zeilen lang ist. Man braucht einen Öffnungs-Tag, der anzeigt, dass ein Mehrzeiliger Tag beginnt, und zum Schluss einen Schluss-Tag. Die sind dann das jeweilige Keyword. Hinter dem Öffnungs-Tag ist aber noch Platz, der für Faktoren genutzt wird. Ein Faktor ist nicht Teil des Inhaltes, sondern ändern was am Tag. Dazu steht mehr bei der Tabelle.</p>
      <h4>Zitate</h4>
      <p>Mit dem Keyword <code>&amp;quote</code> kann man ein Zitat makieren. Diese müssen aber allein in einer Zeile stehen und das Zitat dazwischen. Beispiel:</p>
      <codeblock>&amp;quote
Ich heiße Robert
&amp;quote</codeblock>
      <h4>Code</h4>
      <p>Mit dem Keyword <code>&amp;code</code> kann man einen Codeblock makieren. Diese haben auch keine Faktoren und können einfach so geschrieben werden:</p>
      <codeblock>&amp;code
package spielwiese;

public class Main {
  public static void main(String[] args) {
    System.out.println("Hello World!");
  }
}
&amp;code</codeblock>
      <h4>Tabellen</h4>
      <p>Mit dem Keyword <code>&amp;table</code> kann man ein Zitat makieren. Die Tabelle ist der erste Tag, der auch Faktoren benutzt. Es können die Faktoren <code>top</code> und <code>side</code> hinter dem Öffnungs-Tag stehen, die den Tabellenkopf bestimmen. Sie können auch kombiniert werden. Die Tabellen Reihen werden einfach durch neue Zeilen ausgedrückt, während zwischen Zellen ein | (alt gr + <) stehen muss. Beispiel:</p>
      <codeblock>&amp;table top side
Cards | Devourer of Souls | Decipede | Viking
Health | 6 | 3 | 8
Damage | 7 | 4 | 5
Speed | 2 | 9 | 4
Ability | On Kill: +2 Health | | <3 Health: + 1 Damage
&amp;table</codeblock>
      <table class="top side">
      <tbody>
        <tr>
          <td>Cards</td>
          <td>Devourer of Souls</td>
          <td>Decipede</td><td>Viking</td>
        </tr>
        <tr>
          <td>Health</td>
          <td>6</td>
          <td>3</td>
          <td>8</td>
        </tr>
        <tr>
          <td>Damage</td>
          <td>7</td>
          <td>4</td>
          <td>5</td>
        </tr>
        <tr>
          <td>Speed</td>
          <td>2</td>
          <td>9</td>
          <td>4</td>
        </tr>
        <tr>
          <td>Ability</td>
          <td>On Kill: +2 Health</td>
          <td></td>
          <td>&lt;3 Health: + 1 Damage</td>
        </tr>
      </tbody>
      </table>
<?php
echo $currentShown ? '</section>' : '';
?>
  </main>
</body>
</html>
