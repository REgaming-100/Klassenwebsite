document.addEventListener("DOMContentLoaded", function () {

  $("#edit-article").on("click", function () {
    localStorage.setItem("editor-article-id", $(this).attr("topic"));
    window.open("editor.php","_self");
  });

  $("select").on("change", function () {
    window.open("?topic=" + $(this).attr("topic") + "&version=" + $(this).find("option:selected").attr('value'), "_self");
  });

  $(".file-error #open").on("click", function () {
    window.open("/upload/" + $(this).parent().attr("upload-id") + "/view", "_blank");
  });

});