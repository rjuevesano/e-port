var imgArray = [];
jQuery(document).ready(function () {
  ImgUpload();
});

function ImgUpload() {
  var imgWrap = "";

  $(".upload__inputfile").each(function () {
    $(this).on("change", function (e) {
      imgWrap = $(this).closest(".upload__box").find(".upload__img-wrap");

      var files = e.target.files;
      var filesArr = Array.prototype.slice.call(files);
      filesArr.forEach(function (f, timeline) {
        if (!f.type.match("image.*")) return;

        imgArray.push(f);
        var reader = new FileReader();
        reader.onload = function (e) {
          var html =
            "<div class='upload__img-box'><div style='background-image: url(" +
            e.target.result +
            ")' data-number='" +
            $(".upload__img-close").length +
            "' data-file='" +
            f.name +
            "' class='img-bg'><div class='upload__img-close'>&#x2715</div></div></div>";
          imgWrap.append(html);
        };
        reader.readAsDataURL(f);
      });
    });
  });

  $("body").on("click", ".upload__img-close", function (e) {
    var file = $(this).parent().data("file");
    for (var i = 0; i < imgArray.length; i++) {
      if (imgArray[i].name === file) {
        imgArray.splice(i, 1);
        break;
      }
    }
    $(this).parent().parent().remove();
  });
}

function addPost() {
  var caption = document.getElementsByName("caption")[0].value;
  var formData = new FormData();
  formData.append("action", "addpost");
  formData.append("caption", caption);

  for (var timeline = 0; timeline < imgArray.length; timeline++) {
    formData.append("files[]", imgArray[timeline]);
  }

  $.ajax({
    url: "post.php",
    type: "post",
    data: formData,
    contentType: false,
    cache: false,
    processData: false,
    success: function (data) {
      window.location.reload();
    },
    error: function (error) {
      alert("Something went wrong.");
    },
  });
  return false;
}
