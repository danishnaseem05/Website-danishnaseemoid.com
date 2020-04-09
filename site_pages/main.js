var navbar = document.querySelector('nav');

function on(n) {
  document.getElementById(`project${n}-modal`).style.display = "block";
  navbar.style.display = "none";
}

// Add New Project Cards in the funtion off() below
function off() {
  navbar.style.display = "block";
  // Project card 1
  document.getElementById(`project1-modal`).style.display = "none";
  // Project card 2
  document.getElementById(`project2-modal`).style.display = "none";
  // Project card 3
  document.getElementById(`project3-modal`).style.display = "none";
  // Project card 4
  document.getElementById(`project4-modal`).style.display = "none";
  // Project card 5
  document.getElementById(`project5-modal`).style.display = "none";
  // Project card 6
  document.getElementById(`project6-modal`).style.display = "none";

}

// reCAPTCHA
$(document).ready(function(){
  $(".recaptchaForm").on('submit', function(event){
    var recaptcha = $("#g-recaptcha-response").val();
    if(recaptcha===""){
      event.preventDefault();
      alert("Please Check reCAPTCHA!");
    }
    event.preventDefault();
    $.post("../php/contact.php", {
      "secret": "6Lf0COgUAAAAAECTfB7n3DZ3yCGtYPX6f9MsHvy5",
      "response":recaptcha
    }, function(response){
      console.log(response);
      alert(response);
    })
  })
});