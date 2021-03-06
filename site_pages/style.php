<?php
    require 'projectSeed.php';
    $project_cards = projectCardsArr();

    header('Content-type: text/css; charset:UTF-8');
?>

/********* LAYOUT *********/
html{
    scroll-behavior: smooth;
}

body{
    position: relative;
    background-color:#292e32;
    font-family:inherit;
    text-align: center;
}

hr{ 
    border-radius: 0.5rem;
    background: white;
    margin: auto;
    margin-top: 4vh;
    width: 25%;
    padding: 0.02rem;
}

#page-container{
    position: relative;
    min-height: 100vh;
}

.wrapper{
    width: 60%;
    margin-left: 24vh;
}

.custom-small-container{
    margin: 0 0 20vh 0;
    border-style: solid;
    border-color: darkorange;
    border-width: 0.15em;
    border-radius: 1em;
    background-color:#343a40;
    padding-top:3em;
    padding-bottom: 3em;
    width: 70%;
}

.custom-container-spacing{
    padding-top: 40vh;
    padding-bottom: 20vh;
}

.custom-small-final-container{
    border-style: solid;
    border-color: darkorange;
    border-width: 0.15em;
    border-radius: 1em;
    background-color:#343a40;
    padding-top:3em;
    padding-bottom: 3em;
    width: 70%;
    height: 100%;
}

.name-title{
    padding-top: 13.5em;
    padding-bottom: 14em;
}

.custom-name-title{
    padding-top: 13.5em;
}


h1{
    font-weight: bolder;
    font-size: 6em;
    color:#fff;
}

.custom-text-size{
    font-size: 1.2em;
    font-weight: 700;
}

.custom-border{
    border-style: solid;
    border-width: 0.15em;
    border-radius: 1em;
    border-color: darkorange;
}

.custom-border-no-color{
    border-style: solid;
    border-width: 0.15em;
    border-radius: 1em;
}

.custom-half-border-cards{
    border-style: solid;
    border-width: 0.075em;
    border-radius: 1em;
    border-color: darkorange;
}

.info-page-heading{
    font-family: "Courier New", Courier, monospace;
}

/********* MEDIA *********/

#about-paralax{
    background: url("../pics/pg_backgrounds/eye-4063134_1920.jpg");
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    z-index: -10;
}
#about-paralax-overlay{
    background-color: rgba(29, 102, 161, 0.7);
    z-index: -5;
}

#skills-paralax{
    background: url("../pics/pg_backgrounds/dna-3539309_1920.jpg");
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    z-index: -10;
}
#skills-paralax-overlay{
    background-color: rgba(29, 102, 160, 0.8);
}

#projects-paralax{
    background: url("../pics/pg_backgrounds/artificial-intelligence-3382507_1920.jpg");
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    z-index: -10;
}
#projects-paralax-overlay{
    background-color: rgba(29, 102, 160, 0.8);
    z-index: -5;
}

#contact-paralax{
    background: url("../pics/pg_backgrounds/monitor-1307227_1920.jpg");
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    z-index: -10;
}
#contact-paralax-overlay{
    background-color: rgba(29, 102, 161, 0.6);
    z-index: -5;
}

#resume-page-paralax{
    background: url("../pics/pg_backgrounds/matrix-3408060_1920.jpg");
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    z-index: -10;
}
#resume-page-paralax-overlay{
    background-color: rgba(29, 102, 160, 0.45);
    z-index: -5;
}

#projects-page-paralax{
    background: url("../pics/pg_backgrounds/coding-1841550_1920.jpg");
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    z-index: -10;
}
#projects-page-paralax-overlay{
    background-color: rgba(29, 102, 160, 0.6);
    z-index: -5;
}

#success-page-paralax{
    background: url("../pics/pg_backgrounds/thumbs-up-2056022.jpg");
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    z-index: -10;
}
#success-page-paralax-overlay{
    background-color: rgba(0, 0, 0, 0.6);
    z-index: -5;
}

#error-page-paralax{
    background: url("../pics/pg_backgrounds/digital-484402_1920.jpg");
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    z-index: -10;
}
#error-page-paralax-overlay{
    background-color: rgba(0, 0, 0, 0.6);
    z-index: -5;
}

#games-page-paralax{
    background: url("../pics/pg_backgrounds/battle-black-board-game-277092.jpg");
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    z-index: -10;
}
#games-page-paralax-overlay{
    background-color: rgba(0, 0, 0, 0.6);
    z-index: -5;
}

