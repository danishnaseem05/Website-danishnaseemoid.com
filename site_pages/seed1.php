<?php

// N = 1,2,3,4,5,6,....
// project_cards.N[0] = language
// project_cards.N[1] = language_class
// project_cards.N[2] = card_title
// project_cards.N[3] = card_description
// project_cards.N[4] = card_cover
// project_cards.N[5] = array of modal_paths
// project_cards.N[6] = array of modal_alts
// project_cards.N[7] = array of modal_descriptions
// project_cards.N[8] = github_url
$project_cards = {
                1: array('Python', 'fab fa-python', 'Web Server', 
                        'Web Server created using sockets in python, runs on localhost:8000 by default, and accepts multiple clients. Saves the html file by keeping the directory structure inside a custom created folder called \'static\', only if the response was a HTTP 200 OK response; otherwise checks for 404 Not Found, and 400 Bad Request errors.', 
                        '../pics/project_cards/Web_Server/Capture5.PNG', 
                        array('../pics/project_cards/Web_Server/Capture.PNG',
                            '../pics/project_cards/Web_Server/Capture2.PNG',
                            '../pics/project_cards/Web_Server/Capture3.PNG',
                            '../pics/project_cards/Web_Server/Capture4.PNG',
                            '../pics/project_cards/Web_Server/Capture5.PNG'),
                        array('First slide','Second slide','Third slide','Fourth slide','Fifth slide'),
                        array('Started the server on linux in windows.',
                            'Ran localhost:8000/tests/html/cars/ford.html in Google Chrome.',
                            'Created and saved ford.html inside static/tests/html/cars/ford.html',
                            'ford.html file saved inside the path.',
                            'Connected two clients (Microsoft Edge and Google Chrome) to the Web Server. Runing localhost:8000/tests/html/cars/ford.html in Chrome, and localhost:8000/tests/html/index.html in Edge.'),
                        'https://github.com/danishnaseem05/WebServer'    
                        ),
                
                2: array('Java', 'fab fa-java', 'Sorted Doubly Linked List', 
                'This is the GUI representation of my customly implemented Doubly Linked List. This List is Sorted and only allows unique integer values. Furthermore, the GUI is easily navigable and its purpose is to Add an unique integer while keeping the list sorted, Delete an integer if it exists in the list, and Display All integers currently stored within the list. Adding to and displaying an empty list, deleting an unstored integer, or entering a non-integer value; all would display errors.', 
                '../pics/project_cards/Doubly_Linked_Sorted_List/Capture5.PNG'
                ),
                
                3: array('Ruby', 'fas fa-gem', 'Today\'s Songs', 'This CLI executable program provides the user with top songs of the day. It collects the song data from two different sites, lists them to the user, and enables them to listen to these songs by opening the user\'s preferred song in the chrome browser.', '../pics/project_cards/Todays_songs/Capture2.PNG'),
                
                4: array('Java', 'fab fa-java', 'Wave Worm', 'Wave Worm is a single player 2D Java game. The user tackles the various worms using keyboard keys, and makes his/her way across levels, while maintaining a top score.', '../pics/project_cards/Wave_worm/Capture8.PNG'),
                
                5: array('Python', 'fab fa-python', 'Video and Sound Encoder', 'This gui program makes bash calls to ffmpeg in order to encode either mov (includes audio encoding from surround to stereo) or dnxhd format video(s), hence condensing their size all the while preserving quality, as well as the directory structure. And this software was made during my time interning for FCB Chicago.', '../pics/project_cards/Video_and_Sound_Encoder/Capture5.PNG'),
                
                6: array('Ruby', 'fas fa-gem', 'CLI Tic Tac Toe', 'This is a CLI version of Tic Tac Toe, providing the user with single player, double player, and even zero player (meaning the player gets to watch computer vs computer) experience.', '../pics/project_cards/Tic_Tac_Toe/Capture5.PNG')     
};


?>