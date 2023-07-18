document.addEventListener("DOMContentLoaded", function () {

  idLocation = window.location.hash
  if (idLocation) {
    setTimeout(() => {
      document.getElementById(idLocation.substring(1)).scrollIntoView();
      window.scrollBy(0, -200);
      window.scrollBy(0, 100);
    }, 10);
  }

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