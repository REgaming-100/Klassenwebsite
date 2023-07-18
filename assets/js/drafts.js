document.addEventListener("DOMContentLoaded", function () {

  $(".edit-draft").on("click", function () {
    id = $(this).parent().parent().attr("article-id");
    localStorage.setItem("editor-article-id", id);
    window.open("editor.php","_self");
  });

  $(".delete-draft").on("click", function () {
    if (!$(this).hasClass("disabled")) {
      id = $(this).parent().parent().attr("article-id");

      $.ajax({
        type: "POST",
        data: {
          "request-type": "delete-draft",
          "article-id": id
        },
        url: "api/draft-server.php",
        success: function () {
          location.reload();
        }
      });
    }
  });

})