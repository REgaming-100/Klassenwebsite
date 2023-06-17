document.addEventListener("DOMContentLoaded", function () {

  $(".show-section").on("click", function () {
    id = $(this).attr("id").replace("show-section-", "");

    $(this).html($(this).html().replace(/<i.*><\/i>/, ""));
    $(this).css("cursor", "initial");
    $(".showable-section#" + id).css("display", "block");
  });

});