document.addEventListener("DOMContentLoaded", function () {

  searchRequest();

  $("#search input").on("input", searchRequest);

  function searchRequest() {
    valueEntered = $("#search input").val();

    data = "request-type=search&search-term=" + valueEntered;
    $.ajax({
      type: "GET",
      data: data,
      url: "api/article-server.php",
      success: function (response) {
        json = JSON.parse(response);
        $(".articles").html("");
        $.each(json, function (idx, e) {
          date = new Date(e.infos.date * 1000);
          if (e.image) {
            $image = `
              <div class="image">
                <img src="assets/media/${e.image}">
              </div>
            `;
          }
          else {
            $image = "";
          }
          $(".articles").append(`
            <a href="article?topic=${e.id}" style="color: inherit;">
              <div class="article">
                ${$image}
                <div class="content">
                  <h2>${e.title}</h2>
                  <h3>${e.subtitle}</h3>
                  <p>${e.description}</p>
                  <div class="meta">
                    <span>Von ${e.infos.author}</span>
                    <span>${("0" + date.getDate()).substr(-2)}. ${["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"][date.getMonth()]} ${date.getFullYear()}
                    </span>
                    <span>Version ${e.infos.version}</span>
                  </div>
                </div>
              </div>
            </a>
          `)
        })
      }
    })
  }

})