<?php
// For each project card, set its cover
    foreach($project_cards as $id=>$value){
        echo <<<EOL
        #project-card-{$id}{
            background-image: url({$value[4]});
            background-size: cover;
            background-repeat: no-repeat;
        }
        EOL;
    }
?>

.general-overlay{
    z-index: 1;
    background-color: rgba(21, 22, 22, 0.788);
}
/********* CUSTOM HTML OVERRIDE *********/ 

.custom-p{
    border-radius: 1em;
    padding: 1em;
    background: #292e32;
    color: #fff;
    max-width: 80%;
}

.custom-h1{
    font-size: 4em;
    font-style: normal;
}

.custom-h2{
    font-style:oblique;
    font-family:serif;
    font-size: 2em;
    color:#fff;
    padding-bottom: 1em; 
}

.custom-h3{
    font-style:normal;
    font-family:serif;
    font-size: 1.5em;
    color:#fff;
    padding-bottom: 1em;
}

/********* COLOR *********/

.skill-font-color{
    color: white;
}

.custom-progress-bar{
    background: #ff7300;
}

.custom-progress-bar:hover{
    background: #ff5100;
}

.custom-nav-item{
    padding-left: 0.5em;
    padding-right: 0.5em;
    margin:0.6em 0.3em 0.6em 0.3em;
    border-style: outset;
    border-radius: 0.8em;
    border-width: 0.15em;
    border-color: rgba(255, 140, 0, 0.8);
    background: #343a40;
}

.custom-nav-item:hover{
    background: #292e32;
    border-color: #ff6a00;
}

.custom-input-color:hover{
    background-color: rgb(255, 174, 0);
    border-color: rgba(255, 174, 0, 0.6);
    border-width: 0.15em;
}

.custom-button-color{
    border: solid;
    background-color: #343a40;
    border-color: #343a40;
    border-width: 0.15em;
    border-radius: 1em;
}

.custom-button-color:hover{
    background-color: #292e32;
    border-color: #ff6a00;
    border-width: 0.15em;
}

.custom-send-button-color{
    background-color: #343a40;
    border-color: darkorange;
    border-width: 0.15em;
    border-radius: 1em;
}

.custom-send-button-color:hover{
    background-color: #292e32;
    border-color: #ff6a00;
    border-width: 0.15em;
}

.custom-a{
    text-decoration: none;
    color: darkorange;
}

.custom-a:hover{
    text-decoration: none;
    color: #ff6a00;
}

.custom-color{
    background-color: #292e32;
    margin-left: 2vh;
    margin-right: 2vh;
    font-size: 0.8em;
}

.navbar-custom{
    background-color: #292e32;
    border-bottom-style: inset;
    border-bottom-color: darkorange;
    border-width: 0.15em;
    border-radius: 0.6em;
}

.dark-color{
    background: #292e32;
}

.theme-orange{
    color: darkorange;
}
.theme-orange:hover{
    color: #ff6a00;
}

.hover-color:hover{
    color: rgb(216, 216, 216);
}

footer{
    border-top-style: outset;
    border-top-color: darkorange;
    border-width: 0.15em;
    border-radius: 1.5em;
    background: #343a40;
    color: #fff;
}

footer .container ul a{
    color: #fff;
}

.custom-container{
    min-height: -webkit-calc(100% - 130px);
    min-height: -moz-calc(100% - 130px);
    min-height: calc(100% - 130px); 
}

/********* MODALS AND CAROUSEL *********/

.custom-carousel-p{
    padding: 1em;
    background: #343a40e3;
    border: solid transparent;
    border-radius: 1em;
    font-size: 1.1em;
    font-style: oblique;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', 'sans-serif';

}

.custom-carousel-next, .custom-carousel-prev{
    color: white;
    background: rgba(0, 0, 0, 0.4);
}

.custom-carousel-next:hover, .custom-carousel-prev:hover{
    background-color: rgba(0,0,0,0.9);
}

.custom-modal-button{
    background: #343a4050;
    color: white;
    font-weight: bold;
    border: solid;
    border-color: #292e32fa;
    border-radius: 0.4em;
    width: 100%;
}

.custom-modal-button:hover{
    background: #292e32fa;
    color: white;
}

.custom-modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0, 0, 0, 0.9); /* Black w/ opacity */
  }


