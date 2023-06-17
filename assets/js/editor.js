document.addEventListener("DOMContentLoaded", function () {

  // Set variables
  articleId = localStorage.getItem("editor-article-id");
  changesSaved = true;
  needingStartPopup = true;
    
  // Function for manual and home
  $("menu item #manual").on("click", function () {
    openManual = function () { window.open("manual.php","_self") };

    if (changesSaved) {
      openManual();
    }
    else {
      popupSaveChanges(openManual, function () {
        uploadContent(openManual);
      }, "du das Handbuch öffnest");
    }
  });
  $("menu item #home").on("click", function () {
    openHome = function () { window.open("write.php","_self") };

    if (changesSaved) {
      openHome();
    }
    else {
      popupSaveChanges(openHome, function () {
        uploadContent(openHome);
      }, "du zur Startseite gehst");
    }
  });

  // If there is an article id, show it. Else, hide the editor part.
  if (articleId) {
    if (!(/^[a-z0-9\-]*$/.test(articleId))) {
      noArticleId(true);
      return;
    }
    else {    
      $("#article-id").html("<code>" + articleId + "</code>");
      downloadContent();
    }
  }
  else {
    noArticleId();
    return;
  }

  // Functions for the toolbar buttons
  $("menu item #save").on("click", function () {
    spin("#save", "fa-floppy-disk");
    uploadContent(function () {
      afterSpin("#save", "fa-floppy-disk");
    });
  });
  $("menu item #load").on("click", function () {
    spin("#load", "fa-cloud-arrow-down");
    downloadContent(function () {
      afterSpin("#load", "fa-cloud-arrow-down");
    });
  });
  //$("menu #dropper").on("click", function () {
  //  $("#dropdown").toggleClass("show");
  //});
  $("menu item #publish").on("click", function () {
    if (changesSaved) {
      popupPublish();
    }
    else {
      popupSaveChanges(popupPublish, function () {
        uploadContent(popupPublish);
      }, "du veröffentlichst");
    }
  });

  // Force some inputs to be one line
  $(".edit-without-nl").keydown(function(e) {
    if (e.keyCode === 13) {
      document.execCommand('insertHTML', false, "");
      return false;
    }
  });

  // When user changes anything, adjust text field and note that there are unsaved changes
  $("#title, subtitle, #description, #editor-content").on("input", function () {
    changesSaved = false;
    adjustBox();
  });

  // Display warning when user tries to leave and has unsaved changes
  $(window).on("beforeunload", function (e) {
  	if (!changesSaved && $("#popups #box-save-changes.shown").length == 0) {
      return "Du hast ungespeicherte Änderungen";
    }
  });

  // Close the dropdown when the user clicks out of it
  //$(window).on("click", function (event) {
  //  if (!$(event.target).is("#dropper")) {
  //    $("#dropdown").removeClass("show");
  //  }
  //});

  // Close popups when their x is clicked
  $("#popups .box i").on("click", function () {
    closePopup($(this).parent().attr("id"));
  })

  // Adjust the textbox height to fit its content
  function adjustBox() {
    elem = $("#editor-content")

    elem.height("0px");

    padd = elem.innerHeight() - elem.height();
    elem.height(elem[0].scrollHeight - padd);
  };

  // Save everything to the DRAFTS folder
  function uploadContent(successFunction = null) {
    title = encodeURIComponent($("#title").html());
    subtitle = encodeURIComponent($("subtitle").html());
    description = encodeURIComponent($("#description").html());
    content = encodeURIComponent($("#editor-content").val());

    data = `request-type=content-sync&article-id=${articleId}&title=${title}&subtitle=${subtitle}&description=${description}&content=${content}`
    $.ajax({
      type: "POST",
      data: data,
      url: "api/draft-server.php",
      success: function () {
        changesSaved = true;

        successFunction ? successFunction() : null;
      }
    });
  };

  // Load everything from the DRAFTS folder
  function downloadContent(successFunction = null) {
    data = "request-type=content-sync&article-id=" + articleId
    $.ajax({
      type: "GET",
      data: data,
      url: "api/draft-server.php",
      success: function (response) {
        changesSaved = true;

        json = JSON.parse(response);
        if (needingStartPopup) {
          popupStart(json["new"]);
        }
        $("#title").html(json["title"]);
        $("subtitle").html(json["subtitle"]);
        $("#description").html(json["description"]);
        $("#editor-content").val(json["body"]);
        adjustBox();

        successFunction ? successFunction() : null;
      }
    });
  };

  // Publish everything to become a readable article
  function publishContent(importance, successFunction = null) {
     data = `request-type=publish&article-id=${articleId}&change=${importance}`
    $.ajax({
      type: "POST",
      data: data,
      url: "api/release-server.php",
      success: function (response) {
        successFunction ? successFunction() : null;
      }
    });
  }

  function noArticleId(badId = false) {
    $("#article-id").css("display", "none");
    $("#title").html("Editor").removeClass("editable").prop("contenteditable", false);
    $("#description-container").css("display", "none");
    $("#editor-content").css("display", "none");
    $("subtitle").removeClass("editable").prop("contenteditable", false);
  
    if (badId) {
      $("subtitle").html("Die Artikel-ID enthält ein unerlaubtes Zeichen");
      $("#just-normal-text").html('Die Artikel-ID, die du gerade abrufen willst, enthält ungültige Zeichen. Das heißt, die <b>kann</b> es gar nicht geben! Gehe zurück, um einen gültigen Artikel auszuwählen.<div class="links"><a id="to-write">Auswahl-Seite<i class="fa-solid fa-angle-right"></i></a></div>');
    }
    else {
      $("subtitle").html("Du bearbeitest gerade nichts");
      $("#just-normal-text").html('Es wurde keine Artikel-ID eingegeben! Gehe zurück, um einen Artikel auszuwählen.<div class="links"><a id="to-write">Auswahl-Seite<i class="fa-solid fa-angle-right"></i></a></div>');
    }

    $("#just-normal-text .links #to-write").on("click", function () {
      localStorage.removeItem("editor-article-id");
      window.open("write.php", "_self");
    });

    $("menu item i:not(#home)").addClass("disabled").off("click");
  }

  // The popup when you first open the article in editor
  // This is not working yet.
  function popupStart(newDraft) {
    needingStartPopup = false;

    if (newDraft) {
      popUpText = "Das ist eine nagelneue Bearbeitungsversion. Du kannst hier deine Änderungen vornehmen"
    }
    else {
      popUpText = "Oh, hier hat schon jemand was gemacht. Du kannst es weiter bearbeiten und dann veröffentlichen!"
    }
  }

  // When you perform an action where you might want to save before
  function popupSaveChanges(functionDontSave, functionSave, info) {
    $("#popups #box-save-changes p").html("Du hast ungespeicherte Änderungen. Möchtest du speichern, bevor " + info + "? Wenn du nicht speicherst, werden deine Änderungen gelöscht.");

    $("#popups #darkener").addClass("shown");
    $("#popups #box-save-changes").addClass("shown");

    $("#popups #box-save-changes .buttons *").off("click");
    $("#popups #box-save-changes .buttons *").on("click", function () {
      ($(this).attr("id") == "save") ? functionSave() : functionDontSave();
      closePopup("box-save-changes");
    });
  }

  // The popup for publishing the article
  function popupPublish() {
    $("#popups #darkener").addClass("shown");
    $("#popups #box-publish").addClass("shown");

    // When you click an option, allow publishing
    $("#importance label input").on("click", function () {
      $("#publish-final").removeClass("disabled");
      $("#importance #error").removeClass("shown");

      data = `request-type=next-version&article-id=${articleId}&change=${$(this).val()}`
      $.ajax({
        type: "GET",
        data: data,
        url: "api/release-server.php",
        success: function (response) {
          $("#next-version").html(response);
        }
      });

      $("#publish-final").off("click");
      $("#publish-final").on("click", function () {
        $("#publish-final").html('<i class="fa-solid"></i>')
        spin("#publish-final i", "")
        publishContent(
          $("#importance label input:checked").val(),
          function () {
            afterSpin("#publish-final i", "fa-check");
            localStorage.removeItem("editor-article-id");
            noArticleId();
            setTimeout(() => {
              window.open(`article.php?topic=${articleId}`,"_self");
            }, 500);
          }
        );
      });
    })

    $("#box-publish .buttons").off("click");
    $("#box-publish .buttons #cancel").on("click", function () {
      closePopup("box-publish");
    });
    // Show error when you try to publish with no option selected
    $("#box-publish .buttons #publish-final").on("click", function () {
      $("#importance #error").addClass("shown");
    });
  }

  // The function to close any popup by its id
  function closePopup(id) {
    $("#popups #" + id).removeClass("shown");

    if ($("#popups .box.shown").length == 0) {
      $("#popups #darkener").removeClass("shown");
    }
  }

  // The spinning wheel when an action is processed serverside
  function spin(id, fa) {
    ["#save", "#load", "#publish"].forEach(function (item) {
      $(item).css("pointer-events", "none");
    })
    
    $(id).removeClass(fa).addClass("fa-spinner").addClass("fa-spin");
    failId = setTimeout(() => {
      $(id).removeClass("fa-spinner").removeClass("fa-spin").addClass("fa-xmark");
      setTimeout(() => $(id).removeClass("fa-xmark").addClass(fa), 2000);
    }, 5000);
  }

  // When the server is done processing the action
  function afterSpin(id, fa) {
    clearTimeout(failId);
    $(id).removeClass("fa-spinner").removeClass("fa-spin").addClass("fa-check");
    setTimeout(() => {
      $(id).removeClass("fa-check").addClass(fa);
        
      ["#save", "#load", "#publish"].forEach(function (item) {
        $(item).css("pointer-events", "auto");
      })
    }, 2000);
  }
})