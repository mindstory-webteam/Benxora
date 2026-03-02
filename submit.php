<?php
header('Content-Type: application/json; charset=UTF-8');

// BLOCK DIRECT ACCESS
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Access denied."]);
    exit;
}

// SANITIZE FUNCTION
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim((string)$data)));
}

$errorMSG = "";

// GET DATA
$name    = clean_input($_POST["name"] ?? '');
$email   = clean_input($_POST["email"] ?? '');
$phone   = clean_input($_POST["phone"] ?? '');
$course  = clean_input($_POST["course"] ?? '');
$message = clean_input($_POST["message"] ?? '');

// VALIDATION
if ($name === "") $errorMSG .= "Full Name is required. ";
if ($email === "") $errorMSG .= "Email is required. ";
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errorMSG .= "Enter valid email address. ";
if ($phone === "") $errorMSG .= "Phone is required. ";
if ($course === "") $errorMSG .= "Please select a course. ";
if ($message === "") $errorMSG .= "Message is required. ";

if ($errorMSG !== "") {
    echo json_encode(["status" => "error", "message" => trim($errorMSG)]);
    exit;
}

$EmailTo = "janavalsan@mindstory.in";
$subject = "New Course Enquiry from Website";

// ================= EMAIL TEMPLATE =================

$Body = "
<html>
<head>
<meta charset='UTF-8'>
</head>
<body style='margin:0; padding:0; background:#f4f6f9; font-family:Arial, sans-serif;'>

<table width='100%' cellpadding='0' cellspacing='0' style='padding:30px 0; background:#f4f6f9;'>
<tr>
<td align='center'>

<table width='600' cellpadding='0' cellspacing='0' style='border-collapse:separate;'>

<tr>
<td style='background:#ffffff; border-radius:14px;'>

<!-- HEADER -->
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td style='background:#007272; padding:22px; text-align:center; color:#ffffff; font-size:22px; font-weight:bold; border-top-left-radius:14px; border-top-right-radius:14px;'>
New Course Enquiry
</td>
</tr>
</table>

<!-- BODY CONTENT -->
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td style='padding:30px; color:#333333;'>

<p style='font-size:16px; margin-bottom:20px;'>
You have received a new enquiry from your website.
</p>

<table width='100%' cellpadding='8' cellspacing='0' style='font-size:15px; border-collapse:collapse;'>

<tr>
<td style='font-weight:bold; width:150px;'>Full Name:</td>
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

<div style='margin-top:25px; padding:18px; background:#f1f4f8; border-radius:8px;'>
<strong>Message:</strong><br><br>
$message
</div>

</td>
</tr>
</table>

<!-- FOOTER -->
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td style='background:#f1f4f8; padding:16px; text-align:center; font-size:13px; color:#777777; border-bottom-left-radius:14px; border-bottom-right-radius:14px;'>
This email was generated from your website enquiry form.
</td>
</tr>
</table>

</td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>
";

// ================= HEADERS =================

$headers  = "From: Website Enquiry <noreply@yourdomain.com>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if (mail($EmailTo, $subject, $Body, $headers)) {
    echo json_encode(["status" => "success", "message" => "Thank you! Your enquiry has been sent successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Mail sending failed. Please try again later."]);
}

exit;
?>