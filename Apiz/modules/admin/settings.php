<?php
// core configuration
include_once "../config/core.php";

// check if logged in as admin
include_once "login_checker.php";

// include database and object files
include_once '../config/database.php';
include_once "../objects/category.php";
include_once '../objects/setting.php';
include_once "../objects/message.php";
include_once "../objects/order.php";

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare page object
$category = new Category($db);
$setting = new Setting($db);
$message = new Message($db);
$order_obj = new Order($db);

// count unread message
$unread_message_count=$message->countUnread();

// count pending orders
$pending_orders_count=$order_obj->countPending();

// set page title
$page_title="Settings";

// include page header HTML
include 'layout_head.php';

// when form was submitted
if($_POST){
    echo "<div class='row'>";
        echo "<div class='col-md-12'>";
        // assign posted values
        $setting->contact_firstname=$_POST['contact_firstname'];
        $setting->contact_lastname=$_POST['contact_lastname'];
        $setting->contact_gender=$_POST['contact_gender'];
        $setting->contact_email=$_POST['contact_email'];
        $setting->contact_number=$_POST['contact_number'];
        $setting->show_contact_name=$_POST['show_contact_name'];
        $setting->show_contact_email=$_POST['show_contact_email'];
        $setting->show_contact_number=$_POST['show_contact_number'];

        // update settings
        if($setting->update()){
            echo "<div class='alert alert-success'>";
                echo "Settings was udpated.";
            echo "</div>";
        }

        // tell the user if unable to update
        else{
            echo "<div class='alert alert-danger'>";
                echo "Unable to update settings.";
            echo "</div>";
        }
        echo "</div>";
    echo "</div>";
}

// read settings
$setting->read();

echo "<div class='row'>";
    echo "<div class='col-md-12'>";

    // settings
    echo "<form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" id='contact-form' method='post'>";

        echo "<table class='table table-hover table-responsive table-bordered'>";
            echo "<tr>";
                echo "<td class='width-30-percent'>Contact Firstname</td>";
                echo "<td>";
                    echo "<input type='text' name='contact_firstname' class='form-control' value=\"{$setting->contact_firstname}\" required />";
                echo "</td>";
            echo "</tr>";

            echo "<tr>";
                echo "<td>Contact Lastname</td>";
                echo "<td>";
                    echo "<input type='text' name='contact_lastname' class='form-control' value=\"{$setting->contact_lastname}\" required />";
                echo "</td>";
            echo "</tr>";

            echo "<tr>";
                echo "<td>Contact Gender</td>";
                echo "<td>";
                    echo "<div class='btn-group' data-toggle='buttons'>";
                        $contact_gender_0_active=$setting->contact_gender==0 ? 'active' : '';
                        $contact_gender_0_checked=$setting->contact_gender==0 ? 'checked' : '';
                        echo "<label class='btn btn-default {$contact_gender_0_active}'>";
                            echo "<input type='radio' name='contact_gender' value='0' {$contact_gender_0_checked}> Male";
                        echo "</label>";

                        $contact_gender_1_active=$setting->contact_gender==1 ? 'active' : '';
                        $contact_gender_1_checked=$setting->contact_gender==1 ? 'checked' : '';
                        echo "<label class='btn btn-default {$contact_gender_1_active}'>";
                            echo "<input type='radio' name='contact_gender' value='1' {$contact_gender_1_checked}> Female";
                        echo "</label>";
                    echo "</div>";
                echo "</td>";
            echo "</tr>";

    		echo "<tr>";
                echo "<td>Contact Email</td>";
                echo "<td>";
                    echo "<input type='email' name='contact_email' class='form-control' value=\"{$setting->contact_email}\" required />";
                echo "</td>";
            echo "</tr>";

    		echo "<tr>";
                echo "<td>Contact Number</td>";
                echo "<td>";
                    echo "<input type='text' name='contact_number' class='form-control' value=\"{$setting->contact_number}\" required />";
                echo "</td>";
            echo "</tr>";

    		echo "<tr>";
                echo "<td>Show Contact Name</td>";
                echo "<td>";
                    echo "<div class='btn-group' data-toggle='buttons'>";
                        $show_contact_name_0_active=$setting->show_contact_name==0 ? 'active' : '';
                        $show_contact_name_0_checked=$setting->show_contact_name==0 ? 'checked' : '';
                        echo "<label class='btn btn-default {$show_contact_name_0_active}'>";
                            echo "<input type='radio' name='show_contact_name' value='0' {$show_contact_name_0_checked}> No";
                        echo "</label>";

                        $show_contact_name_1_active=$setting->show_contact_name==1 ? 'active' : '';
                        $show_contact_name_1_checked=$setting->show_contact_name==1 ? 'checked' : '';
                        echo "<label class='btn btn-default {$show_contact_name_1_active}'>";
                            echo "<input type='radio' name='show_contact_name' value='1' {$show_contact_name_1_checked}> Yes";
                        echo "</label>";
                    echo "</div>";
                echo "</td>";
            echo "</tr>";

    		echo "<tr>";
    			echo "<td>Show Contact Email</td>";
    			echo "<td>";
                    echo "<div class='btn-group' data-toggle='buttons'>";
                        $show_contact_email_0_active=$setting->show_contact_email==0 ? 'active' : '';
                        $show_contact_email_0_checked=$setting->show_contact_email==0 ? 'checked' : '';
                        echo "<label class='btn btn-default {$show_contact_email_0_active}'>";
                            echo "<input type='radio' name='show_contact_email' value='0' {$show_contact_email_0_checked}> No";
                        echo "</label>";

                        $show_contact_email_1_active=$setting->show_contact_email==1 ? 'active' : '';
                        $show_contact_email_1_checked=$setting->show_contact_email==1 ? 'checked' : '';
                        echo "<label class='btn btn-default {$show_contact_email_1_active}'>";
                            echo "<input type='radio' name='show_contact_email' value='1' {$show_contact_email_1_checked}> Yes";
                        echo "</label>";
                    echo "</div>";
    			echo "</td>";
    		echo "</tr>";

    		echo "<tr>";
    			echo "<td>Show Contact Number</td>";
    			echo "<td>";
                    echo "<div class='btn-group' data-toggle='buttons'>";
                        $show_contact_number_0_active=$setting->show_contact_number==0 ? 'active' : '';
                        $show_contact_number_0_checked=$setting->show_contact_number==0 ? 'checked' : '';
                        echo "<label class='btn btn-default {$show_contact_number_0_active}'>";
                            echo "<input type='radio' name='show_contact_number' value='0' {$show_contact_number_0_checked}> No";
                        echo "</label>";

                        $show_contact_number_1_active=$setting->show_contact_number==1 ? 'active' : '';
                        $show_contact_number_1_checked=$setting->show_contact_number==1 ? 'checked' : '';
                        echo "<label class='btn btn-default {$show_contact_number_1_active}'>";
                            echo "<input type='radio' name='show_contact_number' value='1' {$show_contact_number_1_checked}> Yes";
                        echo "</label>";
                    echo "</div>";
    			echo "</td>";
    		echo "</tr>";

            echo "<tr>";
                echo "<td></td>";
                echo "<td>";
                    // send message button
                    echo "<button type='submit' class='btn btn-primary' id='btn_send_message'>";
                        echo "<span class='glyphicon glyphicon-envelope'></span> Save Settings";
                    echo "</button>";
                echo "</td>";
            echo "</tr>";

        echo "</table>";
    echo "</form>";

    echo "</div>";
echo "</div>";

// include page footer HTML
include_once 'layout_foot.php';
?>