.custom-modal-content {
    background-color: rgba(255,255,255,0.25);
    margin: 10% auto; /* 15% from the top and centered */
    padding: 20px;
    width: 70%; /* Could be more or less, depending on screen size */
    border: solid;
    border-color: transparent;
    border-radius: 1em;
}  

  /* Add Animation - Zoom in the Modal */
.custom-modal-content { 
    animation-name: zoom;
    animation-duration: 0.6s;
}
  
@keyframes zoom {
    from {transform:scale(0)} 
    to {transform:scale(1)}
}

.Close{
      float: right;
      font-size: 2.5em;
      font-weight: bold;
      color: rgb(255, 255, 255);
      z-index: 5;
}

.Close:hover,
.Close:focus{
      color: rgba(255, 255, 255, 0.637);
}


/********* PROJECT PAGE LINKS DESIGN *********/

.projectLinks{
    position: relative;
    background-color:#343a40;
    border: double;
    border-color: silver;
    margin-bottom: 10px;
    font-size: 2.4vh;
    color: white;
    padding: 20px;
    width: 70vh;
    text-align: center;
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
    text-decoration: none;
    overflow: hidden;
    cursor: pointer;
}

.projectLinks:after{
    content: "";
    background: #f1f1f1;
    display: block;
    position: absolute;
    padding-top: 300%;
    padding-left: 350%;
    margin-left: -20px !important;
    margin-top: -120%;
    opacity: 0;
    transition: all 0.8s
}

.projectLinks:active:after{
    padding: 0;
    margin: 0;
    opacity: 1;
    transition: 0s
}

.projectLinks:hover{
    box-shadow: 0 8px 14px 0 rgba(0,0,0,0.25), 0 17px 50px 0 rgba(0,0,0,0.19);
    color: #ff6a00;
    background: #292e32;
}

/********* OTHER COMMENTED OUT CONTENT *********/

/*.bottomText{
    color: slategray;
    padding: 10px;
}*/

