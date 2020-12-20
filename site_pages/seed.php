<?php
    require 'DBconnection.php';

    $conn = OpenCon(); // function is from DBconnection.php

    $sql = "CREATE TABLE IF NOT EXISTS project_cards(
            `card_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `language` VARCHAR(45) NOT NULL,
            `language_class` VARCHAR(45) NOT NULL,
            `card_title` VARCHAR(45) NOT NULL,
            `card_description` VARCHAR(1024) NOT NULL,
            `cover` VARCHAR(1024) NOT NULL
        );";
    $conn->query($sql);

    $sql = "CREATE TABLE IF NOT EXISTS project_modals(
        `modal_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `card_id` INT UNSIGNED NOT NULL,
        `modal_path` VARCHAR(512) NOT NULL,
        `modal_alt` VARCHAR(45) NOT NULL,
        `modal_description` VARCHAR(1024) NOT NULL,
        `github_url` VARCHAR(512) NOT NULL,
        INDEX `idx_card_id` (`card_id`),
        CONSTRAINT `fk_character_card_id`
        FOREIGN KEY (`card_id`)
        REFERENCES `project_cards`(`card_id`)
    );";

    $conn->query($sql);

    $sql="";
    $run_query = false; // if atleast one record is to be inserted in the database, turns true
    
    // The format below should be: 
    // project_cards[0..n][0] = language
    // project_cards[0..n][1] = language_class
    // project_cards[0..n][2] = card_title
    // project_cards[0..n][3] = card_description
    // project_cards[0..n][4] = cover
    // project_cards[0..n][5] = github_url 
    $project_cards = array(array('Python', 'fab fa-python', 'Web Server', 'Web Server created using sockets in python, runs on localhost:8000 by default, and accepts multiple clients. Saves the html file by keeping the directory structure inside a custom created folder called \'static\', only if the response was a HTTP 200 OK response; otherwise checks for 404 Not Found, and 400 Bad Request errors.', '../pics/project_cards/Web_Server/Capture5.PNG'),
                        array('Java', 'fab fa-java', 'Sorted Doubly Linked List', 'This is the GUI representation of my customly implemented Doubly Linked List. This List is Sorted and only allows unique integer values. Furthermore, the GUI is easily navigable and its purpose is to Add an unique integer while keeping the list sorted, Delete an integer if it exists in the list, and Display All integers currently stored within the list. Adding to and displaying an empty list, deleting an unstored integer, or entering a non-integer value; all would display errors.', '../pics/project_cards/Doubly_Linked_Sorted_List/Capture5.PNG'),
                        array('Ruby', 'fas fa-gem', 'Today\'s Songs', 'This CLI executable program provides the user with top songs of the day. It collects the song data from two different sites, lists them to the user, and enables them to listen to these songs by opening the user\'s preferred song in the chrome browser.', '../pics/project_cards/Todays_songs/Capture2.PNG'),
                        array('Java', 'fab fa-java', 'Wave Worm', 'Wave Worm is a single player 2D Java game. The user tackles the various worms using keyboard keys, and makes his/her way across levels, while maintaining a top score.', '../pics/project_cards/Wave_worm/Capture8.PNG'),
                        array('Python', 'fab fa-python', 'Video and Sound Encoder', 'This gui program makes bash calls to ffmpeg in order to encode either mov (includes audio encoding from surround to stereo) or dnxhd format video(s), hence condensing their size all the while preserving quality, as well as the directory structure. And this software was made during my time interning for FCB Chicago.', '../pics/project_cards/Video_and_Sound_Encoder/Capture5.PNG'),
                        array('Ruby', 'fas fa-gem', 'CLI Tic Tac Toe', 'This is a CLI version of Tic Tac Toe, providing the user with single player, double player, and even zero player (meaning the player gets to watch computer vs computer) experience.', '../pics/project_cards/Tic_Tac_Toe/Capture5.PNG')     
                    );
    
    $rows_to_add = array();
    
    $sql = "INSERT INTO project_cards(language, language_class, card_title, card_description, cover) VALUES";
    // Selecting the rows that do not exist in the database
    for($row=0; $row<count($project_cards); $row++){
        if ($conn->query("SELECT * FROM project_cards WHERE card_title=\"".$project_cards[$row][2]."\"")->num_rows == 0){
            $run_query = true; // atleast one row doesn't exist and needs to be added in the database 
            //echo $project_cards[$row][2];
            array_push($rows_to_add,$row); // index of the $rows to insert (which don't exist in the database)
        }
    }

    // Iterating the rows which do not exist in the database, and inserting them
    for($i=0; $i<count($rows_to_add); $i++){
        $row = $rows_to_add[$i];
        $sql .= "(";
        for($col=0; $col<count($project_cards[$row]); $col++){
            $sql .= '"'.$project_cards[$row][$col].'"';
            if ($col+1 < count($project_cards[$row])){
                $sql .= ", ";
            }
        }
        if ($i+1 == count($rows_to_add)){
            $sql .= ")";
        } else{$sql .= "), ";}
    }

    // Run the sql queries if there is atleast one row to be added
    if ($run_query == true){
        if ($conn->query($sql) == true) { 
            echo "Records inserted successfully."; 
        } else
        { 
            echo "<p>ERROR: Could not able to execute $sql. ".$conn->error."</p>"; 
        }
    } else {
        echo "No records to be inserted";
    }

    CloseCon($conn); // function is from DBconnection.php
?>