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
$f3->route('GET|POST /order', function($f3) {
    //Add data from form1 to Session array
    //var_dump($_POST);
    //get the data from the post
    $userFood = trim($_POST['food']);
    $userMeal = trim($_POST['meal']);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(validFood($userFood)) {
            $_SESSION['food'] = $_POST['food'];
        }
        else{

            $f3-> set ('errors["food"]', "food cannot be blanked");
        }
        if(validMeal($userMeal)) {
            $_SESSION['meal'] = $userMeal;
        }
        else{
            $f3-> set ('errors["meal"]', "meal should be selected");
        }

        //if no errors go to next page
        if(empty($f3->get('errors'))){
            $f3->reroute('/order2');
        }
    }



    $f3-> set('meals', getMeals());
    $f3-> set('userFood', isset($userFood)? $userFood: "");
    $f3-> set('userMeal', isset($userMeal)? $userMeal: "");
    //Display a view
    $view = new Template();
    echo $view->render('views/form1.html');
});

//Define an order2 route
$f3->route('GET|POST /order2', function($f3) {

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_POST['conds'])) {
            $userCondiments =$_POST['conds'];
            //data is valid add to session
            if(validCondiments($userCondiments)){
                $_SESSION['conds'] = implode(", ", $userCondiments);
            }
            else{
                $f3-> set ('errors["conds"]', "Go away, evildoer");
            }

            //send to summary page

        }
        else{
            $f3-> set ('errors["conds2"]', "please select one");
        }
        if(empty($f3->get('errors'))){
            $f3->reroute('/summary');
        }


    }

    $f3->set('condiments', getCondiments());

    //Display a view
    $view = new Template();
    echo $view->render('views/form2.html');
});

//Define a summary route
$f3->route('GET /summary', function() {

    //echo "<p>POST:</p>";
    //var_dump($_POST);

    //echo "<p>SESSION:</p>";
    //var_dump($_SESSION);

    //Add data from form2 to Session array


    //Display a view
    $view = new Template();
    echo $view->render('views/summary.html');
    session_destroy();
});

//Run Fat-Free
$f3->run();