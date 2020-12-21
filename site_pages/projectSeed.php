<?php

function projectCardsArr(){
// NOTICE: To add more projects, add from the top, incrementing the id by a 1.
// For example: if latest project id is 6, add a new project with id 7 right before it.

// N = 1,2,3,4,5,6,....
// project_cards[N][0] = language
// project_cards[N][1] = language_class
// project_cards[N][2] = card_title
// project_cards[N][3] = card_description
// project_cards[N][4] = card_cover
// project_cards[N][5] = array of arrays(modal_paths,modal_alts,modal_descriptions)
// project_cards[N][6] = github_url
    $project_cards = array(
                    6=> array('Python', 'fab fa-python', 'Web Server', 
                            'Web Server created using sockets in python, runs on localhost:8000 by default, and accepts multiple clients. Saves the html file by keeping the directory structure inside a custom created folder called \'static\', only if the response was a HTTP 200 OK response; otherwise checks for 404 Not Found, and 400 Bad Request errors.', 
                            '../pics/project_cards/Web_Server/Capture5.PNG', 
                            array(array('../pics/project_cards/Web_Server/Capture.PNG','First slide','Started the server on linux in windows.'),
                                array('../pics/project_cards/Web_Server/Capture2.PNG','Second slide','Ran localhost:8000/tests/html/cars/ford.html in Google Chrome.'),
                                array('../pics/project_cards/Web_Server/Capture3.PNG','Third slide','Created and saved ford.html inside static/tests/html/cars/ford.html'),
                                array('../pics/project_cards/Web_Server/Capture4.PNG','Fourth slide','ford.html file saved inside the path.'),
                                array('../pics/project_cards/Web_Server/Capture5.PNG','Fifth slide','Connected two clients (Microsoft Edge and Google Chrome) to the Web Server. Runing localhost:8000/tests/html/cars/ford.html in Chrome, and localhost:8000/tests/html/index.html in Edge.')
                            ),
                            'https://github.com/danishnaseem05/WebServer'    
                            ),
                    
                    5=> array('Java', 'fab fa-java', 'Sorted Doubly Linked List', 
                            'This is the GUI representation of my customly implemented Doubly Linked List. This List is Sorted and only allows unique integer values. Furthermore, the GUI is easily navigable and its purpose is to Add an unique integer while keeping the list sorted, Delete an integer if it exists in the list, and Display All integers currently stored within the list. Adding to and displaying an empty list, deleting an unstored integer, or entering a non-integer value; all would display errors.', 
                            '../pics/project_cards/Doubly_Linked_Sorted_List/Capture5.PNG',
                            array(array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture.PNG','First slide','After launching the app.'),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture2.PNG','Second slide','Clicked on Display All on without adding first.'),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture3.PNG','Third slide','Entering number 1500 into the input entry.'),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture4.PNG','Fourth slide','Confirmation Log of number 1500 being added to the list.'),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture5.PNG','Fifth slide','After adding a few items to the list.'),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture6.PNG','Sixth slide','After clicking Display All to display all the integers currently stored in the list.'),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture7.PNG','Seventh slide','Trying to add or delete without entering a number in the entry box.'),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture8.PNG','Eight slide','Entered a non-integer value.'),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture9.PNG','Ninth slide','Deleted number 2, 43, and 754 from the list.'),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture10.PNG','Tenth slide','Displaying list after deleting the numbers.',),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture11.PNG','Eleventh slide','Trying to add an already existant number in the list.'),
                                array('../pics/project_cards/Doubly_Linked_Sorted_List/Capture12.PNG','Twelveth slide','After the removing the last item from the list.')
                                ),
                            'https://github.com/danishnaseem05/Doubly-Linked-Sorted-List'
                    ),
                    
                    4=> array('Ruby', 'fas fa-gem', 'Today\'s Songs', 
                            'This CLI executable program provides the user with top songs of the day. It collects the song data from two different sites, lists them to the user, and enables them to listen to these songs by opening the user\'s preferred song in the chrome browser.', 
                            '../pics/project_cards/Todays_songs/Capture2.PNG',
                            array(array('../pics/project_cards/Todays_songs/Capture.PNG','First slide','Lists the songs to the user from two different sites, and prompts them for selection'),
                                array('../pics/project_cards/Todays_songs/Capture2.PNG','Second slide','Opens the selected song number in Chrome browser.'),
                                array('../pics/project_cards/Todays_songs/Capture3.PNG','Third slide','Opens another song selection (after prompting the user whether they want to listen to any other song, and their response being yes) in YouTube.'),
                                array('../pics/project_cards/Todays_songs/Capture4.PNG','Fourth slide','The program exits with a goodbye response, after the user is done listening to songs.')
                            ),
                            'https://github.com/danishnaseem05/todays_songs'
                    ),
                    
                    3=> array('Java', 'fab fa-java', 'Wave Worm', 
                            'Wave Worm is a single player 2D Java game. The user tackles the various worms using keyboard keys, and makes his/her way across levels, while maintaining a top score.', 
                            '../pics/project_cards/Wave_worm/Capture8.PNG',
                            array(array('../pics/project_cards/Wave_worm/Capture.PNG','First slide','The main menu screen; having mouse event listeners for the user to easily navigate through with a mouse/touchpad.'),
                                array('../pics/project_cards/Wave_worm/Capture2.PNG','Second slide','The help menu, which is pretty self-explanatory'),
                                array('../pics/project_cards/Wave_worm/Capture3.PNG','Third slide','After pressing Play, the user gets prompt to select a difficulty mode. The Hard mode increments the number of enemies as well as introduces a new one.'),
                                array('../pics/project_cards/Wave_worm/Capture8.PNG','Fourth slide','Gameplay in Hard Mode. The Player being the white square, and the others being different species of enemies, including the green smart enemy, which follows the player.'),
                                array('../pics/project_cards/Wave_worm/Capture6.PNG','Fifth slide','The final level of the game, where the user comes face to face with the big boss, and his minions.'),
                                array('../pics/project_cards/Wave_worm/Capture7.PNG','Sixth slide','Like any other 2D game, if your health becomes zero, you loose, and hence comes the game over screen.')
                            ),
                            'https://github.com/danishnaseem05/Wave-Worm-Java-Game'
                        ),
                    
                    2=> array('Python', 'fab fa-python', 'Video and Sound Encoder', 
                            'This gui program makes bash calls to ffmpeg in order to encode either mov (includes audio encoding from surround to stereo) or dnxhd format video(s), hence condensing their size all the while preserving quality, as well as the directory structure. And this software was made during my time interning for FCB Chicago.', 
                            '../pics/project_cards/Video_and_Sound_Encoder/Capture5.PNG',
                            array(array('../pics/project_cards/Video_and_Sound_Encoder/Capture.PNG','First slide','The main menu of the GUI, giving the user the options of picking from either MOV or DNXHD Encoding.'),
                                array('../pics/project_cards/Video_and_Sound_Encoder/Capture2.PNG','Second slide','After selection, it prompts the user to select the input directory containing all the video files. Even if the directory contains any other files, the program would simply just ignore them, and go for the required files.'),
                                array('../pics/project_cards/Video_and_Sound_Encoder/Capture3.PNG','Third slide','Warns the user in case of accidental cancellation.'),
                                array('../pics/project_cards/Video_and_Sound_Encoder/Capture4.PNG','Fourth slide','Well, this one I thought would be funny if the user were to accidentally hit cancel and then click on \'No\' that they do not wish to exit the program. The program would then respond with a random goofy message.'),
                                array('../pics/project_cards/Video_and_Sound_Encoder/Capture5.PNG','Fifth slide','After the user stops goofing around, *pun intended* the program runs the main ffmpeg conversion using the bash command pipelined from within python')
                            ),
                            'https://github.com/danishnaseem05/Video-and-Sound-Encoder-made-at-FCB'
                        ),
                    
                    1=> array('Ruby', 'fas fa-gem', 'CLI Tic Tac Toe', 
                            'This is a CLI version of Tic Tac Toe, providing the user with single player, double player, and even zero player (meaning the player gets to watch computer vs computer) experience.', 
                            '../pics/project_cards/Tic_Tac_Toe/Capture5.PNG',
                            array(array('../pics/project_cards/Tic_Tac_Toe/Capture.PNG','First slide','After execution, it greets and then prompts the user for the type of game they\'d wish to play.'),
                                array('../pics/project_cards/Tic_Tac_Toe/Capture2.PNG','Second slide','Here I\'ve chosen a one player game. The game launches with a blank board and is waiting on me to make a move.'),
                                array('../pics/project_cards/Tic_Tac_Toe/Capture3.PNG','Third slide','A few turns into the game of me vs the computer.'),
                                array('../pics/project_cards/Tic_Tac_Toe/Capture6.PNG','Fourth slide','The game ends up in being a draw. It also congragulates the winner with their token name (X or O) if either of the players have won.')
                            ),
                            'https://github.com/danishnaseem05/CLI-Tic-Tac-Toe-Ruby'
                        )     
    );
    return $project_cards;
} 

?>