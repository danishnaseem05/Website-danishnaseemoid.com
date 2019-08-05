// ******************** ADD NEW PROJECT CARD BELOW ******************** // 

// Get the modal
// Project 1
var project1_modal = document.getElementById("project1-modal");
//Project 2
// var project2_modal = document.getElementById("project2-modal");
// //Project 3
// var project3_modal = document.getElementById("project3-modal");
// //Project 3
// var project3_modal = document.getElementById("project3-modal");
// //Project 4
// var project4_modal = document.getElementById("project4-modal");

// Get the button that opens the modal
// Project 1
var project1_btn = document.getElementById("project1-btn");
// Project 2
// var project2_btn = document.getElementById("project2-btn");
// Project 3
// var project3_btn = document.getElementById("project3-btn");
// Project 4
// var project4_btn = document.getElementById("project4-btn");

// When the user clicks on the button, open the modal 
// Project 1
project1_btn.onclick = function() {project1_modal.style.display = "block";}
// Project 2
// project2_btn.onclick = function() {project2_modal.style.display = "block";}
// Project 3
// project3_btn.onclick = function() {project3_modal.style.display = "block";}
// Project 4
// project4_btn.onclick = function() {project4_modal.style.display = "block";}

// Close the Modal
function closeModal() {
  // Project 1
  document.getElementById("project1-modal").style.display = "none";
  // Project 2
  document.getElementById("project2-modal").style.display = "none";
  // Project 3
  document.getElementById("project3-modal").style.display = "none";
  // Project 4
  document.getElementById("project4-modal").style.display = "none";
}

// ********************************************************************* //

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
}