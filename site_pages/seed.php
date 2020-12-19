<?php
    function OpenCon()
    {
        $dbhost = "127.0.0.1";
        $dbuser = "root";
        $dbpass = "root";
        $db = "projects";
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n". $conn -> error);
        return $conn;
    }

    function CloseCon($conn)
    {
        $conn -> close();
    }

    $conn = OpenCon();

    $query = $conn->query("SELECT * FROM project_cards;");
    $arr =  $query->fetch_all();
    if(count($arr) == 0){
        // add in all the project cards
        $sql = "INSERT INTO project_cards(language, language_class, card_title, card_description)
                    VALUES('Python', 'fab fa-python', 'Web Server', 'Web Server created using sockets in python, runs on localhost:8000 by default, and accepts multiple clients. Saves the html file by keeping the directory structure inside a custom created folder called `static`, only if the response was a HTTP 200 OK response; otherwise checks for 404 Not Found, and 400 Bad Request errors.'),
            ('Java', 'fab fa-java', 'Sorted Doubly Linked List', 'This is the GUI representation of my customly implemented Doubly Linked List. This List is Sorted and only allows unique integer values. Furthermore, the GUI is easily navigable and its purpose is to Add an unique integer while keeping the list sorted, Delete an integer if it exists in the list, and Display All integers currently stored within the list. Adding to and displaying an empty list, deleting an unstored integer, or entering a non-integer value; all would display errors.'),
            (),
            ()";
    } elseif(count($arr) >=1){
        // only add in those which don't already exist in the database
    }


    CloseCon($conn);
?>