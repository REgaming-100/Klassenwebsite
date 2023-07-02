document.addEventListener("DOMContentLoaded", function () {

  $(".edit-draft").on("click", function () {
    id = $(this).parent().parent().children("p").children("code").html();
    localStorage.setItem("editor-article-id", id);
    window.open("editor","_self");
  });

  $(".delete-draft").on("click", function () {
    if (!$(this).hasClass("disabled")) {
      id = $(this).parent().parent().children("p").children("code").html();

      $.ajax({
        type: "POST",
        data: data = `request-type=delete-draft&article-id=${id}`,
        url: "api/draft-server.php",
        success: function () {
          location.reload();
        }
      });
    }
  });

})