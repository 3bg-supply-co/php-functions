<?php
/**
 * This class is used to build a standardized API response structure for use when responding to a GET or POST request.
 *
 * @author     Benjamin Saralegui <ben@3bgsupply.com>
 *
 * Date Created: 05/27/20
 */

namespace Common3BG;


/**
 * This generic class is used to build the response JSON string that will be sent back to the requester
 */
class API_Response {
  /**
   * This property will contain the data requested
   * @var array
   */
  public $data;

  /**
   * This property will contain the input data that caused an error. This will be useful to let the requester know what cause the issue
   * @var array
   */
  public $errors;

  /**
   * This property will be the output log(s)/message(s) to the requester regarding the process that was run as a result of the request
   * @var string
   */
  private $message;

  /**
   * This property will notify the requester if the overall process succeeded or failed
   * @var boolean
   */
  private $success;

  public function __construct(){
    $this->message = "";
    $this->data = [];
    $this->errors = [];
    $this->success = true;
  }

  /**
   * This method will add to the 'message' property
   * @param string $text The message text that will be added to the 'message' property
   */
  public function addMessage($text){
    // Add the $text to the $message
    $this->message .= ($this->message == "" ? $text : ". " . $text);
  }

  /**
   * This method is used to set the response as a failure
   */
  public function setFailure(){
    $this->success = false;
  }

  /**
   * This method is used to add any data type to the 'data' property
   * @param $input This can be any data type as it will be added to the 'data' array as a new element. Conventionally, add data that is relevant to what the requester was requesting
   */
  public function addData($input) {
    $this->data[] = $input;
  }

  /**
   * This method is used to add any data type to the 'errors' property
   * @param $input This can be any data type as it will be added to the 'data' array as a new element. Conventionally, only data from the request should be added here, to help the requester understand what caused the error
   */
  public function addError($input) {
    $this->errors[] = $input;
  }

  /**
   * This method is used to return the response to the requester
   * @return string This method will return the object as a JSON string to the requester
   */
  public function sendResponse(){
    // Build the response
    $jsonString = (object) [
      "success" => $this->success,
      "message" => $this->message,
      "data"    => $this->data,
      "errors"  => $this->errors,
    ];
    $encodedJson = json_encode($jsonString);
    header('Content-Type: application/json;charset=utf-8');
    echo $encodedJson;
    writeLog("Send Response JSON String: " . $encodedJson);
  }

  /**
   * This is used to throw a quick failure response, in order to avoid having to build all of the response data. It will also throw an exception
   * @param  string $message This text will be added to the 'message' property
   * @param         $data    This optional can be of any data parameter can be of any data type, as it will be added to the 'errors' property
   * @return string          This method will call the sendResponse() method, which will output a JSON string
   */
  public function quickFailResponse($message, $data = null) {
    // Build the requirements of the response
    $this->addMessage($message);
    $this->setFailure();
    if($data != null) {
      $this->addError($data);
    }

    // Now send the response
    $this->sendResponse();

    // Throw an exception with this information
    throw new \Exception($this->message, 1);
  }
}
