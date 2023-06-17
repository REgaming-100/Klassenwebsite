document.addEventListener("DOMContentLoaded", function () {

  // Here's an easter egg…
  $("#pilz")
    .on("mouseover", function () {
      $(this).html("&#127812;");
    })
    .on("mouseout", function () {
      $(this).html("pilz");
    });

  $("#show-features").on("click", function () {
    $(this).html("Aktuelle Features");
    $(this).css("cursor", "initial");
    $("#features").css("display", "block");
  });

});