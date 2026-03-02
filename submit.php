<?php

// BLOCK DIRECT ACCESS
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Access denied.");
}

// SANITIZE FUNCTION
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$errorMSG = "";

// GET & VALIDATE DATA
$name    = clean_input($_POST["name"] ?? '');
$email   = clean_input($_POST["email"] ?? '');
$phone   = clean_input($_POST["phone"] ?? '');
$course  = clean_input($_POST["course"] ?? '');
$message = clean_input($_POST["message"] ?? '');

if ($name == "") {
    $errorMSG .= "Full Name is required. ";
}

if ($email == "") {
    $errorMSG .= "Email is required. ";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorMSG .= "Enter valid email address. ";
}

if ($phone == "") {
    $errorMSG .= "Phone is required. ";
}

if ($course == "") {
    $errorMSG .= "Please select a course. ";
}

if ($message == "") {
    $errorMSG .= "Message is required. ";
}


// CONTINUE ONLY IF NO ERRORS
if ($errorMSG == "") {

    $EmailTo = "janavalsan@mindstory.in"; // CHANGE THIS
    $subject = "New Course Enquiry from Website";

    // CUSTOM HTML EMAIL TEMPLATE
    $Body = "
    <html>
    <head>
        <meta charset='UTF-8'>
    </head>
    <body style='margin:0; padding:0; background:#f4f6f9; font-family:Arial, sans-serif;'>

        <table width='100%' cellpadding='0' cellspacing='0' style='background:#f4f6f9; padding:30px 0;'>
            <tr>
                <td align='center'>
                    
                    <table width='600' cellpadding='0' cellspacing='0' style='background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 5px 20px rgba(0,0,0,0.08);'>

                        <tr>
                            <td style='background:#0E52A0; padding:20px; text-align:center; color:#ffffff; font-size:22px; font-weight:bold;'>
                                New Course Enquiry
                            </td>
                        </tr>

                        <tr>
                            <td style='padding:30px; color:#333;'>

                                <p style='font-size:16px; margin-bottom:20px;'>
                                    You have received a new enquiry from your website.
                                </p>

                                <table width='100%' cellpadding='8' cellspacing='0' style='font-size:15px;'>

                                    <tr>
                                        <td style='font-weight:bold;'>Full Name:</td>
                                        <td>$name</td>
                                    </tr>

                                    <tr>
                                        <td style='font-weight:bold;'>Email:</td>
                                        <td>$email</td>
                                    </tr>

                                    <tr>
                                        <td style='font-weight:bold;'>Phone:</td>
                                        <td>$phone</td>
                                    </tr>

                                    <tr>
                                        <td style='font-weight:bold;'>Course:</td>
                                        <td>$course</td>
                                    </tr>

                                </table>

                                <div style='margin-top:25px; padding:15px; background:#f1f4f8; border-radius:6px;'>
                                    <strong>Message:</strong><br><br>
                                    $message
                                </div>

                            </td>
                        </tr>

                        <tr>
                            <td style='background:#f1f4f8; padding:15px; text-align:center; font-size:13px; color:#777;'>
                                This email was generated from your website enquiry form.
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
        </table>

    </body>
    </html>
    ";

    // HEADERS
    $headers  = "From: Website Enquiry <noreply@yourdomain.com>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (mail($EmailTo, $subject, $Body, $headers)) {
        echo "success";
    } else {
        echo "Something went wrong. Please try again.";
    }

} else {
    echo $errorMSG;
}

?>