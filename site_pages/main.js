// // Get the modal
 var project1_modal = document.getElementById("project1-modal");
// // Get the button that opens the modal
 var project1_btn = document.getElementById("project1-btn");

// // When the user clicks on the button, open the modal 
 project1_btn.onclick = function() {
   project1_modal.style.display = "block";
 }

  // Close the Modal
  function closeModal() {
    // Project 1
    document.getElementById("project1-modal").style.display = "none";
    // // Project 2
    // document.getElementById("project2-modal").style.display = "none";
    // // Project 3
    // document.getElementById("project3-modal").style.display = "none";
    // // Project 4
    // document.getElementById("project4-modal").style.display = "none";
  }
  
  var slideIndex = 1;
  showSlides(slideIndex);
  
  // Next/previous controls
  function plusSlides(n) {
    showSlides(slideIndex += n);
  }
  
  // Thumbnail image controls
  function currentSlide(n) {
    showSlides(slideIndex = n);
  }
  
  function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("demo");
    var captionText = document.getElementById("caption");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
    captionText.innerHTML = dots[slideIndex-1].alt;
  }