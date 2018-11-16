(function() {

  var streaming   = false,
      video       = document.querySelector('#video'),
      canvas      = document.querySelector('#canvas'),
      smile       =  document.querySelector('#smile'),
      save        = document.getElementById('save'),
      del         = document.getElementById('delete'),
      img1        = document.getElementById('img1'),
      img2        = document.getElementById('img2'),
      img3        = document.getElementById('img3'),
      width       = 500,
      height      = 300,
      filter      = "none";

  navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);

  navigator.getMedia(
    {
      video: true,
      audio: false
    },
    function(stream) {
      if (navigator.mozGetUserMedia) {
        video.mozSrcObject = stream;
      } else {
        var vendorURL = window.URL || window.webkitURL;
        video.src     = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err) {
       canvas.width     = 500;
       canvas.height    = 375;
       video.width      = 500;
       video.height     = 375;
       smile.innerHTML  = "Upload an Image (.PNG with tranparent propreties)";
    }
  );
  video.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth/width);
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }
  }, false);

  function getCode() {
    var code = Math.floor((Math.random() * 10000) + 1);
    return (code);
  }

  function takepicture(code) {
    if (img1.checked && video.currentTime > 0 ||
      img2.checked && video.currentTime > 0 || img3.checked && video.currentTime > 0)
    {
      canvas.width  = width;
      canvas.height = height;
      del.style     = "";
      save.style    = "";
      canvas.getContext('2d').drawImage(video, 0, 0, width, height);
      if (img1.checked)
        filter = "../img/cactus.png";
      if (img2.checked)
        filter = "../img/flame.png";
      if (img3.checked)
        filter = "../img/boom.png";
      var data = canvas.toDataURL('image/png');

        var xhr_mergeIN = new XMLHttpRequest();
        mergeIN = new FormData();
        mergeIN.append('action', 'merge');
        mergeIN.append('data', data);
        mergeIN.append('filter', filter);
        mergeIN.append('tmp', code);

        xhr_mergeIN.open('POST', 'assets/processing.php');
        xhr_mergeIN.send(mergeIN);
        xhr_mergeIN.onreadystatechange = function () {
          var DONE  = 4;
          var OK    = 200;
          if (xhr_mergeIN.readyState === DONE) {
            if (xhr_mergeIN.status === OK) {
              var output = xhr_mergeIN.responseText;
              if (output == "ERROR") {
            del.click();
          }
            else {
              var filtered = new Image();
              filtered.src = output;
              canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
              filtered.onload = function() {
                canvas.getContext('2d').drawImage(filtered, 0, 0, canvas.width, canvas.height);
              }
            }
          }
        }
      }
    }
    else if (img1.checked && video.currentTime == 0 || img2.checked && video.currentTime == 0 ||
      img3.checked && video.currentTime == 0)
    {
      var upload = document.getElementById('uploadedFile');
      upload.click();
        upload.addEventListener("change", function() {
        del.style   = "";
        save.style  = "";
        var file    = document.querySelector('input[type=file]').files[0];
        var reader  = new FileReader();
        reader.addEventListener("load", function () {
            var data = reader.result;
            if (img1.checked)
              filter = "../img/cactus.png";
            if (img2.checked)
              filter = "../img/flame.png";

            var mergeUpload = new FormData();
            mergeUpload.append('action', 'merge');
            mergeUpload.append('data', data);
            mergeUpload.append('filter', filter);
            mergeUpload.append('uploaded', upload.value);
            mergeUpload.append('tmp', code);
            var xhr_mergeUpload = new XMLHttpRequest();
            xhr_mergeUpload.open('POST', 'assets/processing.php');
            xhr_mergeUpload.send(mergeUpload);
            xhr_mergeUpload.onreadystatechange = function () {
              var DONE  = 4;
              var OK    = 200;
              if (xhr_mergeUpload.readyState === DONE) {
                if (xhr_mergeUpload.status === OK) {
                  var output = xhr_mergeUpload.responseText;
                  if (output == "ERROR")
                    del.click();
                else {
                  var filtered    = new Image();
                  filtered.src    = output;
                  canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                  filtered.onload = function() {
                    canvas.getContext('2d').drawImage(filtered, 0, 0, canvas.width, canvas.height);
                  }
                }
              }
            }
          }

          }, false);
          if (file)
            reader.readAsDataURL(file);
      });
    }
}
  smile.addEventListener('click', function(ev){
      code = getCode();
      takepicture(code);
    ev.preventDefault();
  }, false);

  del.addEventListener('click', function() {
    if (del.innerHTML == "Delete") {
      del.style       = "visibility:hidden;";
      save.style      = "visibility:hidden;";
      canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
      var delData     = new FormData();
      delData.append('action', 'delete');
      delData.append('tmp', code);
      var xhr_Delete  = new XMLHttpRequest();
      xhr_Delete.open('POST', 'assets/processing.php');
      xhr_Delete.send(delData);
    }
  });

  save.addEventListener('click', function() {
    if (save.innerHTML == "Save") {
      save.style     = "visibility:hidden;";
      del.style      = "visibility:hidden;";
      canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
      var saveData = new FormData();
      saveData.append('action', 'save');
      saveData.append('tmp', code);
      var xhr_Save  = new XMLHttpRequest();
      xhr_Save.open('POST', 'assets/processing.php');
      xhr_Save.send(saveData);
      xhr_Save.onreadystatechange = function () {
          var DONE = 4;
          var OK = 200;
          if (xhr_Save.readyState === DONE) {
            if (xhr_Save.status === OK) {
              location.reload();
            }
          }
        }
    }
  });

})();