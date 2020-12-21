var navbar = document.querySelector('nav');

function on(n) {
  document.getElementById(`project${n}-modal`).style.display = "block";
  navbar.style.display = "none";
}

// Add New Project Cards in the funtion off() below
function off() {
  navbar.style.display = "block";

  project_modals = document.getElementById("project-modals").children
  for(var i=0; i<project_modals.length; i++){
    project_modals[i].style.display="none"
  }
}

// reCAPTCHA
$(document).ready(function(){
  $(".recaptchaForm").on('submit', function(event){
    var recaptcha = $("#g-recaptcha-response").val();
    if(recaptcha===""){
      event.preventDefault();
      alert("Please Check reCAPTCHA!");
    }
    else{
      event.preventDefault();
      $.post("../php/contact.php", {
        "secret": "6Lf0COgUAAAAAECTfB7n3DZ3yCGtYPX6f9MsHvy5",
        "response":recaptcha,
        "first_name":$("#form-contact-first-name").val(),
        "last_name":$("#form-contact-last-name").val(),
        "phone":$("#form-contact-phone").val(),
        "email":$("#form-contact-email").val(),
        "message":$("#form-contact-message").val()
      }, function(response){
        console.log(response);
        //alert(response);
        self.location = response;
        top.location = response;
      })
    }
  })
});