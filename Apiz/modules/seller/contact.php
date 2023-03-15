<?php
// core configuration
include_once "../../config/core.php";

// check if logged in as admin
include_once "../auth/login_checker.php";


// include classes

include_once "../../config/database.php";
include_once "../../libs/utils.php";
include_once "../../classes/Product.php";
include_once "../../classes/Product_image.php";
include_once "../../classes/Category.php";
include_once '../../classes/Bids.php';
include_once '../../classes/Price.php';
include_once '../../classes/Message.php';
include_once '../../classes/Settings.php';

// get database connection
$database = new Database();
$db = $database->getConnection();


// initialize objects
$product = new Product($db);
$utils = new Utils($db);
$product_image = new ProductImage($db);
$category = new Category($db);
$bid = new Bid($db);
$price = new Price($db);
$message_obj = new Message($db);
$setting = new Setting($db);



// read settings
$setting->read();

// set page title
$page_title="Contact Us";

// include page header HTML
include_once 'layout_head.php';

// initialize field values
$name="";
$email="";
$subject="";
$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    // store field values in variable
    $name=$_POST['name'];
    $email=$_POST['email'];
    $subject=$_POST['subject'];
    $message=$_POST['message'];

    // store error in variable
    $name_error="";
    $email_error="";
    $subject_error="";
    $message_error="";

    // required fields (sanitized in the 'message' object's create() method)
    // name
    if(empty($_POST['name'])){
        $name_error="Name cannot be empty.";
    }

    // email
    if(empty($_POST['email'])){
        $email_error="Email cannot be empty.";
    }

    else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
      $email_error = "Invalid email format.";
    }

    // subject
    if(empty($_POST['subject'])){
        $subject_error="Subject cannot be empty.";
    }

    // message
    if(empty($_POST['message'])){
        $message_error="Message cannot be empty.";
    }

    // if no errors
    if(
        empty($name_error) &&
        empty($email_error) &&
        empty($subject_error) &&
        empty($message_error)
    ){

        // assign to messages object
        $message_obj->name=$name;
        $message_obj->email=$email;
        $message_obj->subject=$subject;
        $message_obj->message=$message;

        // update settings
        if($message_obj->create()){

            echo "<div class='col-md-12'>";

                    
             
                    echo "<div class='alert alert-success'>";
                    echo "Your message was sent.";
                    echo "</div>";
                
            }

            // tell the user if unable to update
            else{
                echo "<div class='alert alert-danger'>";
                echo "Unable to send message.";
                echo "</div>";
            }

        echo "</div>";
    }
}

echo "<div class='col-md-12'>";
    // contact form
    echo "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" id='contact-form' method='post'>";

        echo "<table class='table table-hover table-responsive'>";
            echo "<tr>";
                echo "<td class='width-30-percent'>Name</td>";
                echo "<td>";
                    echo "<input type='text' name='name' class='form-control' value=\"{$name}\" required />";
                    echo empty($name_error) ? "" : "<div class='alert alert-danger m-10px-0'>{$name_error}</div>";
                echo "</td>";
            echo "</tr>";

            echo "<tr>";
                echo "<td>Email</td>";
                echo "<td>";
                    echo "<input type='email' name='email' class='form-control' value=\"{$email}\" required />";
                    echo empty($email_error) ? "" : "<div class='alert alert-danger m-10px-0'>{$email_error}</div>";
                echo "</td>";
            echo "</tr>";

            echo "<tr>";
                echo "<td>Subject</td>";
                echo "<td>";
                    echo "<input type='text' name='subject' class='form-control' value=\"{$subject}\" required />";
                    echo empty($subject_error) ? "" : "<div class='alert alert-danger m-10px-0'>{$subject_error}</div>";
                echo "</td>";
            echo "</tr>";

            echo "<tr>";
                echo "<td>Message</td>";
                echo "<td>";
                    echo "<textarea name='message' class='form-control' required>{$message}</textarea>";
                    echo empty($message_error) ? "" : "<div class='alert alert-danger m-10px-0'>{$message_error}</div>";
                echo "</td>";
            echo "</tr>";

            echo "<tr>";
                echo "<td></td>";
                echo "<td>";
                    // send message button
                    echo "<button type='submit' name='send_message' class='btn btn-primary' id='btn_send_message'>";
                        echo "<span class='glyphicon glyphicon-envelope'></span> Send Message";
                    echo "</button>";
                echo "</td>";
            echo "</tr>";

            echo "<tr>";
                echo "<td></td>";
                echo "<td colspan='3'>";

                    if(
                        $setting->show_contact_name==1 ||
                        $setting->show_contact_email==1 ||
                        $setting->show_contact_number==1
                    ){
                        echo "<p>Other ways to contact us:</p>";
                    }

                    // contact email
                    if($setting->show_contact_email==1){
                        echo "<p>Email: ";
                            echo isset($setting->contact_email) ? $setting->contact_email : "";
                        echo "</p>";
                    }

                    // contact number
                    if($setting->show_contact_number){
                        echo "<p>Contact number: ";
                            echo isset($setting->contact_number) ? $setting->contact_number : '';
                        echo "</p>";
                    }

                    // contact firstname / lastname
                    if($setting->show_contact_name==1){
                        echo "<p>Look for: ";
                            echo isset($setting->contact_firstname) ? $setting->contact_firstname . " " : '';
                            echo isset($setting->contact_lastname) ? $setting->contact_lastname : '';
                        echo "</p>";
                    }

                echo "</td>";
            echo "</tr>";
        echo "</table>";
    echo "</form>";
echo "</div>";

// footer HTML and JavaScript codes
include 'layout_foot.php';
?>
