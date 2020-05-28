<?php
/**
 * This class is used to create POST requests. It is capable of encoding the data as JSON, if requested.
 *
 * @author     Benjamin Saralegui <ben@3bgsupply.com>
 *
 * Date Created: 05/27/20
 */

namespace Common;

// This class is used for building the cUrl setup in a class form
class HttpPost
{
  /**
   * This is the URL endpoint that will receive the POST request
   * @var string
   */
  public $url;

  /**
   * This is the URL string that will be used to POST, in the case that JSON is not selected
   * @var string
   */
  public $postString;

  /**
   * This is the response that will be received from the POST request
   * @var stdClass
   */
  public $httpResponse;

  /**
   * This is the cURL object that will be used to build and execute the POST request
   * @var stdClass
   */
  public $ch;

  /**
   * By default, this class will require a URL endpoint to POST to.
   * @param string $url This is the base URL endpoint.
   */
  public function __construct($url)
  {
      $this->url = $url;
      $this->ch = curl_init($this->url);
      curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, false);
      curl_setopt($this->ch, CURLOPT_HEADER, false);
      curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
  }

  public function __destruct()
  {
      curl_close($this->ch);
  }

  /**
   * This method will build the POST data in either url-encoded or JSON format
   * @param array  $params     The input parameters in array format
   * @param boolean $jsonObject By default, this will assume that a JSON string will be built. Set to false for url-encoded
   */
  public function setPostData($params, $jsonObject = true)
  {
      curl_setopt($this->ch, CURLOPT_POST, true);

      // Check to see if the params should be forcefully encoded as a JSON object
      if ($jsonObject === false) {
          // http_build_query encodes URLs, which breaks POST data
          $this->postString = rawurldecode(http_build_query($params));
          curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->postString);
      } else {
          $jsonEncoded = json_encode($params, $jsonObject);

          // Since it should be sent as a jsonEncoded, then set the POST a little differently using the jsonObject as the json_encode parameter
          curl_setopt($this->ch, CURLOPT_POSTFIELDS, $jsonEncoded);
      }
  }

  /**
   * Send the POST request
   * @return void The response will be set to the $httpResponse property
   */
  public function send()
  {
      $this->httpResponse = curl_exec($this->ch);
  }
}