/*
#Aboutbtn{
    position: relative;
    padding: 12px 49px 12px 49px;
    font-family: inherit;
    font-size: 18px;
    border-radius: 16px;
    color: hsl(0, 0%, 90%);
    background: hsl(0,0%, 50%);
    box-shadow: 0 18px 14px 0 rgba(0,0,0,0.25), 0 17px 50px 0 rgba(0,0,0,0.19);
}
#Aboutbtn:hover{
    position: relative;
    animation-name: Projectsbtn;
    animation-duration: 900ms;
    animation-timing-function: linear;
    animation-direction: alternate-reverse;
    animation-iteration-count: 2;
    animation-fill-mode:forwards;
}
@keyframes Aboutbtn{
    0%{background: linear-gradient(45deg , hsl(0,0%, 40%), hsl(0, 0%, 10%) 40px);}
    10%{background: linear-gradient(45deg , hsl(0, 0%, 10%), hsl(0,0%, 40%) 40px, hsl(0, 0%, 10%) 60px);}
    20%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 20px, hsl(0,0%, 40%) 60px, hsl(0, 0%, 10%) 80px);}
    30%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 40px, hsl(0,0%, 40%) 80px, hsl(0, 0%, 10%) 100px);}
    40%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 60px, hsl(0,0%, 40%) 100px, hsl(0, 0%, 10%) 120px);}
    50%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 80px, hsl(0,0%, 40%) 120px, hsl(0, 0%, 10%) 140px);}
    60%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 100px, hsl(0,0%, 40%) 140px, hsl(0, 0%, 10%) 160px);}
    70%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 120px, hsl(0,0%, 40%) 160px, hsl(0, 0%, 10%) 180px);}
    80%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 140px, hsl(0,0%, 40%) 180px, hsl(0, 0%, 10%) 200px);}
    100%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 160px, hsl(0,0%, 40%) 200px, hsl(0, 0%, 10%) 220px);}
}


#Projectsbtn{
    position: relative;
    padding: 12px 49px 12px 49px;
    font-family: inherit;
    font-size: 18px;
    border-radius: 16px;
    color: hsl(0, 0%, 90%);
    background: hsl(0,0%, 50%);
    box-shadow: 0 18px 14px 0 rgba(0,0,0,0.25), 0 17px 50px 0 rgba(0,0,0,0.19);
}
#Projectsbtn:hover{
    position: relative;
    animation-name: Projectsbtn;
    animation-duration: 900ms;
    animation-timing-function: linear;
    animation-direction: alternate-reverse;
    animation-iteration-count: 2;
    animation-fill-mode:forwards;
    
}
@keyframes Projectsbtn{
    0%{background: linear-gradient(45deg , hsl(0,0%, 40%), hsl(0, 0%, 10%) 40px);}
    10%{background: linear-gradient(45deg , hsl(0, 0%, 10%), hsl(0,0%, 40%) 40px, hsl(0, 0%, 10%) 60px);}
    20%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 20px, hsl(0,0%, 40%) 60px, hsl(0, 0%, 10%) 80px);}
    30%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 40px, hsl(0,0%, 40%) 80px, hsl(0, 0%, 10%) 100px);}
    40%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 60px, hsl(0,0%, 40%) 100px, hsl(0, 0%, 10%) 120px);}
    50%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 80px, hsl(0,0%, 40%) 120px, hsl(0, 0%, 10%) 140px);}
    60%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 100px, hsl(0,0%, 40%) 140px, hsl(0, 0%, 10%) 160px);}
    70%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 120px, hsl(0,0%, 40%) 160px, hsl(0, 0%, 10%) 180px);}
    80%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 140px, hsl(0,0%, 40%) 180px, hsl(0, 0%, 10%) 200px);}
    100%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 160px, hsl(0,0%, 40%) 200px, hsl(0, 0%, 10%) 220px);}
    
}

#Resumebtn{
    position: relative;
    padding: 12px 49px 12px 49px;
    font-family: inherit;
    font-size: 18px;
    border-radius: 16px;
    color: hsl(0, 0%, 90%);
    background: hsl(0,0%, 50%);
    box-shadow: 0 18px 14px 0 rgba(0,0,0,0.25), 0 17px 50px 0 rgba(0,0,0,0.19); 
}
#Resumebtn:hover{
    position: relative;
    animation-name: Resumebtn;
    animation-duration: 900ms;
    animation-timing-function: linear;
    animation-direction: alternate-reverse;
    animation-iteration-count: 2;
    animation-fill-mode:forwards;
}
@keyframes Resumebtn{
    0%{background: linear-gradient(45deg , hsl(0,0%, 40%), hsl(0, 0%, 10%) 40px);}
    10%{background: linear-gradient(45deg , hsl(0, 0%, 10%), hsl(0,0%, 40%) 40px, hsl(0, 0%, 10%) 60px);}
    20%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 20px, hsl(0,0%, 40%) 60px, hsl(0, 0%, 10%) 80px);}
    30%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 40px, hsl(0,0%, 40%) 80px, hsl(0, 0%, 10%) 100px);}
    40%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 60px, hsl(0,0%, 40%) 100px, hsl(0, 0%, 10%) 120px);}
    50%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 80px, hsl(0,0%, 40%) 120px, hsl(0, 0%, 10%) 140px);}
    60%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 100px, hsl(0,0%, 40%) 140px, hsl(0, 0%, 10%) 160px);}
    70%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 120px, hsl(0,0%, 40%) 160px, hsl(0, 0%, 10%) 180px);}
    80%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 140px, hsl(0,0%, 40%) 180px, hsl(0, 0%, 10%) 200px);}
    100%{background: linear-gradient(45deg , hsl(0, 0%, 10%) 160px, hsl(0,0%, 40%) 200px, hsl(0, 0%, 10%) 220px);}
}

    
#AboutbtnPage{
    position: relative;
    padding: 13px 50px 13px 50px;
    font-family: inherit;
    font-size: 19px;
    border-radius: 16px;
    color: darkorange;
    background-color:black;
    box-shadow: 0 18px 14px 0 rgba(0,0,0,0.25), 0 17px 50px 0 rgba(0,0,0,0.19);
}

#ProjectsbtnPage{
    position: relative;
    padding: 13px 50px 13px 50px;
    font-family: inherit;
    font-size: 19px;
    border-radius: 16px;
    color: darkorange;
    background-color:black;
    box-shadow: 0 18px 14px 0 rgba(0,0,0,0.25), 0 17px 50px 0 rgba(0,0,0,0.19);
}

#ResumebtnPage{
    position: relative;
    padding: 13px 50px 13px 50px;
    font-family: inherit;
    font-size: 19px;
    bo 
