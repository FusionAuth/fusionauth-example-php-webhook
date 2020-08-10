<?php
require __DIR__. '/config.php';
require __DIR__ . '/vendor/autoload.php';

$headers = getallheaders();
if (!$headers) {
  error_log("Invalid authorization header.");
  return;
}

$authorization_value = $headers['Authorization'];
if ($authorization_value !== $authorization_header_value) {
  error_log("Invalid authorization header value found: ".$authorization_value);
  return;
}

$input = file_get_contents('php://input');

$obj = json_decode($input);
if (!$obj) { 
  error_log("Invalid JSON");
  return;
}

$type = $obj->event->type;
if ($type !== "user.password.breach") {
  error_log("Sorry, we only handle breached password events.");
  return;
}

$user_id = $obj->event->user->id;
if (!$user_id) {
  error_log("No user id");
  return;
}
$client = new FusionAuth\FusionAuthClient($api_key, $fa_url);
$response = $client->deactivateUser($user_id);
if (!$response->wasSuccessful()) {
  // uh oh
  error_log("Response wasn't successful:");
  error_log(var_export($response, TRUE));
  return;
}
http_response_code(500);
?>
