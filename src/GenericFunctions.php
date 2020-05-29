<?php
/**
 * This class is used to create POST requests. It is capable of encoding the data as JSON, if requested.
 *
 * @author     Benjamin Saralegui <ben@3bgsupply.com>
 *
 * Date Created: 05/27/20
 */

/**
 * This function is just a universal function to convert a number to a letter and works past Z. Found the solution here: https://stackoverflow.com/questions/3302857/algorithm-to-get-the-excel-like-column-name-of-a-number
 * @param  string $n The numeric character to be converted to an alpha character
 * @return string    The alpha character representation of the number given will be returned. E.g. $n = 27 will return "AA"
 */
 function num2alpha($n)
 {
   for ($r = ""; $n >= 0; $n = intval($n / 26) - 1) {
     $r = chr($n%26 + 0x41) . $r;
   }
   return $r;
 }

 /**
 * This function converts a comma separated list of numbers, which can include an abbreviated range, and separate them out into individual array elements. E.g. $input = "1-10,12" will return [1,2,3,4,5,6,7,8,9,10,12]
 * @param  string $input The input number(s) or list of numbers to be split into individual numbers
 * @return array         The return value will always be an array, even if only one number was given
 */
 function numberListToArray($input)
 {
   // Declare the variables for this function
   $output = [];

   // Begin by splitting the input by using the comma as the delimiter
   $inputSplit = explode(",", $input);

   // Just for good measure, filter out any blank values that might have been in there
   $inputSplit = array_filter($inputSplit);

   // Now loop through the inputSplit
   foreach ($inputSplit as $split) {
     // Check to see if this split has a hyphen
     if (strpos($split, "-") === false) {
       $output[] = trim($split);
     } else {
       // Since this split has a hyphen, split it by the hyphen and set it to the minMax array
       $minMax = explode("-", $split);

       // Now check to see if the first element before the hyphen is actually less than the element after the hyphen
       if ($minMax[0] <= $minMax[1]) {
         // Since the first element is less than or equal to the second element, create a numerical range from the first to the second element as an array and append it to the output
         $output = array_merge($output, range(trim($minMax[0]), trim($minMax[1])));
       }
     }
   }

   // Now return the output
   return $output;
 }

 /**
  * This function converts an array of numbers to a list with ranges. E.g. $input = [1,2,3,4,6,7,10] will return "1-4,6-7,10"
  * @param  array $input The array of numbers to be converted to a comma separated range of numbers
  * @return string       The return value will be a string of comma separated range of numbers
  */
 function arrayToNumberList($input)
 {
   // Declare the variables for this function
   $nextSequentialNumber = $range = $beginRangeNumber = $previousNumber = false;
   $output = '';

   // Start by removing duplicates, since they could mess up the logic, and then sort the result
   $uniqueInput = array_unique($input);
   sort($uniqueInput);

   // Loop through the input
   foreach ($uniqueInput as $number) {
     // Begin by checking to see if a range has begun
     if ($nextSequentialNumber === false) {
       // Since there was no previous number, that means this is the first number so just add it to the output
       $output .= $number;
     } else {
       // Since this is not the first number in the array, check to see if this number is equal to the nextSequentialNumber, which would mean that it is part of a range
       if (($number == $nextSequentialNumber) && ($range === false)) {
         // Since this number is the next number in the sequence, set the range to TRUE
         $range = true;
       } elseif (($number != $nextSequentialNumber) && ($range === false)) {
         // Since this number is not next in the sequence and there was no range started, add the comma, then this number
         $output .= ', ' . $number;
       } elseif (($number != $nextSequentialNumber) && ($range === true)) {
         // Since this number is not next in the sequence, but there was a range started, close the range using the previousNumber and add the comma, then this number. Also, set the range to FALSE
         $output .= '-' . $previousNumber . ',' . $number;
         $range = false;
       }
     }

     // Always set the nextSequentialNumber according to the current number as well as the previousNumber
     $nextSequentialNumber = $number + 1;
     $previousNumber = $number;
   }

   // Before finalizing the output, the last number might have to be added. Check the range to see if it should be added to the end of the output or not
   if ($range === true) {
     // Since it was set to TRUE on the last number, that means that this number was never added to the output, so add it to the output
     $output .= '-' . $number;
   }

   // After all the numbers in the input have been looped through, return the output
   return $output;
 }

