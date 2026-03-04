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

// GET & VALIDATE DATA
$name     = clean_input($_POST["name"]            ?? '');
$email    = clean_input($_POST["email"]           ?? '');
$phone    = clean_input($_POST["phone"]           ?? '');
$category = clean_input($_POST["course_category"] ?? '');
$course   = clean_input($_POST["course"]          ?? '');
$message  = clean_input($_POST["message"]         ?? '');

// Use category as fallback if specific course not selected (e.g. Nursing, GNM)
$courseDisplay = $course !== '' ? $course : $category;

// VALIDATE
$errorMSG = "";
if ($name === "")          $errorMSG .= "Full Name is required. ";
if ($email === "")         $errorMSG .= "Email is required. ";
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errorMSG .= "Enter valid email address. ";
if ($phone === "")         $errorMSG .= "Phone is required. ";
if ($category === "")      $errorMSG .= "Please select a course category. ";
if ($courseDisplay === "") $errorMSG .= "Please select a course. ";
if ($message === "")       $errorMSG .= "Message is required. ";

if ($errorMSG !== "") {
  echo json_encode(["status" => "error", "message" => trim($errorMSG)]);
  exit;
}

// EMAIL SETTINGS
$EmailTo = "janavalsan@mindstory.in";
$subject = "New Course Enquiry from Website";

// HTML EMAIL TEMPLATE
$Body = "
<html><head><meta charset='UTF-8'></head>
<body style='margin:0; padding:0; background:#f4f6f9; font-family:Arial, sans-serif;'>
  <table width='100%' cellpadding='0' cellspacing='0' style='background:#f4f6f9; padding:30px 0;'>
    <tr><td align='center'>
      <table width='600' cellpadding='0' cellspacing='0' style='background:#ffffff; border-radius:10px; overflow:hidden;'>
        <tr>
          <td style='background:linear-gradient(90deg, #007272 0%, #005e7a 50%, #091e3a 100%); padding:20px; text-align:center; color:#ffffff; font-size:22px; font-weight:bold;'>
            New Course Enquiry
          </td>
        </tr>
        <tr>
          <td style='padding:30px; color:#333;'>
            <p style='font-size:16px; margin-bottom:20px;'>You have received a new enquiry from your website.</p>
            <table width='100%' cellpadding='8' cellspacing='0' style='font-size:15px; border-collapse:collapse;'>
              <tr style='background:#f8f9fa;'><td style='font-weight:bold; width:160px; padding:10px 8px;'>Full Name</td><td style='padding:10px 8px;'>$name</td></tr>
              <tr>                            <td style='font-weight:bold; padding:10px 8px;'>Email</td>      <td style='padding:10px 8px;'><a href='mailto:$email' style='color:#007878;'>$email</a></td></tr>
              <tr style='background:#f8f9fa;'><td style='font-weight:bold; padding:10px 8px;'>Phone</td>     <td style='padding:10px 8px;'>$phone</td></tr>
              <tr>                            <td style='font-weight:bold; padding:10px 8px;'>Category</td>  <td style='padding:10px 8px;'>$category</td></tr>
              <tr style='background:#f8f9fa;'><td style='font-weight:bold; padding:10px 8px;'>Course</td>   <td style='padding:10px 8px;'>$courseDisplay</td></tr>
            </table>
            <div style='margin-top:25px; padding:15px; background:#f1f4f8; border-radius:6px;'>
              <strong>Message:</strong><br><br>$message
            </div>
          </td>
        </tr>
        <tr>
          <td style='background:#f1f4f8; padding:15px; text-align:center; font-size:13px; color:#777;'>
            This email was generated from your website enquiry form.
          </td>
        </tr>
      </table>
    </td></tr>
  </table>
</body></html>
";

// HEADERS
$headers  = "From: Website Enquiry <noreply@benxora.in>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if (mail($EmailTo, $subject, $Body, $headers)) {
  echo json_encode(["status" => "success", "message" => "Thanks! Your enquiry has been sent successfully."]);
} else {
  echo json_encode(["status" => "error", "message" => "Mail sending failed. Please try again later."]);
}
exit;