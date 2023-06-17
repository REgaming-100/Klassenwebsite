document.addEventListener("DOMContentLoaded", function () {

  $("#logout").on("click", function () {
    $("#logout i").removeClass("fa-right-from-bracket").addClass("fa-spinner").addClass("fa-spin");

    $.ajax({
      type: "GET",
      url: "api/logout.php",
      success: function (response) {
        window.open("login.php","_self");
      }
    });
  });
})