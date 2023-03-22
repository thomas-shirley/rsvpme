<?php
interface updateRSVP {
    public static function performUpdate(string $guestID, string $rsvpCount);
}

class rsvp implements updateRSVP {
    
    
    public static function performUpdate($guestID, $rsvpCount) {
        //Load in credentials
        require '../../creds/rsvpme.php';
        // Create DB instance and execute the statement
        $db = new PDO("pgsql:dbname={$credentials['dbname']};host={$credentials['dbhost']};port=5432", $credentials['dbuser'], $credentials['dbpass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $statement = $db->prepare("UPDATE guests SET guest_attending = ?, guest_rsvp_confirmed = true WHERE guest_rsvp_number = ? RETURNING TRUE");
        $statement->execute([$rsvpCount, $guestID]);
        $queryResults = $statement->fetch(PDO::FETCH_ASSOC);
        if ($queryResults['bool'] === true) {
            return array("success" => true);
        }
        else {
            return array("success" => 'error');
        }

    }
}?>