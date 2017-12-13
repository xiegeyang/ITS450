<?php

// This script sends a receipt out in HTML format.
// This script is created in Chapter 10.

// Create the message body in two formats:
$body_plain = "Thank you for your order. Your order number is {$_SESSION['order_id']}. All orders are processed on the next business day. You will be contacted in case of any delays.\n\n";

$body_html = '<html><head><style type="text/css" media="all">
	body {font-family:Tahoma, Geneva, sans-serif; font-size:100%; line-height:.875em; color:#70635b;}
</style></head><body>
<p>Thank you for your order. Your order number is ' . $_SESSION['order_id'] . '. All orders are processed on the next business day. You will be contacted in case of any delays.</p>
<table border="0" cellspacing="8" cellpadding="6">
	<tr>
		<th align="center">Item</th>
		<th align="center">Quantity</th>
		<th align="right">Price</th>
		<th align="right">Subtotal</th>
	</tr>';

// Get the cart contents for the confirmation email:
$r = mysqli_query($dbc, "CALL get_order_contents({$_SESSION['order_id']})");

// Fetch each product:
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	
	// Add to the plain version:
	$body_plain .= "{$row['category']}::{$row['name']} ({$row['quantity']}) @ \${$row['price_per']} each: $" . $row['subtotal'] . "\n";
	
	// Add to the HTML:
	$body_html .= '<tr><td>' . $row['category'] . '::' . $row['name'] . '</td>
		<td align="center">' . $row['quantity'] . '</td>
		<td align="right">$' . $row['price_per'] . '</td>
		<td align="right">$' . $row['subtotal'] . '</td>
	</tr>
	';
	
	// For reference after the loop:
	$shipping = $row['shipping'];
	$total = $row['total'];

} // End of WHILE loop. 

// Clear the stored procedure results:
mysqli_next_result($dbc);

// Add the shipping:
$body_html .= '<tr>
	<td colspan="2"> </td><th align="right">Shipping &amp; Handling</th>
	<td align="right">$' . $shipping . '</td>
</tr>
';
$body_plain .= "Shipping & Handling: \$$shipping\n";

// Add the total:
$body_plain .= "Total: \$$total\n";
$body_html .= '<tr>
	<td colspan="2"> </td><th align="right">Total</th>
	<td align="right">$' . $total . '</td>
</tr>
';

// Complete the HTML body:
$body_html .= '</table></body></html>';

// For Zend:
//set_include_path('./library/');

// Include the class definition:
//include ('Zend/Mail.php');

require_once('./PHPMailer/class.phpmailer.php');

// Create a new mail:
//$mail = new Zend_Mail(); 
//$mail->setFrom('admin@example.com');
//$mail->addTo($_SESSION['email']);
//$mail->setSubject("Order #{$_SESSION['order_id']} at the Coffee Site");
//$mail->setBodyText($body_plain);
//$mail->setBodyHtml($body_html); 
//$mail->send();  
date_default_timezone_set('Etc/UTC');

require './PHPMailer/PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 465;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'ssl';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "vaccinexie@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "vaccine123";
//Set who the message is to be sent from
$mail->setFrom('vaccinexie@gmail.com', 'Auto Everything');
//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to
$mail->addAddress($_SESSION['email'], 'John Doe');
//Set the subject line
$mail->Subject = 'Auto Everything Receipt';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->isHTML(true);
//$mail->msgHTML(ILE__));
//Replace the plain text body with one created manually
$mail->Body = $body_html;
$mail->AltBody = $body_plain;
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
if (!$mail->send()) {
 //   echo "Mailer Error: " . $mail->ErrorInfo;
} else {
//    echo "Message sent!";
    //Section 2: IMAP
    //Uncomment these to save your message in the 'Sent Mail' folder.
   // if (save_mail($mail)) {
    //    echo "Message saved!";
    //}
}
