<!DOCTYPE html>
<?php
    require 'DBconnection.php';
    $conn = OpenCon();
?>

<html lang="en">


  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Portfolio">
    <meta name="keywords" content="Danish,Naseem,Ruby,Python,HTML,CSS,Bash,Developer,Programmer,Portfolio,Coder,Today's,Songs,todays,today,projects,blog,naseemoid,student,college,Computer,Science,project">
    <meta name="author" content="Danish Naseem">    

    <!-- Google -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145388957-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-145388957-1');
    </script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.1/css/font-awesome.min.css' rel='stylesheet'/>

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="../fontawesome-free-5.9.0-web/fontawesome-free-5.9.0-web/css/all.min.css">
    <link rel="stylesheet" href="../fontawesome-free-5.9.0-web/fontawesome-free-5.9.0-web/css/fontawesome.min.css">
     
     <!-- My CSS -->
    <link href = "style.php" rel = "stylesheet" type = "text/css">
    <link href = "media_queries.css" rel = "stylesheet" type = "text/css">

    <title>Projects | Danish Naseem</title>

    <!-- Website Logo -->
    <link rel = "icon" href = "../pics/Danish Naseem-logo/profile.png" type = "image/x-icon">

  </head>




  <body>
    
    <div id="page-container">

        <!-- Navigation -->
        <header>
            <nav class="navbar navbar-expand-md navbar-dark navbar-custom fixed-top">
                <div class="container">
                    <a class="navbar-brand" href="../index.php"><span class="hover-color">D<span class="theme-orange">/</span>N</span></a>   
                    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="navbar-collapse collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="../index.php">Home</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="../index.php#Skills">Skills</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link active" href="./projects.php">Projects</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="../index.php#Resume">Resume</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="../index.php#Contact">Contact</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="./games.php">Games</a>
                            </li>
                            <li class="custom-nav-item nav-item">
                                <a class="nav-link" href="https://blog.danishnaseemoid.com/">&nbsp;Blog&nbsp;</a>
                            </li>
                        </ul>
                    </div><!-- .navbar-toggler .collapsed .show-->
                </div><!-- .container -->   
            </nav>
        </header>

        <!-- Main Content -->
        <main>

            <div id="projects-page-paralax">
                <div id="projects-page-paralax-overlay">
                    <br><br><br><br><br>
                    <div class="container-fluid mt-5">          
                        <!--Grid row-->
                        <div class="row mx-0">
                            <?php
                                if ($result = $conn->query("SELECT * FROM project_cards;")) {
                                    while($obj = $result->fetch_object()){
                                        echo <<<EOL
                                        <div class="col-md-6 mb-4">
                                            <div id="project-card-{$obj->id}" class="h-100 card card-image custom-half-border-cards">
                                                <div class="h-100 text-white justify-content-center text-center d-flex align-items-center general-overlay py-5 px-4 custom-half-border-cards">
                                                    <div>
                                                        <h6 style="font-size: 1.85vh;"><i class="{$obj->language_class}"></i><strong> {$obj->language}</strong></h6>
                                                        <h3 class="card-title py-3 font-weight-bold" style="font-size: 2.5vh;">{$obj->card_title}</h3>
                                                        <p class="pb-3" style="font-size: 90%;">{$obj->card_description}</p>
                                                        <button id="project{$obj->id}-btn" class="btn btn-success btn-rounded custom-button-color fa-1x" onclick="on({$obj->id})"><i class="far fa-clone left"></i> View Project</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--Grid column-->
                                        EOL;
                                    }
                                }
                            ?>
                        </div>
                        <!--Grid row-->
                    </div> <!-- .container-fluid -->
                    <br><br><br><br><br>


                   
                    <?php
                        if($result = $conn->query("SELECT * FROM project_modals;")) {
                            while($obj = $result->fetch_object()){
                                echo <<<EOL
                                <div id="project{$obj->card_id}-modal" class="custom-modal">
                                    <div class="custom-modal-content">
                                        <button class=" btn Close" onclick="off()">&times;</button>
                                        <div class="container-fluid justify-content-center">
                                            <div class="row>
                                                <div class="col-sm-12">
                                                    <div id="project{$obj->card_id}-carousel" class="carousel slide" data-interval="5000" data-keyboard="true" data-pause="hover" data-ride="carousel">
                                                        <ol class="carousel-indicator">
                                                            {for($i=0; i<count($obj->modal_slides); $i++){
                                                                '<li data-target="#project{$obj->card_id}-carousel" data-slide-to="{$i}" class="active"></li>';
                                                            }}
                                                        </ol>
                                                        <div class="carousel-inner">
                                                            {for($i=0; i<count($obj->modal_slides); $i++){
                                                                '<div class="carousel-item active">
                                                                    <img class="d-block w-100" src="{$obj->modal_slides[$i][0]}" alt="{$obj->modal_slides[$i][1]}" style="width:100%">
                                                                    <div class="carousel-caption d-none d-sm-block">
                                                                        <p class="custom-carousel-p">{$obj->modal_slides[$i][2]}</p>
                                                                    </div>
                                                                </div>';
                                                            }}
                                                        </div>
                                                        <a class="carousel-control-prev custom-carousel-prev" href="#project{$obj->card_id}-carousel" role="button" data-slide="prev">
                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next custom-carousel-next" href="#project{$obj->card_id}-carousel" role="button" data-slide="next">
                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="custom-modal-button">
                                                                <a href="{$obj->github_url}" target="_blank" type="button" class="btn custom-modal-button">Github repo</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                EOL;
                            }
                        }
                    ?>

                   <!-- Project 1 Modal Carousel -->
                   <!-- NEW PROJECT ID BELOW -->
                    <div id="project1-modal" class="custom-modal">
                        <div class="custom-modal-content">
                            <button class=" btn Close" onclick="off()">&times;</button>
                            <div class="container-fluid justify-content-center">
                                <div class="row"> 
                                    <div class="col-sm-12">
                                        <!-- NEW PROJECT ID BELOW -->
                                        <div id="project1-carousel" class="carousel slide" data-interval="5000" data-keyboard="true" data-pause="hover" data-ride="carousel">
                                            <!-- ADD A LIST ITEM FOR EACH PHOTO -->
                                            <ol class="carousel-indicators">
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project1-carousel" data-slide-to="0" class="active"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project1-carousel" data-slide-to="1"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project1-carousel"  data-slide-to="2"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project1-carousel" data-slide-to="3"></li>
                                            </ol>
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100" src="../pics/project_cards/Todays_songs/Capture.PNG" alt="First slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Lists the songs to the user from two different sites, and prompts them for selection</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Todays_songs/Capture2.PNG" alt="Second slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Opens the selected song number in Chrome browser.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Todays_songs/Capture3.PNG" alt="Third slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Opens another song selection (after prompting the user whether they want to listen to any other song, and their response being yes) in YouTube.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Todays_songs/Capture4.PNG" alt="Fourth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">The program exits with a goodbye response, after the user is done listening to songs.</p>
                                                    </div>
                                                </div>
                                            </div><!-- .caousel-inner -->
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-prev custom-carousel-prev" href="#project1-carousel" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-next custom-carousel-next" href="#project1-carousel" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div><!-- #project1-carousel -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="custom-modal-button">
                                                    <a href="https://github.com/danishnaseem05/todays_songs" target="_blank" type="button" class="btn custom-modal-button">Github repo</a>
                                                </div><!-- .custom-modal-button -->
                                            </div><!-- .col-md-12 -->
                                        </div><!-- .row -->
                                    </div><!-- .col-sm-9 -->
                                </div><!-- .row -->
                            </div><!-- .container-fluid -->
                        </div><!-- .custom-modal-content -->
                    </div><!-- #project1-modal .modal -->

                    <!-- Project 2 Modal Carousel -->
                    <!-- NEW PROJECT ID BELOW -->
                    <div id="project2-modal" class="custom-modal">
                        <div class="custom-modal-content">
                            <button class=" btn Close" onclick="off()">&times;</button>
                            <div class="container-fluid justify-content-center">
                                <div class="row"> 
                                    <div class="col-sm-12">
                                        <!-- NEW PROJECT ID BELOW -->
                                        <div id="project2-carousel" class="carousel slide" data-interval="5000" data-keyboard="true" data-pause="hover" data-ride="carousel">
                                            <!-- ADD A LIST ITEM FOR EACH PHOTO -->
                                            <ol class="carousel-indicators">
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project2-carousel" data-slide-to="0" class="active"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project2-carousel" data-slide-to="1"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project2-carousel"  data-slide-to="2"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project2-carousel" data-slide-to="3"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project2-carousel" data-slide-to="4"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project2-carousel" data-slide-to="5"></li>
                                            </ol>
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100" src="../pics/project_cards/Wave_worm/Capture.PNG" alt="First slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">The main menu screen; having mouse event listeners for the user to easily navigate through with a mouse/touchpad.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Wave_worm/Capture2.PNG" alt="Second slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">The help menu, which is pretty self-explanatory</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Wave_worm/Capture3.PNG" alt="Third slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">After pressing Play, the user gets prompt to select a difficulty mode. The Hard mode increments the number of enemies as well as introduces a new one.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Wave_worm/Capture8.PNG" alt="Fourth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Gameplay in Hard Mode. The Player being the white square, and the others being different species of enemies, including the green smart enemy, which follows the player.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Wave_worm/Capture6.PNG" alt="Fifth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">The final level of the game, where the user comes face to face with the big boss, and his minions.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Wave_worm/Capture7.PNG" alt="Sixth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Like any other 2D game, if your health becomes zero, you loose, and hence comes the game over screen.</p>
                                                    </div>
                                                </div>
                                            </div><!-- .caousel-inner -->
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-prev custom-carousel-prev" href="#project2-carousel" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-next custom-carousel-next" href="#project2-carousel" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div><!-- #project2-carousel -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="custom-modal-button">
                                                    <a href="https://github.com/danishnaseem05/Wave-Worm-Java-Game" target="_blank" type="button" class="btn custom-modal-button">Github repo</a>
                                                </div><!-- .custom-modal-button -->
                                            </div><!-- .col-md-12 -->
                                        </div><!-- .row -->
                                    </div><!-- .col-sm-9 -->
                                </div><!-- .row -->
                            </div><!-- .container-fluid -->
                        </div><!-- .custom-modal-content -->
                    </div><!-- #project2-modal .modal -->

                    <!-- Project 3 Modal Carousel -->
                    <!-- NEW PROJECT ID BELOW -->
                    <div id="project3-modal" class="custom-modal">
                        <div class="custom-modal-content">
                            <button class=" btn Close" onclick="off()">&times;</button>
                            <div class="container-fluid justify-content-center">
                                <div class="row"> 
                                    <div class="col-sm-12">
                                        <!-- NEW PROJECT ID BELOW -->
                                        <div id="project3-carousel" class="carousel slide" data-interval="5000" data-keyboard="true" data-pause="hover" data-ride="carousel">
                                            <!-- ADD A LIST ITEM FOR EACH PHOTO -->
                                            <ol class="carousel-indicators">
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project3-carousel" data-slide-to="0" class="active"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project3-carousel" data-slide-to="1"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project3-carousel"  data-slide-to="2"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project3-carousel" data-slide-to="3"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project3-carousel" data-slide-to="4"></li>
                                            </ol>
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100" src="../pics/project_cards/Video_and_Sound_Encoder/Capture.PNG" alt="First slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">The main menu of the GUI, giving the user the options of picking from either MOV or DNXHD Encoding.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Video_and_Sound_Encoder/Capture2.PNG" alt="Second slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">After selection, it prompts the user to select the input directory containing all the video files. Even if the directory contains any other files, the program would simply just ignore them, and go for the required files.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Video_and_Sound_Encoder/Capture3.PNG" alt="Third slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Warns the user in case of accidental cancellation.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Video_and_Sound_Encoder/Capture4.PNG" alt="Fourth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Well, this one I thought would be funny if the user were to accidentally hit cancel and then click on 'No' that they do not wish to exit the program. The program would then respond with a random goofy message.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Video_and_Sound_Encoder/Capture5.PNG" alt="Fifth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">After the user stops goofing around, *pun intended* the program runs the main ffmpeg conversion using the bash command pipelined from within python</p>
                                                    </div>
                                                </div>
                                            </div><!-- .caousel-inner -->
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-prev custom-carousel-prev" href="#project3-carousel" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-next custom-carousel-next" href="#project3-carousel" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div><!-- #project3-carousel -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="custom-modal-button">
                                                    <a href="https://github.com/danishnaseem05/Video-and-Sound-Encoder-made-at-FCB" target="_blank" type="button" class="btn custom-modal-button">Github repo</a>
                                                </div><!-- .custom-modal-button -->
                                            </div><!-- .col-md-12 -->
                                        </div><!-- .row -->
                                    </div><!-- .col-sm-9 -->
                                </div><!-- .row -->
                            </div><!-- .container-fluid -->
                        </div><!-- .custom-modal-content -->
                    </div><!-- #project3-modal .modal -->

                    <!-- Project 4 Modal Carousel -->
                    <!-- NEW PROJECT ID BELOW -->
                    <div id="project4-modal" class="custom-modal">
                        <div class="custom-modal-content">
                            <button class=" btn Close" onclick="off()">&times;</button>
                            <div class="container-fluid justify-content-center">
                                <div class="row"> 
                                    <div class="col-sm-12">
                                        <!-- NEW PROJECT ID BELOW -->
                                        <div id="project4-carousel" class="carousel slide" data-interval="5000" data-keyboard="true" data-pause="hover" data-ride="carousel">
                                            <!-- ADD A LIST ITEM FOR EACH PHOTO -->
                                            <ol class="carousel-indicators">
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project4-carousel" data-slide-to="0" class="active"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project4-carousel" data-slide-to="1"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project4-carousel"  data-slide-to="2"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project4-carousel" data-slide-to="3"></li>
                                            </ol>
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100" src="../pics/project_cards/Tic_Tac_Toe/Capture.PNG" alt="First slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">After execution, it greets and then prompts the user for the type of game they'd wish to play.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Tic_Tac_Toe/Capture2.PNG" alt="Second slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Here I've chosen a one player game. The game launches with a blank board and is waiting on me to make a move.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Tic_Tac_Toe/Capture3.PNG" alt="Third slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">A few turns into the game of me vs the computer.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Tic_Tac_Toe/Capture6.PNG" alt="Fourth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">The game ends up in being a draw. It also congragulates the winner with their token name (X or O) if either of the players have won.</p>
                                                    </div>
                                                </div>
                                            </div><!-- .caousel-inner -->
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-prev custom-carousel-prev" href="#project4-carousel" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-next custom-carousel-next" href="#project4-carousel" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div><!-- #project4-carousel -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="custom-modal-button">
                                                    <a href="https://github.com/danishnaseem05/CLI-Tic-Tac-Toe-Ruby" target="_blank" type="button" class="btn custom-modal-button">Github repo</a>
                                                </div><!-- .custom-modal-button -->
                                            </div><!-- .col-md-12 -->
                                        </div><!-- .row -->
                                    </div><!-- .col-sm-9 -->
                                </div><!-- .row -->
                            </div><!-- .container-fluid -->
                        </div><!-- .custom-modal-content -->
                    </div><!-- #project4-modal .modal -->

                    <!-- Project 5 Modal Carousel -->
                    <!-- NEW PROJECT ID BELOW -->
                    <div id="project5-modal" class="custom-modal">
                        <div class="custom-modal-content">
                            <button class=" btn Close" onclick="off()">&times;</button>
                            <div class="container-fluid justify-content-center">
                                <div class="row"> 
                                    <div class="col-sm-12">
                                        <!-- NEW PROJECT ID BELOW -->
                                        <div id="project5-carousel" class="carousel slide" data-interval="5000" data-keyboard="true" data-pause="hover" data-ride="carousel">
                                            <!-- ADD A LIST ITEM FOR EACH PHOTO -->
                                            <ol class="carousel-indicators">
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="0" class="active"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="1"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel"  data-slide-to="2"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="3"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="4"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="5"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="6"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="7"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="8"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="9"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="10"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project5-carousel" data-slide-to="11"></li>
                                            </ol>
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture.PNG" alt="First slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">After launching the app.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture2.PNG" alt="Second slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Clicked on Display All on without adding first.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture3.PNG" alt="Third slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Entering number 1500 into the input entry</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture4.PNG" alt="Fourth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Confirmation Log of number 1500 being added to the list.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture5.PNG" alt="Fifth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">After adding a few items to the list.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture6.PNG" alt="Sixth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">After clicking Display All to display all the integers currently stored in the list.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture7.PNG" alt="Seventh slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Trying to add or delete without entering a number in the entry box.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture8.PNG" alt="Eight slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Entered a non-integer value.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture9.PNG" alt="Ninth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Deleted number 2, 43, and 754 from the list.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture10.PNG" alt="Tenth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Displaying list after deleting the numbers.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture11.PNG" alt="Eleventh slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Trying to add an already existant number in the list.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Doubly_Linked_Sorted_List/Capture12.PNG" alt="Twelveth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">After the removing the last item from the list.</p>
                                                    </div>
                                                </div>
                                            </div><!-- .caousel-inner -->
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-prev custom-carousel-prev" href="#project5-carousel" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-next custom-carousel-next" href="#project5-carousel" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div><!-- #project5-carousel -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="custom-modal-button">
                                                    <a href="https://github.com/danishnaseem05/Doubly-Linked-Sorted-List" target="_blank" type="button" class="btn custom-modal-button">Github repo</a>
                                                </div><!-- .custom-modal-button -->
                                            </div><!-- .col-md-12 -->
                                        </div><!-- .row -->
                                    </div><!-- .col-sm-9 -->
                                </div><!-- .row -->
                            </div><!-- .container-fluid -->
                        </div><!-- .custom-modal-content -->
                    </div><!-- #project5-modal .modal -->

                    <!-- Project 4 Modal Carousel -->
                    <!-- NEW PROJECT ID BELOW -->
                    <div id="project6-modal" class="custom-modal">
                        <div class="custom-modal-content">
                            <button class=" btn Close" onclick="off()">&times;</button>
                            <div class="container-fluid justify-content-center">
                                <div class="row"> 
                                    <div class="col-sm-12">
                                        <!-- NEW PROJECT ID BELOW -->
                                        <div id="project6-carousel" class="carousel slide" data-interval="5000" data-keyboard="true" data-pause="hover" data-ride="carousel">
                                            <!-- ADD A LIST ITEM FOR EACH PHOTO -->
                                            <ol class="carousel-indicators">
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project6-carousel" data-slide-to="0" class="active"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project6-carousel" data-slide-to="1"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project6-carousel"  data-slide-to="2"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project6-carousel" data-slide-to="3"></li>
                                                <!-- NEW PROJECT ID BELOW -->
                                                <li data-target="#project6-carousel" data-slide-to="4"></li>
                                            </ol>
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100" src="../pics/project_cards/Web_Server/Capture.PNG" alt="First slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Started the server on linux in windows.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Web_Server/Capture2.PNG" alt="Second slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Ran localhost:8000/tests/html/cars/ford.html in Google Chrome.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Web_Server/Capture3.PNG" alt="Third slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Created and saved ford.html inside static/tests/html/cars/ford.html</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Web_Server/Capture4.PNG" alt="Fourth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">ford.html file saved inside the path.</p>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block w-100" src="../pics/project_cards/Web_Server/Capture5.PNG" alt="Fifth slide" style="width:100%">
                                                    <div class="carousel-caption d-none d-sm-block">
                                                        <p class="custom-carousel-p">Connected two clients (Microsoft Edge and Google Chrome) to the Web Server. Runing localhost:8000/tests/html/cars/ford.html in Chrome, and localhost:8000/tests/html/index.html in Edge.</p>
                                                    </div>
                                                </div>
                                            </div><!-- .caousel-inner -->
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-prev custom-carousel-prev" href="#project6-carousel" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <!-- NEW PROJECT ID BELOW -->
                                            <a class="carousel-control-next custom-carousel-next" href="#project6-carousel" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div><!-- #project6-carousel -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="custom-modal-button">
                                                    <a href="https://github.com/danishnaseem05/WebServer" target="_blank" type="button" class="btn custom-modal-button">Github repo</a>
                                                </div><!-- .custom-modal-button -->
                                            </div><!-- .col-md-12 -->
                                        </div><!-- .row -->
                                    </div><!-- .col-sm-9 -->
                                </div><!-- .row -->
                            </div><!-- .container-fluid -->
                        </div><!-- .custom-modal-content -->
                    </div><!-- #project6-modal .modal -->

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
                </div> <!-- #projects-page-paralax-overlay -->
            </div> <!-- #projects-page-paralax -->

        </main>

    </div> <!-- #page-container -->
    









    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->


    <!-- My JS -->
    <script src="./main.js"></script> 


  </body>

</html>

<?php
CloseCon($conn);
?>
