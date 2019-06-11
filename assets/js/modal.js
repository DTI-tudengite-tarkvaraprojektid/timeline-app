/*jshint esversion:6*/

  var modal = document.getElementById('myModal');


  var img = $('.GPics');
  var modalImg = $("#img01");
  var captionText = document.getElementById("caption");
  $('body').on('click', '.GPics', function(){
    modal.style.display = "block";
    var newSrc = $(this).data('path');
    modalImg.attr('src', newSrc);
    captionText.innerHTML = this.alt;
  });

  var span = document.getElementsByClassName("close")[0];

  span.onclick = function() {
    modal.style.display = "none";
  };
