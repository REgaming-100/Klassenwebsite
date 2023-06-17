document.addEventListener("DOMContentLoaded", function () {

  searchRequest();

  $("#minisearch input").on("input", searchRequest);
  
  var articleIdConfirmed = false
  
  $("#edit-article-button").on("click", function () {
    $("#create-article-button").removeClass("active");
    $("#create-article-content").addClass("collapsed");

    $("#edit-article-button").addClass("active");
    $("#edit-article-content").removeClass("collapsed");
  });

  $("#create-article-button").on("click", function () {
    $("#edit-article-button").removeClass("active");
    $("#edit-article-content").addClass("collapsed");

    $("#create-article-button").addClass("active");
    $("#create-article-content").removeClass("collapsed");
  });

  $("#create-article-send").on("click", function (e) {
    e.preventDefault();
    if (articleIdConfirmed) {
      localStorage.setItem("editor-article-id", $("#create-article-id-field").val());
      window.open("editor.php","_self");
    }
  });

  $("#create-article-id-field").on("input", function () {
    articleIdConfirmed = false

    data = "request-type=article-exists&article-id=" + $("#create-article-id-field").val()
    $.ajax({
      type: "GET",
      data: data,
      url: "api/article-server.php",
      success: function (response) {
        articleIdConfirmation($("#create-article-id-field").val(), response)
      }
    })
  })

  function searchRequest() {
    valueEntered = $("#minisearch input").val();

    data = "request-type=search&search-term=" + valueEntered;
    $.ajax({
      type: "GET",
      data: data,
      url: "api/article-server.php",
      success: function (response) {
        json = JSON.parse(response);
        $("#search-results").html("");
        $.each(json, function (idx, e) {
          $("#search-results").append(`
            <div class="search-result">
              <p>${e.title}<i>${e.id}</i></p>
              <a class="edit-article-select"><i class="fa-solid fa-pen-to-square"></i></a>
            </div>
          `)
        });

        $(".edit-article-select").on("click", function () {
          id = $(this).prev().children("i").first().html();
          localStorage.setItem("editor-article-id", id);
          window.open("editor.php","_self");
        })
      }
    })
  }

  function articleIdConfirmation(valueEntered, articleExists) {
    articleIdConfirmed = false;
    $("#create-article-send").addClass("disabled");
    $("#article-id-info").html("").removeClass("error").removeClass("warning").removeClass("okay");

    if (valueEntered) {
      if (/^[a-z0-9\-]*$/.test(valueEntered)) {
        if (articleExists) {
          articleIdConfirmed = true
          $("#create-article-send").removeClass("disabled");
          $("#article-id-info").html("Diesen Artikel gibt es schon. Du kannst ihn bearbeiten.").addClass("warning");
        }
        else {
          articleIdConfirmed = true
          $("#create-article-send").removeClass("disabled");
          $("#article-id-info").html("Diese ID kannst du verwenden").addClass("okay");
        }
      }
      else {
        $("#article-id-info").html("Du hast ein unerlaubtes Zeichen verwendet").addClass("error");
      }
    }
  }

})