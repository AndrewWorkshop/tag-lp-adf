<?php
date_default_timezone_set('America/New_York');
// This is a sample PHP script that demonstrates accepting a POST from the        
// Unbounce form submission webhook, and then sending an email notification.      
function stripslashes_deep($value) {
  $value = is_array($value) ?
    array_map('stripslashes_deep', $value) :
    stripslashes($value);
 
  return $value;
}

// First, grab the form data.  Some things to note:                               
// 1.  PHP replaces the '.' in 'data.json' with an underscore.                    
// 2.  Your fields names will appear in the JSON data in all lower-case,          
//     with underscores for spaces.                                               
// 3.  We need to handle the case where PHP's 'magic_quotes_gpc' option           
//     is enabled and automatically escapes quotation marks.                      
if (get_magic_quotes_gpc()) {
  $unescaped_post_data = stripslashes_deep($_POST);
} else {
  $unescaped_post_data = $_POST;
}
$form_data = json_decode($unescaped_post_data['data_json']);
 
// If your form data has an 'Email Address' field, here's how you extract it:     
$email = $form_data->email[0];
$name = $form_data->name[0];
$phone = $form_data->phone[0];
$date = date("F j, Y, g:i a");  

 
// Assemble the body of the email...                                              
$message_body = <<<EOM
<?ADF VERSION "1.0"?>
<?XML VERSION “1.0”?>
<adf>
<prospect>
<requestdate>2000-03-30T15:30:20-08:00</requestdate>
<vehicle>
<year>1999</year>
<make>Chevrolet</make>
<model>Blazer</model>
</vehicle>
<customer>
 <contact>
 <name part="full">John Doe</name>
 <phone>393-999-3922</phone>
 </contact>
 </customer>
<vendor>
 <contact>
 <name part="full">Acura of Bellevue</name>
 </contact>
</vendor>
</prospect>
</adf>
EOM;

$headers .= 'From: ' . $email . "\r\n";
 
mail('andrew@workshopdigital.com',
     'TAG Lead',
     $message_body, $headers);
?>