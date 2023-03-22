<?php

session_start();
switch (json_decode($_POST['requestPayload'])->request)  {
    
    case 'generateID';
    $id = hash("adler32", $_POST['guestName']);
    require 'classes/insert-guests.php';
    if (guestInserter::insert($_POST['guestEmail'], $id, $_POST['guestCount'], $_POST['guestName'])) {
        echo json_encode(array("success" => true, "id" => $id, "guestEmail" => $_POST['guestEmail']));
        die;
    }
    echo json_encode(array("success" => false, "error" => "Unable to insert into DB"));
    break;

    case 'getGuestEmailAddress';
    require 'classes/get-guest-email.php';
    $id = $_POST['guestID'];
    echo json_encode(guestEmail::get($id));
    break;

    case 'updateRSVP';
    require 'classes/update-rsvp.php';
    $id = $_POST['guestID'];
    $rsvpCount = $_POST['rsvpCount'];
    echo json_encode(rsvp::performUpdate($id, $rsvpCount));
    break;
    
    case 'getGuests';
    require 'classes/get-guest-email.php';
    echo json_encode(guestEmail::getAll());
    break;

    case 'getWeddingDetails';
    require 'classes/get-wedding-details.php';
    // echo json_encode(guestEmail::getAll());
    break;

    case 'validateUser';
    require_once 'classes/sanitise.php';
    require_once 'usr/hashVerify.php';
    $sanitisedUserInput = sanitise::cleanUserLoginData(json_decode($_POST['requestPayload']));
    $hashVerify         = new hashVerify();
    $validateUser       = $hashVerify->validatePassword($sanitisedUserInput['userpassword']);

    if ($validateUser['result'] == true) {
        require_once 'classes/sessions/startSession.php';
        startSession::setAllSessionVars();
        echo json_encode(array('result' => true));
    }
    else {
        echo json_encode(array('result' => false));
        session_reset();
    }
    break;

}