<?php
    require 'projectSeed.php';
    $project_cards = projectCardsArr();
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
                                // GENERATING PROJECT CARDS
                                foreach($project_cards as $id=>$value){
                                    echo <<<EOL
                                    <div class="col-md-6 mb-4">
                                        <div id="project-card-{$id}" class="h-100 card card-image custom-half-border-cards">
                                            <div class="h-100 text-white justify-content-center text-center d-flex align-items-center general-overlay py-5 px-4 custom-half-border-cards">
                                                <div>
                                                    <h6 style="font-size: 1.85vh;"><i class="{$value[1]}"></i><strong> {$value[0]}</strong></h6>
                                                    <h3 class="card-title py-3 font-weight-bold" style="font-size: 2.5vh;">{$value[2]}</h3>
                                                    <p class="pb-3" style="font-size: 90%;">{$value[3]}</p>
                                                    <button id="project{$id}-btn" class="btn btn-success btn-rounded custom-button-color fa-1x" onclick="on({$id})"><i class="far fa-clone left"></i> View Project</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Grid column-->
                                    EOL;
                                }
                            ?>
                        </div>
                        <!--Grid row-->
                    </div> <!-- .container-fluid -->
                    <br><br><br><br><br>
                

                    <div id="project-modals">
                    <?php
                    // GENERATING PROJECT SLIDESHOWS
                    foreach($project_cards as $id=>$value){
                        echo    '<div id="project'.$id.'-modal" class="custom-modal">';
                        echo        '<div class="custom-modal-content">';
                        echo            '<button class=" btn Close" onclick="off()">&times;</button>';
                        echo            '<div class="container-fluid justify-content-center">';
                        echo                '<div class="row">';
                        echo                    '<div class="col-sm-12">';
                        echo                        '<div id="project'.$id.'-carousel" class="carousel slide" data-interval="2500" data-keyboard="true" data-pause="hover" data-ride="carousel">';
                        echo                            '<ol class="carousel-indicators">';
                                                        for($i=0; $i<count($value[5]); $i++){
                                                            if ($i==0){
                                                                echo '<li data-target="#project'.$id.'-carousel" data-slide-to="'.$i.'" class="active"></li>';
                                                            }else{
                                                                echo '<li data-target="#project'.$id.'-carousel" data-slide-to="'.$i.'"></li>';
                                                            }
                                                        }
                        echo                            '</ol>';
                        echo                            '<div class="carousel-inner">';
                                                        for($i=0; $i<count($value[5]); $i++){
                                                            if($i==0){
                                                                echo '<div class="carousel-item active">';
                                                            } else{
                                                                echo '<div class="carousel-item">';
                                                            }
                                                                echo '<img class="d-block w-100" src="'.$value[5][$i][0].'" alt="'.$value[5][$i][1].'" style="width:100%">';
                                                                echo '<div class="carousel-caption d-none d-sm-block">';
                                                                    echo '<p class="custom-carousel-p">'.$value[5][$i][2].'</p>';
                                                                echo '</div>';
                                                            echo '</div>';
                                                        }
                        echo                            '</div>';
                        echo                            '<a class="carousel-control-prev custom-carousel-prev" href="#project'.$id.'-carousel" role="button" data-slide="prev">';
                        echo                                '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                        echo                                '<span class="sr-only">Previous</span>';
                        echo                            '</a>';
                        echo                            '<a class="carousel-control-next custom-carousel-next" href="#project'.$id.'-carousel" role="button" data-slide="next">';
                        echo                                '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                        echo                                '<span class="sr-only">Next</span>';
                        echo                            '</a>';
                        echo                        '</div>';
                        echo                        '<div class="row">';
                        echo                            '<div class="col-md-12">';
                        echo                                '<div class="custom-modal-button">';
                        echo                                    '<a href="'.$value[6].'" target="_blank" type="button" class="btn custom-modal-button">Github repo</a>';
                        echo                                '</div>';
                        echo                            '</div>';
                        echo                        '</div>';
                        echo                    '</div>';
                        echo                '</div>';
                        echo            '</div>';
                        echo        '</div>';
                        echo    '</div>';
                        
                    }
                    ?> 
                    </div>     
                



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