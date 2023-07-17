document.addEventListener("DOMContentLoaded", function () {

  $("#search input").on("input", function () {
    filter($(this).val());
  });
  $("#search #filters div").on("click", function () {
    $(this).toggleClass("selected");
    filter();
  });

  function filter(searchTerm) {
    if ($("#search #filters div.selected").length > 0) {
      $(".upload").css("display", "none");

      $.each($("#search #filters div.selected"), function (i, filter) {
        filterName = $(filter).attr("upload-filter");
        $(".upload.type-"+filterName).css("display", "flex");
      });
    }
    else {
      $(".upload").css("display", "flex");
    }

    if (searchTerm) {
      $.each($(".upload"), function (i, upload) {
        title = $(upload).children("#details").children("h1").html();
        if (title.toLowerCase().indexOf(searchTerm.toLowerCase()) < 0) {
          $(upload).css("display", "none");
        }
      });
    }
  }

})