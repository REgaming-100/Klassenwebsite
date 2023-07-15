document.addEventListener("DOMContentLoaded", function () {

  adjustBox();
  setToPlaceholder();

  fileInput = $("#upload-file");
  uploadedFile = null;

  $("#title").on("input", checkIfSendable);
  $("#description").on("input", adjustBox);
  $("#submit").on("click", upload);
  $("#upload-file").on("change", function () {
    uploadedFile = fileInput.prop("files")[0];
    setToPreview(uploadedFile);
    checkIfSendable();
  });
  $("#upload-button").on("dragenter", handleDragEnter);
  $("#upload-button").on("dragleave", handleDragLeave);
  $("#upload-button").on("dragover", handleDragOver);
  $("#upload-button").on("drop", handleDrop);

  function adjustBox() {
    elem = $("#description");

    elem.height("0px");

    padd = elem.innerHeight() - elem.height();
    elem.height(elem[0].scrollHeight - padd);
  };

  function upload() {
    if (checkIfSendable()) {
      $("#submit").html(`<i class="fa-solid fa-spinner fa-spin"></i>`);
      failId = setTimeout(() => {
        $("#submit").html(`<i class="fa-solid fa-xmark"></i>`);
        setTimeout(() => $("#submit i").html("Hochladen"), 2000);
      }, 5000);

      file = uploadedFile;

      fd = new FormData();
      fd.append("title", $("#title").val());
      fd.append("description", $("#description").html());
      fd.append("file", file);

      $.ajax({
        type: "POST",
        url: "api/upload-server.php",
        data: fd,
        contentType: false,
        processData: false,
        success: function (uploadId) {
          clearTimeout(failId);
          $("#submit").html(`<i class="fa-solid fa-check"></i>`);
          setTimeout(() => {
            $("#submit").html("Hochladen");
          }, 2000);

          if ($("#stay:checked").length == 0) {
            window.open(`/upload/${uploadId}/view`, "_self");
          }
        }
      });
    }
  }

  function setToPlaceholder() {
    setTimeout(() => {
      $("#upload-button").attr("for", "upload-file");
    }, 100);
    $("#upload-button").removeClass("preview").addClass("placeholder");
    $("#upload-button").html(`
      <h1><i class="fa-solid fa-upload"></i>Datei hochladen</h1>
      <div id="info">Drag 'n' Drop oder hier klicken</div>
    `);
  }

  function setToPreview(file) {
    $("#upload-button").attr("for", "");
    $("#upload-button").removeClass("placeholder").addClass("preview");

    fileSize = shortenFileSize(file.size);
    
    buttonHtml = `<i class="fa-solid fa-xmark" id="close"></i>`;

    if (file.type.split("/")[0] == "image") {
      buttonHtml += `<img id="preview" src="${URL.createObjectURL(file)}">`;
    }
    else if (file.type.split("/")[0] == "video") {
      buttonHtml += `<video id="preview" src="${URL.createObjectURL(file)}"></video>`;
    }

    buttonHtml += `
      <div id="text">
        <h1>${file.name}</h1>
        <div id="infos">${Math.round(fileSize["size"] * 10) / 10} ${fileSize["multiplier"]}B</div>
      </div>
    `;

    $("#upload-button").html(buttonHtml);
    $("#upload-button i").on("click", function () {
      uploadedFile = null;
      checkIfSendable();
      setToPlaceholder();
    });
  }

  function shortenFileSize(bytes) {
    fileSize = bytes;
    fileSizeUnit = "";
    ["k", "M", "G", "T", "P", "E", "Z"].forEach(factor => {
      if (fileSize / 1000 >= 1) {
        fileSizeUnit = factor;
        fileSize /= 1000;
      }
      else {
        return false;
      }
    });

    return {size: fileSize, multiplier: fileSizeUnit};
  }

  function handleDragEnter(event) {
    $("#upload-button").addClass("dragging");
  }

  function handleDragLeave(event) {
    $("#upload-button").removeClass("dragging");
  }

  function handleDragOver() {
    event.preventDefault();
  }

  function handleDrop(event) {
    event.preventDefault();
    $("#upload-button").removeClass("dragging");

    uploadedFile = event.originalEvent.dataTransfer.files[0];
    setToPreview(uploadedFile);

    checkIfSendable();
  }

  function checkIfSendable() {
    sendable = uploadedFile && $("#title").val();

    if (sendable) {
      $("#submit").removeClass("disabled");
    }
    else {
      $("#submit").addClass("disabled");
    }

    return sendable;
  }

})