/**
 * This is a simple function to get the given date/time (current date/time by default) according to EST, instead of UTC (since that is what the server will spit date() as). It will accept a parameter to determine what format is desired for the result.
 *
 * @param  string   $dateFormat      is the desired output format, which will be useful when not returning the object.
 * @param  boolean  $returnObject    is an optional parmeter used to flag the function to return the string format desired or the DateTime object that was created.
 * @param  string   $initialDateTime is an optional parameter used to specify the date/time that the object will use during creation.
 * @param  string   $timeZone        is an optional parameter used to specify the timezone that the object will use during creation.
 *
 * @return string/stdClass           This function will return a string formatted based on the inputs, or a DateTime object (if that's how it was requested).
 */
function getToday($dateFormat = 'Y-m-d', $returnObject = false, $initialDateTime = 'now', $timeZone = 'America/Indiana/Indianapolis')
{
  // Set the proper date, since calling date('m-d-y') fails to point to today's date after 6:59pm since the server time is in UTC, effectively changing it to tomorrow
  $dateTime = new DateTime($initialDateTime, new DateTimeZone($timeZone));

  // Check to see if the returnObject was set to TRUE
  if ($returnObject === true) {
    // Since it was TRUE, that means that the request was for the object of dateTime with the instance already being set according to local timezone of Indiana/Indianapolis, so return dateTime
    return $dateTime;
  } else {
    // Since it was FALSE, that means that the request was for the string time using the given dateFormat, so return that
    return $dateTime->format($dateFormat);
  }
}

/**
 * This function is used to set the SCRIPT_NAME constant
 */
function setScriptName()
{
    // Get the trace of who called this script and remove the ".php" suffix
    $backtrace = debug_backtrace();
    define('SCRIPT_NAME', basename(end($backtrace)['file'], '.php'));
}

/**
 * This function is used to create and write to a log file
 *
 * @method writeLog
 *
 * @param  string   $message is the text that will be written to the next line on the current $logFile
 * @param  string   $logFile is the custom name that will be used for the log file. This will default to the script name if not given.
 *
 */
function writeLog($message, $logFile = null)
{
  // Set the SCRIPT_NAME, if needed
  defined('SCRIPT_NAME') or setScriptName();

  // Set the variables for this function
  static $logFileName = null;
  $directory = '/tmp/' . SCRIPT_NAME . '/';

  // Check to see if $logFileName is null
  if ($logFileName == null) {
    // Since it has not been set yet, set it
    $logFileName = ($logFile == null ? getToday('m-d-y_H:i:s') . '.txt' : $logFile . '.txt');
  }

  // Check to make sure that this directory exists
  if (!file_exists($directory)) {
    exec('mkdir -p ' . $directory . '; chmod 777 ' . $directory);
  }

  // Open the file (create if needed) and append the $message to the next line
  if (($handle = fopen($directory . $logFileName, 'a')) === false) {
    throw new Exception('Failed to open the Log file');
  }
  // Now write to the file
  if (fwrite($handle, $message . "\r\n") === false) {
    throw new Exception('Failed to write to the Log file');
  }
  // Now close the file
  if (fclose($handle) === false) {
    // Since it failed to close the $logFile, throw an exception
    throw new Exception('Failed to close the Log file');
  }
}

/**
 * This function is used to parse the credentials file and define the $request section(s) of credentials. An array of one or multiple credential sections can be given and the function will define the constants with the names as they appear in the file
 * @method getCredentials
 * @param  array   $request is the array of sections that MUST match what exists in the $credentials file in order to work
 */
function getCredentials($request)
{
    // Set the variables for this function
    $credentials = realpath(__DIR__ . '/../..') . '/credentials/credentials.ini';

    // Before anything, check to make sure there was a $request given
    if (!is_array($request) || empty($request)) {
        // Since there was no proper $request supplied, throw an exception
        throw new \Exception('No array Request given for Credentials, so no credentials will be given. Request given was: ' . print_r($request, 1));
    }

    // Since there was a $request given, begin parsing the $credentials file
    $parsedCredentials = parse_ini_file($credentials, true);

    // Now loop the $request to define the variables
    foreach ($request as $section) {
        if (!isset($parsedCredentials[$section])) {
            // For debugging purposes
            writeLog("Requested Credentials section of: " . $section . " was not found in the Credentials file. Will continue to the next requested section");
            continue;
        }

        // Since the $section was set, then loop through the $section do define the variables
        foreach ($parsedCredentials[$section] as $globalVariable => $value) {
            defined($globalVariable) or define($globalVariable, $value);
        }
    }
}
