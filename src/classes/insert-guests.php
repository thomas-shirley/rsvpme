<?php
interface insertGuests {
    public static function insert(string $emailAddress, string $guestID, int $guestCount, string $guestName);
}

class guestInserter implements insertGuests {

    public static function insert($emailAddress, $guestID, $guestCount, $guestName) {
        require '../../creds/rsvpme.php';
        // Create DB instance and execute the statement
        $db = new PDO("pgsql:dbname={$credentials['dbname']};host={$credentials['dbhost']};port=5432", $credentials['dbuser'], $credentials['dbpass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        //insert data into guests: guest_email_address, guest_rsvp_number, guest_rsvp_confirmed. This is only on the initial generation.
        $statement = $db->prepare("INSERT INTO guests(guest_email_address, guest_rsvp_number, guest_invited_total, guest_name, guest_rsvp_confirmed) VALUES(?,?,?,?,false) RETURNING TRUE");
        $statement->execute([$emailAddress, $guestID, $guestCount, $guestName]);


        if ($statement->fetchAll()[0] === true) {
            return array("success" => true);
        }
        else {
            return array("success" => 'error');
        }

    }
}
?>