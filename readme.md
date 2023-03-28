
<!-- LOGO -->
<br />
<h1>
<p align="center">
  <img src="na" alt="Logo" width="140" height="110">
  <br>RSVPMe
</h1>
  <p align="center">
    Web application to send, track and manage RSVPs to your wedding. Easily deployed.
    <br />
    </p>
</p>
<p align="center">
  <a href="#about-the-project">About The Project</a> •
  <a href="#usage">How To Use</a> •
  <a href="#how-it-works">How it works</a> •
  <a href="#get-started">Get Started</a> •
</p>  

<p align="center">
  
![screenshot](img/screenshot.png)
</p>                                                                                                                             
      
## About The Project
RSVPMe is a compact, dynamic and fun way for you to manage RSVPs to a wedding. It is easily deployed on a server and has few overheads. It supports unique URL generation for your guests, with tailored invitations. 

It handles RSVP for both single and double invitees. 

It uses:
 - PostGresDB
 - PHP 8+
 - VueJS & Vue Router
 - Tachyons CSS framework


 It also includes rudimentary login/ authentication. 

## Prerequisites
 - PostgreSQL DB 
 - Apache or NginX (rewrite rules included for both - but you'll need to update your NGINX server blocks if using NginX)

### Configure DB tables

Run the following to create the necessary table in your postgresDB

```sql
CREATE TABLE guests (
    id SERIAL UNIQUE PRIMARY KEY NOT NULL,
    guest_email_address varchar(80),
    guest_rsvp_number varchar(80),
    guest_rsvp_confirmed boolean,
    created_date DATE DEFAULT NOW() NOT NULL,
    guest_attending int,
    guest_invited_total int,
    guest_name varchar(80)
);
```

### Configure your PostGres credentials file
This is a file that contains your DB access credentials. It should <i>not</i> be stored in your web root. Store it outside your hosting directory. It is a simple PHP array:

```php
$credentials = array("dbuser" => "your-user-name", "dbpass" => "your-db-password", "dbhost" => "localhost", "dbname" => "wedding");
```

The path to this file must be updated on the following PHP files, inside the /src/classes/ directory on the following files:

```
get-guest-email.php
insert-guests.php
update-rsvp.php
```

### Configure login password
Edit /src/usr/hashPassword.php and change  ```your-password-here```  to your password of choice. I don't need to remind you to use a secure password.

```php
$newPass = new hashPassword('your-password-here');
``` 

Visit the same path in your browser and copy and paste the output into the /src/usr/dash.dat file. e.g:

```
$argon2id$v=19$m=65536,t=4,p=1$WHppRlVSSUplNTZHOGJOdA$Hsv+5jf1itcJleO5WtJEqW1gaEvpnTJDPin7Ufqczjk
```

The .dat file now contains an argon2id hash of your password.

Return to the /src/usr/hashPassword.php file and delete the password you entered. You can delete the file ```'hashPassword.php'``` entirely if you wish. If you don't either remove the password you entered or delete the file entirely, <strong>anybody</strong> can log in.

### Configure Variables
Open the file src/config.php. Adjust the variables accordingly:

```php
## Enter the name of the people getting married
$WEDDING_NAME   = 'Jill and Jane';
## Enter the date the ceremony is due to take place
$WEDDING_DATE   = '14∙07∙23';
## Enter the venue for the ceremony
$WEDDING_VENUE  = 'The Wide Open Air';
## Select an RSVP template. // To be implemented!
$RSVP_TEMPLATE  = 1;
```

That's it! No more config required. Deploy to your server. Point your browser to /src/login.php, sign in and begin generating rsvp URLs.
