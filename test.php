<?php
require (__DIR__) . '/vendor/autoload.php';
use Common3BG\ApiResponse as ApiResponse;

$apiResponse = new ApiResponse();
$apiResponse->addData('Testing a success message');
$apiResponse->addData(['sample' => 'Success #1 Message']);
$apiResponse->sendResponse();
// $apiResponse->sendFailResponse("Custom Message appended to 'errors'");
// $apiResponse->addError(['message' => 'Custom Error Message #1', 'test' => 'Sample Error #1']);
// $apiResponse->addError(['message' => 'Custom Error Message #2', 'test' => 'Sample Error #2']);
// $apiResponse->addError(['message' => 'Custom Error Message #3', 'test' => 'Sample Error #3']);
// $apiResponse->sendFailResponse();
// $apiResponse->sendFailResponse((object) ['message' => 'Error Message.', 'test' => 'Testing an object being added to the errors']);
// $apiResponse->sendFailResponse();
?>
