<?php
/** Create a food order form */

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();

//Require autoload file
require_once('vendor/autoload.php');
require_once('model/data-layer.php');
require_once('model/valid.php');

//Instantiate Fat-Free
$f3 = Base::instance();

//Turn on Fat-Free error reporting
$f3->set('DEBUG', 3);

//Define a default route
$f3->route('GET /', function() {

    //Display a view
    //$view = new Template();
    //echo $view->render('views/home.html');
    echo"<h1>Midterm Survey</h1>";
    echo "<h3><a href='survey'>Take my Midterm Survey</a></h3>";
});

//Define an order route
$f3->route('GET|POST /survey', function($f3) {
    //Add data from form1 to Session array
    //var_dump($_POST);
    //get the data from the post
    $userNames = trim($_POST['names']);
    $userAnswers = trim($_POST['answers']);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(!empty($_POST['names'])) {
            $_SESSION['names'] = $_POST['names'];
            $f3->set('names', $_SESSION['names']);
        }
        else{

            $f3-> set ('errors["names"]', "Name cannot be blanked");
        }
        if(isset($_POST['ans'])) {
            $userAnswers = $_POST['ans'];
            //data is valid add to session
            if (validAnswers($userAnswers)) {
                $_SESSION['ans'] = implode(", ", $userAnswers);
               // $f3->set('ans', $_SESSION['ans']);
            } else {
                $f3->set('errors["ans"]', "please select an answer");
            }
        }
        else{
            $f3-> set ('errors["ans2"]', "please select one");
        }

        //if no errors go to next page
        if(empty($f3->get('errors'))){
            $f3->reroute('/summary');
        }
    }
    $f3->set('answers', getAnswers());
    $f3-> set('userNames', isset($userNames)? $userNames: "");
    $f3-> set('userAnswers', isset($userAnswers)? $userAnswers: "");
    //Display a view
    $view = new Template();
    echo $view->render('views/form1.html');
});


//Define a summary route
$f3->route('GET /summary', function() {

    //Display a view
    $view = new Template();
    echo $view->render('views/summary.html');
    session_destroy();
});

//Run Fat-Free
$f3->run();