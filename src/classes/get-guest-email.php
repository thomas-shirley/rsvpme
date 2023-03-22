<?php
interface getGuestEmail {
    public static function get(string $guestID);
    public static function getAll();
}

class guestEmail implements getGuestEmail {

    public static function get($guestID) {
        require '../../creds/rsvpme.php';
        // Create DB instance and execute the statement
        $db = new PDO("pgsql:dbname={$credentials['dbname']};host={$credentials['dbhost']};port=5432", $credentials['dbuser'], $credentials['dbpass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        //insert data into guests: guest_email_address, guest_rsvp_number, guest_rsvp_confirmed. This is only on the initial generation.
        $statement = $db->prepare("SELECT guest_email_address, guest_rsvp_confirmed, guest_attending, guest_invited_total, guest_name FROM guests WHERE guest_rsvp_number = ?");
        $statement->execute([$guestID]);
        $queryResults = $statement->fetch(PDO::FETCH_ASSOC);
        // echo var_dump($queryResults);
        if ($queryResults != false && count($queryResults) == 5) {
            return array("success" => true, "guestEmail" => $queryResults['guest_email_address'], "guestConfirmed" => $queryResults['guest_rsvp_confirmed'], "guestsAttending" => $queryResults['guest_attending'], "guestsInvited" => $queryResults['guest_invited_total'],  "guestName" => $queryResults['guest_name']);
        }
        else {
            return array("success" => 'error');
        }

    }

    public static function getAll() {
        require '../../creds/rsvpme.php';
        $db = new PDO("pgsql:dbname={$credentials['dbname']};host={$credentials['dbhost']};port=5432", $credentials['dbuser'], $credentials['dbpass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        //insert data into guests: guest_email_address, guest_rsvp_number, guest_rsvp_confirmed. This is only on the initial generation.
        $statement = $db->prepare("SELECT guest_email_address, guest_rsvp_confirmed, guest_attending, guest_invited_total, guest_rsvp_number, guest_name FROM guests ORDER BY guest_name desc");
        $statement->execute();
        $queryResults = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($queryResults[0] != false) {
            return array("success" => true, "guests" => $queryResults);
        }
        else {
            return array("success" => 'error');
        }
    }
}
?>