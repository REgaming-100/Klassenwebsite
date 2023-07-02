document.addEventListener("DOMContentLoaded", function () {

  $("#edit-article").on("click", function () {
    localStorage.setItem("editor-article-id", $(this).attr("topic"));
    window.open("editor","_self");
  });

  $("select").on("change", function () {
    window.open("?topic=" + $(this).attr("topic") + "&version=" + $(this).find("option:selected").attr('value'), "_self");
  });

});