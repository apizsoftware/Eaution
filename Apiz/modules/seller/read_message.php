<?php
// core configuration
include_once "../config/core.php";

// get database connection
include_once '../config/database.php';
include_once "../objects/category.php";
include_once '../objects/message.php';
include_once "../objects/message.php";
include_once '../libs/php/utils.php';
include_once "../objects/order.php";

// get databae connection
$database = new Database();
$db = $database->getConnection();

// instantiate page object
$category = new Category($db);
$message = new Message($db);
$order = new Order($db);

// utilities
$utils = new Utils();

// get ID of message to be read
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID slug.');

// set ID property of page to be edited
$message->id = $id;

// read the details of page to be edited
$message->readOne();

// set message as read
$message->changeStatus();

// set page headers
$page_title = "Read Message";
include_once "layout_head.php";

echo "<div class='row'>";
    echo "<div class='col-md-12'>";
        echo "<div class='right-button-margin'>";
            echo "<a href='{$home_url}admin/read_messages.php' class='btn btn-primary pull-right'>Back to messages</a>";
        echo "</div>";

        // display message
        echo "<table class='table table-hover table-responsive table-bordered'>";
            echo "<tr>";
                echo "<td class='width-30-percent'>From</td>";
                echo "<td>{$message->name} &lt;{$message->email}&gt;</td>";
            echo "</tr>";

            echo "<tr>";
                echo "<td>Subject</td>";
                echo "<td>{$message->subject}</td>";
            echo "</tr>";

            echo "<tr>";
                echo "<td>Message</td>";
                echo "<td>{$message->message}</td>";
            echo "</tr>";

        echo "</table>";
    echo "</div>";
echo "</div>";

// footer
include_once "layout_foot.php";
?>
