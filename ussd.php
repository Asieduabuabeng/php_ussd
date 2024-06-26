<?php
header("Content-type: text/plain");

//include database configuration
require 'config1.php';

// Reads the variables sent via POST
$sessionId   = $_POST["sessionId"];  
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];  
$text = $_POST["text"];

//Explode the text to understand the user's response
$textArray = explode("*", $text);
$level = count($textArray);

//Initialize the response
$response = "";

//This is the first menu screen
if ($text == "") {
    $response  = "CON Hi welcome. Your mental health is a priority, don't be afraid to seek help \n";
    $response .= "1. Enter 1 to continue";
}
// Menu for a user who selects '1' from the first menu
else if ($text == "1") {
    $response  = "CON Why are you here today? \n";
    $response .= "1. Emergency Support \n";
    $response .= "2. Report a Case \n";
}
// Menu if user selects 1 which is Emergency contact
else if ($text == "1*1") {
    $response = "CON Please call our emergency line: 0555487865 \n";
    $response .= "1. Call Now \n";
    $response .= "2. Main Menu \n";
}
else if ($text == "1*1*1") {
    $response = "END Please dial 0555487865 from your phone to get immediate help. \n";
}
else if ($text == "1*1*2") {
    $response  = "CON Why are you here today? \n";
    $response .= "1. Emergency Support \n";
    $response .= "2. Report a Case \n";
}
// Menu if user selects 2 which is Report a case
else if ($text == "1*2") {
    if ($level == 2) {
        $response = "CON Enter name of victim: \n";
    } else if ($level == 3) {
        $response = "CON Enter victim's phone number: \n";
    } else if ($level == 4) {
        $response = "CON Enter victim's college: \n";
    } else if ($level == 5) {
        $response = "CON Enter victim's department: \n";
    } else if ($level == 6) {
        $response = "CON Enter victim's residence: \n";
    } else if ($level == 7) {
        $response = "CON Describe the case: \n";
    } else if ($level == 8) {
        // Save information to the database
        $name = $textArray[2];
        $PhoneNumber = $textArray[3];
        $college = $textArray[4];
        $department = $textArray[5];
        $residence = $textArray[6];
        $description = $textArray[7];

        $stmt = $conn->prepare("INSERT INTO reports (phoneNumber, studentName, college, department, hostel, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $phoneNumber, $studentName, $college, $department, $residence, $description);
        if ($stmt->execute()) {
            $response = "END Thank you for reporting. We will get back to you shortly. \n";
        } else {
            $response = "END Error reporting case. Please try again later. \n";
        }
        $stmt->close();
    }
} else {
    $response = "END Invalid Choice. \n";
}
$conn->close();

// Send response back to Africa's Talking
echo $response;
echo "Connected successfully";
?>


