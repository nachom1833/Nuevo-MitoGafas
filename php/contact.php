<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $comments = htmlspecialchars($_POST['comments']);

    // Set the recipient email address
    $to = "mitogafasok@gmail.com";

    // Set the email subject
    $subject = "New mensaje del formulario de contacto";

 // Set the email content
    $message = "
     <html>
     <head>
    <title>New mensaje del formulario de contacto</title>
     </head>
     <body>
     <p>Reciviste un mensaje del formulario de contacto.</p>
    <p>Nombre: $name</p>
     <p>Email: $email</p>
     <p>Mensaje: $comments</p>
     </body>
    </html>
    ";
    // $txt ="Nombre = ". $name . "\r\n  Email = "
    // . $email . "\r\n Mensaje =" . $message;
    // Set the email headers
    $headers = "MIME-Version: 1.0". "\r\n";
    $headers.= "Content-type:text/html;charset=UTF-8". "\r\n";
   $headers.= "From: $email". "\r\n";

    // Send the email
    if (mail($to, $subject, $txt, $headers)) {
        // If the email is sent successfully, display a success message
        echo "<div id='simple-msg'>El mensaje a sido  enviado correctamente!</div>";
    } else {
        // If there's an error sending the email, display an error message
        echo "<div id='simple-msg'>perdon, tu mensaje no a sido enviado correctamente.</div>";
    }
}
?>