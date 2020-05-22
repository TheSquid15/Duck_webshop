<?php
require("../includes/const.php");

include(MODEL);

class contact_controller extends DB_model {

    private $automated_message = "
    <html>
        <body>
            <div style='display:flex; flex-direction:column; justify-content:center; align-items:center;'>
            <h2>Thank you for your email!</h2>
            <p>Thank you for your email, we'll reply as soon as possible.<br><br>Keep on ducking!<br><br>      _          _          _          _          _<br>
            >(')____,  >(')____,  >(')____,  >(')____,  >(') ___,<br>
              (` =~~/    (` =~~/    (` =~~/    (` =~~/    (` =~~/<br>
           ~^~^`---'~^~^~^`---'~^~^~^`---'~^~^~^`---'~^~^~^`---'~^~^~<br>
           </p>
           <small>Quack-Tac&trade;</small>
           </div>
        </body>
    </html>
    ";

    public function send_form($subject, $message, $email) {
        $fetch_email = $this->sql_query("SELECT email FROM about_us");
        $business_email = $fetch_email->fetch_assoc();
        
        $san_subject = trim(htmlspecialchars($subject));
        $san_message = trim(htmlspecialchars($message));
        $san_email = trim(htmlspecialchars($email));
        $timestamp = date("Y/m/d h:i:sa");

        $sendToDB = $this->conn->prepare("INSERT INTO message VALUES (NULL, ?, ?, ?, ?)");
        $sendToDB->bind_param("ssss", $db_subject, $db_message, $timestamp, $db_email);

        $db_subject = $san_subject;
        $db_message = $san_message;
        $db_email = $san_email;

        $sendToDB->execute();

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = "To: <$san_email>";
        $headers[] = "From: Quack-Tac Team <duckshop.noreply@prep4this.com>";

        mail($business_email["email"], $san_subject . $timestamp, $san_message, "From: $san_email");
        mail($san_email, "re: " . $san_subject, "Dear sender, <br>" . $this->automated_message, implode("\r\n", $headers));
    }
} 