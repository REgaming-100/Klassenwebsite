<!DOCTYPE html>
<html>
<head>
  <title>Unsere Klasse &ndash; Die Klassenwebsite</title>
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
  <link rel="stylesheet" type="text/css" href="/assets/css/editor.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="/assets/js/general.js"></script>
  <script src="/assets/js/editor.js"></script>
</head>
<body>
  <menu>
    <item title="Speichern"><i class="fa-solid fa-floppy-disk" id="save"></i></item>
    <item title="Laden"><i class="fa-solid fa-cloud-arrow-down" id="load"></i></item>
    <item title="Veröffentlichen"><i class="fa-solid fa-arrow-up-from-bracket" id="publish"></i></item>
    <!--<div id="add">
      <button id="dropper">Hinzufügen…<i class="fa-solid fa-caret-down"></i></button>
      <div id="dropdown">
        <a>h1</a>
        <a>h2</a>
        <a>table</a>
        <a>quote</a>
      </div>
    </div>-->
    <item title="Anleitung"><i class="fa-solid fa-book" id="manual"></i></item>
    <item title="Startseite"><i class="fa-solid fa-house" id="home"></i></item>
  </menu>
  <div id="popups">
    <div id="darkener"></div>
    <div class="box" id="box-save-changes">
      <i class="fa-solid fa-xmark close-popup"></i>
      <h1>Speichern?</h1>
      <p>"Du hast ungespeicherte Änderungen. Möchtest du speichern, bevor du das tust?</p>
      <div class="buttons">
        <button class="danger" id="dont-save">Nicht speichern</button>
        <button class="action" id="save">Speichern & Weiter</button>
      </div>
    </div>
    <div class="box" id="box-publish">
      <i class="fa-solid fa-xmark close-popup"></i>
      <h1>Veröffentlichen</h1>
      <p>Veröffentliche diesen Artikel, damit er für alle lesbar wird! Diese Version wird automatisch bei der Suche angezeigt und ersetzt damit die alte, falls vorhanden. Ältere Versionen kann man in der Versionsansicht des Artikels anschauen.</p>
      <p>Wähle aus, wie groß die Veränderung ist, die an dem Artikel vorgenommen wurde. Hier kannst du sehen, welche Änderungen was entsprechen:</p>
      <div class="comparison">
        <div class="column">
          <h1>Hauptversion</h1>
          <p>Neuer Abschnitt</p>
          <p>Überarbeitung des Artikels</p>
          <p>Große inhaltliche Änderungen</p>
        </div>
        <div class="column">
          <h1>Unterversion</h1>
          <p>Hinzufügen von Medien</p>
          <p>Umschreiben von Abschnitten</p>
          <p>Behebung von Fehlern</p>
          <p>Nur kleine Änderungen</p>
        </div>
      </div>
      <p>Wenn dies ein neuer Artikel ist, macht es keinen Unterschied. Aus technischen Gründen musst du aber trotzdem etwas auswählen.</p>
      <fieldset id="importance">
        <label>
          <input type="radio" name="major-minor-change" value="major">
          <b>Hauptversion</b>
          <p>Diese Version hat wesentliche Änderungen erfahren, wie zum Beispiel einen völlig neuen Abschnitt.</p>
        </label>
        <label>
          <input type="radio" name="major-minor-change" value="minor">
          <b>Unterversion</b>
         <p>Diese Version hat geringe Änderungen erhalten, wie eine umgeschriebene Passage, Behebung von Rechtschreib- oder Grammatikfehlern oder ein neues Zitat.</p>
        </label>
        <p id="error">Bitte wähle eine Option aus</p>
      </fieldset>
      <p>Der Artikel wird die Version <code id="next-version">???</code> bekommen.</p>
      <div class="buttons">
        <button id="cancel">Abbrechen</button>
        <button class="action disabled" id="publish-final">Veröffentlichen!</button>
      </div>
    </div>
  </div>
  <main>
    <p id="article-id"></p>
    <h1 id="title" class="edit-without-nl editable" contenteditable="plaintext-only"></h1>
    <subtitle class="subtitle edit-without-nl editable" contenteditable="plaintext-only"></subtitle>
    <p id="just-normal-text"></p>
    <div id="description-container">
      <div>Beschreibung</div>
      <span id="description" class="edit-without-nl editable" contenteditable="plaintext-only" data-placeholder="Füge eine kurze Beschreibung zum Artikel hinzu…"></span>
    </div>
    <textarea id="editor-content"></textarea>
  </main>
</body>
</html>