<html lang="en">


  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Portfolio">
    <meta name="keywords" content="Danish,Naseem,Ruby,Python,HTML,CSS,Bash,Developer,Programmer,Portfolio,Coder,Today's,Songs,todays,today,projects,blog,naseemoid,student,college,Computer,Science,university">
    <meta name="author" content="Danish Naseem">
    <meta name="norton-safeweb-site-verification" content="bdyy78ovrh7nu6zxzbuxf27ugwz0oth5pdpo02u-sdfj4-39soh6j6ob0pmbcpwtx64tzv9g8m73j3g6my-ojbsc2xnjuv0eupmc59gcuet0bb37fjmxtfb7alb08moq" />

    <!-- Google -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145388957-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-145388957-1');
    </script>

    <!-- Google reCAPTCHA v2 -->
    <script src="https://www.google.com/recaptcha/api.js?"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="fontawesome-free-5.9.0-web/fontawesome-free-5.9.0-web/css/all.min.css">
    <link rel="stylesheet" href="fontawesome-free-5.9.0-web/fontawesome-free-5.9.0-web/css/fontawesome.min.css">
     
    <!-- My CSS -->
    <link href = "site_pages/style.php" rel = "stylesheet" type = "text/css">
    <link href = "site_pages/media_queries.css" rel = "stylesheet" type = "text/css">

    <title>Home | Danish Naseem</title>

    <!-- Website Logo -->
    <link rel = "icon" href = "./pics/Danish Naseem-logo/profile.png" type = "image/x-icon"> 

  </head>






  <body data-spy='scroll' data-target='.navbar' data-offset='150'>
      
    <div id="page-container">

        <!-- Navigation -->
        <header>
            <nav class="navbar navbar-expand-md navbar-dark navbar-custom fixed-top">
                <div class="container">
                    <a class="navbar-brand" href="./index.php"><span class="hover-color">D<span class="theme-orange">/</span>N</span></a>
                    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="navbar-collapse collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link active" href="#About">Home</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="#Skills">Skills</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="site_pages/projects.php">Projects</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="#Resume">Resume</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="#Contact">Contact</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="./site_pages/games.php">Games</a>
                            </li>
                        </ul>
                    </div><!-- .navbar-toggler .collapsed .show-->
                </div><!-- .container -->   
            </nav>
        </header>

        <!-- Main Content -->
        <main>

            <div id="About"></div>
            <div id= "about-paralax">
                <div id= "about-paralax-overlay">
                    <div class= "container">
                        <div class="name-title">
                            <div class="row justify-content-center">
                                <h1 class="title my-5 text-left">I<span class="theme-orange">'</span>M
                                <br>
                                DANISH
                                <br>
                                NASEEM<span class="theme-orange">.</span></h1>
                            </div>
                            <div class="row justify-content-center">
                                <h2 class="custom-h2 sub-heading">
                                    <strong>Software Engineer</strong>
                                </h2>
                            </div>
                        </div> <!-- .name-title -->
                    </div> <!-- .container -->
                    <br><br><br><br>
                    <div class="container">
                        <div class="row mx-0">
                            <section class="about-section">
                                <div id="Resume"></div>
                                <div class="row justify-content-center">
                                        <div class = "custom-small-container">
                                            <div class="row justify-content-center">
                                                <h2 class="custom-h2">Who Am I?</h2>
                                            </div>
                                            <div class="row justify-content-center">
                                                <p class="custom-p">
                                                Skilled Software Developer with over 3 years of experience in computer programming, 
                                                including designing new programs and applications or editing and improving upon code 
                                                in existing software programs and applications that will enhance business, in a variety of industries. 
                                                This site serves as a Portfolio showcasing all my completed as well as in progress projects.
                                                Educating myself along the way; as a great person once said:
                                                </p>
                                            </div><!-- .container .row -->
                                            <div class="row justify-content-center">
                                                <p class="custom-p">
                                                    <i class="theme-orange fas fa-quote-left fa-1x fa-pull-left"></i>
                                                    <?php
                                                        // The following gets a random quote from the quote array
                                                        $quotes = array(array("Dwayne Johnson", "Success isn't always about greatness. It's about consistency. Consistent hard work leads to success. Greatness will come."),
                                                        array("Oscar Wilde", "Be yourself; everyone else is already taken."),
                                                        array("William W. Purkey", "You've gotta dance like there's nobody watching,
                                                        Love like you'll never be hurt,
                                                        Sing like there's nobody listening,
                                                        And live like it's heaven on earth."),
                                                        array("Ralph Waldo Emerson", "To be yourself in a world that is constantly trying to make you something else is the greatest accomplishment."),
                                                        array("Albert Einstein", "The measure of intelligence is the ability to change."), 
                                                        array("Harriet Tubman", "Every great dream begins with a dreamer. Always remember, you have within you the strength, the patience, and the passion to reach for the stars to change the world."),
                                                        array("Jim Rohn", "If you are not willing to risk the usual you will have to settle for the ordinary."),
                                                        array("Walt Disney", "All our dreams can come true if we have the courage to pursue them."),
                                                        array("Eleanor Roosevelt", "No one can make you feel inferior without your consent."),
                                                        array("Thomas Jefferson", "I find that the harder I work, the more luck I seem to have."),
                                                        array("Napoleon Hill", "The starting point of all achievement is desire.")
                                                        );
                                                        $random_index = rand(0, sizeof($quotes)-1);
                                                        echo "<em>" . $quotes[$random_index][1] . "</em>";
                                                    ?>
                                                    <i class="theme-orange fas fa-quote-right fa-1x fa-pull-right"></i><br>
                                                    <?php
                                                        echo "<em>~ " . $quotes[$random_index][0] . "</em>";
                                                    ?>
                                                </p>
                                            </div><!-- .container .row -->
                                            <div class="row justify-content-center">
                                                <a href="./documents/Danish Naseem Resume.pdf" class="btn btn-success btn-rounded custom-send-button-color fa-1x" download><i class="far fa-arrow-alt-circle-down"></i> Download Resume</a>
                                            </div><!-- .container row -->

                                        </div><!-- .custom-small-container -->
                                </div><!-- .row -->
                            </section>
                            <!--Grid column-->
                        </div>
                    </div> <!-- .container -->
                </div> <!-- #about-paralax-overlay -->
            </div> <!-- #about-paralax -->





            <div id="Skills"></div>
            <div id= "skills-paralax">
                <div id= "skills-paralax-overlay">
                    <div class="container custom-container-spacing">
                        <!-- Skills section -->
                        <section class="skill-font-color">
                            <!--Section heading-->
                            <h2 class="custom-h1 h1-responsive text-white font-weight-bold text-center my-5">Skills</h2>
                            <!-- Section description -->
                            <p class="custom-text-size text-light text-center w-responsive mx-auto mb-5 pb-5">MY KNOWLEDGE LEVEL IN SOFTWARE.</p>
                             <!--Grid row-->
                            <div class="row mx-4 pt-2 justify-content-center">
                                <!--Grid column-->
                                <div class="col-md-2 mb-0">
                                    <h2 class="custom-h2">Java</h2>
                                </div>
                                <!--Grid column-->
                                <!--Grid column-->
                                <div class="col-md-8 mb-5">
                                    <div class="progress">
                                        <div class="progress-bar custom-progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:90%">9/10
                                        </div>
                                    </div>
                                </div>
                                <!--Grid column--> 
                            </div>
                            <!--Grid row-->
                            <!--Grid row-->
                            <div class="row mx-4 justify-content-center">
                                <!--Grid column-->
                                <div class="col-md-2 mb-0">
                                    <h2 class="custom-h2">Python</h2>
                                </div>
                                <!--Grid column-->
                                <!--Grid column-->
                                <div class="col-md-8 mb-5">
                                    <div class="progress">
                                        <div class="progress-bar custom-progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:90%">9/10
                                        </div>
                                    </div>
                                </div>
                                <!--Grid column--> 
                            </div>
                             <!--Grid row-->
                            <div class="row mx-4 justify-content-center">
                                <!--Grid column-->
                                <div class="col-md-2 mb-0">
                                    <h2 class="custom-h2">Ruby on Rails</h2>
                                </div>
                                <!--Grid column-->
                                <!--Grid column-->
                                <div class="col-md-8 mb-5">
                                    <div class="progress">
                                        <div class="progress-bar custom-progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%">8/10
                                        </div>
                                    </div>
                                </div>
                                <!--Grid column--> 
                            </div>
                            <!--Grid row-->
                             <!--Grid row-->
                            <div class="row mx-4 justify-content-center">
                                <!--Grid column-->
                                <div class="col-md-2 mb-0">
                                    <h2 class="custom-h2">HTML & CSS</h2>
                                </div>
                                <!--Grid column-->
                                <!--Grid column-->
                                <div class="col-md-8 mb-5">
                                    <div class="progress">
                                        <div class="progress-bar custom-progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%">8/10
                                        </div>
                                    </div>
                                </div>
                                <!--Grid column--> 
                            </div>
                             <!--Grid row-->
                             <!--Grid row-->
                            <div class="row mx-4 justify-content-center">
                                <!--Grid column-->
                                <div class="col-md-2 mb-0">
                                    <h2 class="custom-h2">PHP</h2>
                                </div>
                                <!--Grid column-->
                                <!--Grid column-->
                                <div class="col-md-8 mb-5">
                                    <div class="progress">
                                        <div class="progress-bar custom-progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:70%">7/10
                                        </div>
                                    </div>
                                </div>
                                <!--Grid column--> 
                            </div>
                             <!--Grid row-->
                             <!--Grid row-->
                            <div class="row mx-4 justify-content-center">
                                <!--Grid column-->
                                <div class="col-md-2 mb-0">
                                    <h2 class="custom-h2">JavaScript</h2>
                                </div>
                                <!--Grid column-->
                                <!--Grid column-->
                                <div class="col-md-8 mb-5">
                                    <div class="progress">
                                        <div class="progress-bar custom-progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:60%">6/10
                                        </div>
                                    </div>
                                </div>
                                <!--Grid column--> 
                            </div>
                             <!--Grid row-->
                                <!--Grid row-->
                            <div class="row mx-4 justify-content-center">
                                <!--Grid column-->
                                <div class="col-md-2 mb-0">
                                    <h2 class="custom-h2">Bash</h2>
                                </div>
                                <!--Grid column-->
                                <!--Grid column-->
                                <div class="col-md-8 mb-5">
                                    <div class="progress">
                                        <div class="progress-bar custom-progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:60%">6/10
                                        </div>
                                    </div>
                                </div>
                                <!--Grid column--> 
                            </div>
                            <!--Grid row-->
                             <!--Grid row-->
                            <div class="row mx-4 justify-content-center">
                                <!--Grid column-->
                                <div class="col-md-2 mb-0">
                                    <h2 class="custom-h2">SQL</h2>
                                </div>
                                <!--Grid column-->
                                <!--Grid column-->
                                <div class="col-md-8 mb-5">
                                    <div class="progress">
                                        <div class="progress-bar custom-progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:50%">5/10
                                        </div>
                                    </div>
                                </div>
                                <!--Grid column--> 
                            </div>
                             <!--Grid row-->
                        </section>
                        <!-- Skills section -->
                    </div> <!-- .container -->
                </div> <!-- #skills-paralax-overlay -->
            </div> <!-- #skills-paralax -->






            <div id= "projects-paralax">
                <div id= "projects-paralax-overlay">
                    <div class="container custom-container-spacing">
                        <!--Projects section v.4-->
                        <section>
                            <!--Section heading-->
                            <h2 class="custom-h1 h1-responsive text-white font-weight-bold text-center my-5">Projects</h2>
                            <!-- Section description -->
                            <p class="custom-text-size text-light text-center w-responsive mx-auto mb-5">MY LATEST WORK. <a class="custom-a" href="./site_pages/projects.php">SEE MORE.</a></p>
                            <!--Grid row-->
                            <div class="row mx-4">
                            <div class="col-md-12 mb-4">
                                <div id="project-card-6" class="h-100 card card-image custom-half-border-cards">
                                    <div class="h-100 text-white justify-content-center text-center d-flex align-items-center general-overlay py-5 px-4 custom-half-border-cards">
                                        <div>
                                            <h6 style="font-size: 1.85vh;"><i class="fab fa-python"></i><strong> Python</strong></h6>
                                            <h3 class="card-title py-3 font-weight-bold" style="font-size: 2.5vh;">Web Server</h3>
                                            <p class="pb-3" style="font-size: 90%;">Web Server created using sockets in python, runs on localhost:8000 by default, and accepts multiple clients. Saves the html file by keeping the directory structure inside a custom created folder called 'static', only if the response was a HTTP 200 OK response; otherwise checks for 404 Not Found, and 400 Bad Request errors.</p>
                                            <a href="./site_pages/projects.php" class="btn btn-success btn-rounded custom-button-color fa-1x"><i class="far fa-clone left"></i> View project</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Grid column-->
                                <!--Grid column-->
                                <div class="col-md-6 mb-4">
                                    <div id="project-card-4" class="h-100 card card-image custom-half-border-cards">
                                        <div class="h-100 text-white justify-content-center text-center d-flex align-items-center general-overlay py-5 px-4 custom-half-border-cards">
                                            <div>    
                                                <h6 style="font-size: 100%;"><i class="fas fa-gem"></i><strong> Ruby</strong></h6>
                                                <h3 class="py-3 font-weight-bold" style="font-size: 145%;">Today's Songs</h3>
                                                <p class="pb-3" style="font-size: 90%;">This CLI executable program provides the user with top songs of the day. It collects the song data from two different sites, lists them to the user, and enables them to listen to these songs by opening the user's preferred song in the chrome browser.</p>
                                                <a href="./site_pages/projects.php" class="btn btn-success btn-rounded custom-button-color fa-1x"><i class="far fa-clone left"></i> View project</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Grid column--> 
                                <!--Grid column-->
                                <div class="col-md-6 mb-4">
                                    <div id="project-card-2" class="card card-image custom-half-border-cards">
                                        <div class="text-white justify-content-center text-center d-flex align-items-center general-overlay py-5 px-4 custom-half-border-cards">
                                            <div>
                                                <h6 style="font-size: 100%;"><i class="fab fa-python"></i><strong> Python</strong></h6>
                                                <h3 class="card-title py-3 font-weight-bold" style="font-size: 145%;">Video and Sound Encoder</h3>
                                                <p class="pb-3" style="font-size: 90%;">This gui program makes bash calls to ffmpeg in order to encode either mov (includes audio encoding from surround to stereo) or dnxhd format video(s), hence condencing their size all the while preserving quality, as well as the directory structure. And this software was made during my time interning for FCB Chicago.</p>
                                                <a href="./site_pages/projects.php" class="btn btn-success btn-rounded fa-1x custom-button-color"><i class="far fa-clone left"></i> View project</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Grid column-->
                            </div>
                            <!--Grid row-->
                        </section>
                        <!--Projects section v.4-->
                    </div> <!-- .container -->
                </div> <!-- #projects-paralax-overlay -->
            </div> <!-- #projects-paralax -->






            <div id= "contact-paralax">
                <div id= "contact-paralax-overlay">
                    <br><br><br><br>
                    <div id="Contact"></div>
                    <div class="container custom-container-spacing">
                        <div class="row mx-0 justify-content-center">
                            <!-- Section: Contact v.3 -->
                            <section class="contact-section text-white custom-small-final-container">           
                                <div class="row justify-content-center">
                                    <!-- <div class="card-body form"> -->
                                    <!-- Header -->
                                    <h3 class="custom-h3 mb-5"><i class="fas fa-envelope"></i>  Contact:</h3>
                                </div> 
                                <!-- Grid row -->
                                <!-- Grid row -->
                                <div class="container">      
                                    <form id="contact-form" method="post" class="form-horizontal recaptchaForm" role="form">
                                        <div class="row justify-content-center mx-4"> 
                                            <!-- Grid column -->
                                            <div class="col-md-12 mx-1 justify-content-center">
                                                <!-- Grid row -->
                                                <div class="row">
                                                    <div class="col-md-6 text-left">
                                                        <div class="form-group mb-4">
                                                            <label for="form-contact-first-name"><i class="fas fa-user"></i> First Name *</label>
                                                            <input name="first_name" type="text" placeholder="Please enter your first name" id="form-contact-first-name" class="form-control custom-input-color" required="required" data-error="First Name is required.">  
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                    <!-- Grid column -->
                                                    <div class="col-md-6 text-left">
                                                        <div class="form-group mb-4">
                                                            <label for="form-contact-last-name"><i class="fas fa-user"></i> Last Name *</label>
                                                            <input name="last_name" type="text" placeholder="Please enter your last name" id="form-contact-last-name" class="form-control custom-input-color" required="required" data-error="Last Name is required.">
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                    <!-- Grid column -->
                                                </div>
                                                <!-- Grid row -->
                                                <div class="row">
                                                    <div class="col-md-6 text-left">
                                                        <div class="form-group mb-4">
                                                            <label for="form-contact-phone"><i class="fas fa-mobile-alt"></i> Phone (optional)</label>
                                                            <input name="phone" type="tel" placeholder="Please enter your phone number" id="form-contact-phone" class="form-control custom-input-color">
                                                        </div>
                                                    </div>
                                                    <!-- Grid column -->
                                                    <div class="col-md-6 text-left">
                                                        <div class="form-group mb-5">
                                                            <label for="form-contact-email"><i class="far fa-envelope"></i> Email *</label>
                                                            <input name="email" type="email" placeholder="Please enter your email address"  id="form-contact-email" class="form-control custom-input-color" required="required" data-error="Valid email is required.">
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                    <!-- Grid column -->
                                                </div>
                                                <!-- Grid row -->
                                                <div class= "row">
                                                    <div class="col-md-12 text-left">
                                                        <div class="form-group mb-4">
                                                            <label for="form-contact-message"><i class="far fa-comment-alt"></i> Message *</label>
                                                            <textarea name="message" id="form-contact-message" placeholder="Please enter your message" class="py-3 form-control form-control-md md-textarea" rows="5" required="required" data-error="Nothing typed in the message box."></textarea>
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                    <!-- Grid column -->
                                                </div>
                                                <!-- Grid row -->
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                    <div class="g-recaptcha" data-sitekey="6Lf0COgUAAAAAJ9rZ2hnn-JinucKScWAxeMbEjh4"></div>
                                                    </div>
                                                    <!-- Grid Column -->
                                                </div></br>
                                                <!-- Grid row -->   
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-4">
                                                            <button name="submit" class="btn btn-success fa-1x custom-send-button-color" type="submit" style="width: 100%;">Send</button>      
                                                        </div>
                                                    </div>
                                                    <!-- Grid column -->
                                                </div>
                                                <!-- Grid row --> 
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-0">   
                                                            <p style="color: rgb(216, 216, 216);">
                                                                <strong>*</strong> <em>These fields are required.</em>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <!-- Grid column -->
                                                </div>
                                                <!-- Grid row -->
                                            </div>
                                            <!-- Grid column -->
                                        </div>
                                        <!-- Grid row -->
                                    </form>
                                </div>
                                <!-- Grid row -->
                            </section>
                            <!-- Section: Contact v.3 -->
                        </div>
                    </div> <!-- .container -->
                    
               




                    <!-- Footer -->
                    <footer class="page-footer center-on-sm-only font-small special-color-dark pt-4">
                    <!-- Footer Elements -->
                        <div class="container">
                            <!-- Social buttons -->
                            <ul class="list-unstyled list-inline text-center">
                                <li class="list-inline-item">
                                    <a href="https://www.linkedin.com/in/danish-n-4a2b7486/" target="_blank" class="btn-floating btn-li mx-1"><i class="fab fa-linkedin-in fa-lg white-text mr-md-5 mr-3 fa-2x hover-color"></i></a>
                                    <a href="https://github.com/danishnaseem05/" target="_blank" class="btn-floating btn-li mx-1"><i class="fab fa-github fa-lg white-text mr-md-5 mr-3 fa-2x hover-color"></i></a>
                                </li>
                            </ul>
                            <!-- Social buttons -->
                        </div>
                        <!-- Footer Elements -->
                        <!-- Copyright -->
                        <div class="footer-copyright center-on-sm-only font-small text-center py-3 dark-color">Copyright &copy; <?php echo date("Y");?> Danish Naseem. All rights reserved.
                        <a rel="license" class="theme-orange" href="http://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/80x15.png"/></a>
                        </div>
                        <!-- Copyright -->
                    </footer>
                </div> <!-- #projects-paralax-overlay -->
            </div> <!-- #projects-paralax -->

        </main>

    </div> <!-- #page-container -->
          
    






    <!-- Optional JavaScript -->
    
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Jquery -->
    <script src="./js/jquery-3.4.1.min.js"></script>

    <!-- My JS -->
    <script src="./site_pages/main.js"></script>    


  </body>

</html>
