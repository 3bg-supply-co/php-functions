# PHP Functions
This package is a collection of reusable classes and functions that are currently being used in many of our projects.

## Table of Contents
1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Usage](#usage)
   - [ApiResponse Class](#api-response-class)
   - [HttpPost Class](#http-post-class)
   - [GenericFunctions](#generic-functions)

<a name="requirements"></a>
## Requirements
In order to use this package, the following requirements must be met:
* **credentials.ini** file must exist *2 levels* above the root directory
  - For example: if the code is deployed to the following directory: ```/var/www/html/test/```, then the file would have to be located in the following directory: ```/var/www/credentials/credentials.ini```.
* [Composer](https://getcomposer.org/)
* PHP version **7.0+**
* [SSH Access](https://github.com/settings/keys)
  - Follow this [tutorial](https://help.github.com/en/github/authenticating-to-github/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent) to set up the SSH Key
  - You can test it by running the following command on the machine with the SSH Key: ```ssh -T git@github.com```

<a name="installation"></a>
## Installation
To install this package, the following steps must be followed (in order):
* Create a **composer.json** file in your project by running the following command: ```composer init```
* After going through the options, add the following code to the **composer.json** file:
```json
    "minimum-stability": "dev",
    "require": {
      "3bg-supply-co/php-functions": "^1.6"
    }
```
* Run ```composer update -n```

<a name="usage"></a>
## Usage
To use this collection of code, determine what your need is. All classes found in this package will use the base *namespace* **Common3BG**.

To use the logging function ***writeLog()***, the */tmp/* directory must have *write* permissions by the user executing the script.

For example, if all you need is access to the functions found in the ```src/GenericFunctions.php``` file, then just call said function in your script.

If you need to use one of the classes (such as **ApiResponse**), then you must include the proper *namespace*. For example, the following line of code is needed to use the **ApiResponse** class:
```php
  use Common3BG\ApiResponse as ApiResponse;
```

<a name="api-response-class"></a>
### ApiResponse Class
This class takes no parameters.

The following method

<details>
<summary><b>Sample PHP Code</b></summary>

```php
require (__DIR__) . '/vendor/autoload.php';
use Common3BG\ApiResponse as ApiResponse;

$apiResponse = new ApiResponse();

// Sample #1: simple one liner being added to message then returning the response
$apiResponse->addMessage('Testing a custom message');
$apiResponse->sendResponse();

// Sample #2: adding another message then returning the response
$apiResponse->addMessage('Testing a second custom message');
$apiResponse->sendResponse();

// Sample #3: sending a fail response without any parameters
$apiResponse->sendFailResponse();


// Sample #4: adding a string error message with no data, then returning the fail response
$apiResponse->sendFailResponse('Custom Error Message with no $data given');

// Sample $5: adding an error message with data to the errors, then returning the fail response
$apiResponse->sendFailResponse(['message' => 'Original error message from $data', 'test' => 'Some error sample here (test key is not needed)']);
$apiResponse->sendFailResponse();

// Sample #6: adding multiple errors to the errors, then returning the fail response
$apiResponse->addError(['message' => 'Custom Error Message #2', 'test' => 'Sample Error #2']);
$apiResponse->addError(['message' => 'Custom Error Message #3', 'test' => 'Sample Error #3']);
$apiResponse->sendFailResponse();
```
</details>

<details>
  <summary><b>Sample Success Response</b></summary>

```json
{
    "success": true,
    "message": "",
    "data": [
        "Testing a success message",
        {
            "sample": "Success #1 Message"
        }
    ],
    "errors": []
}
```
</details>

<details>
  <summary><b>Sample Failure Response</b></summary>

```json
{
    "success": false,
    "message": "",
    "data": [],
    "errors": [
        {
            "message": "Custom Error Message #1",
            "test": "Sample Error #1"
        },
        {
            "message": "Custom Error Message #2",
            "test": "Sample Error #2"
        },
        {
            "message": "Custom Error Message #3",
            "test": "Sample Error #3"
        },
        {
            "message": "Error Message.",
            "test": "Testing an object being added to the errors"
        }
    ]
}
```
</details>

<a name="http-post-class"></a>
### HttpPost Class
Documentation Coming Soon!

<a name="generic-functions"></a>
### GenericFunctions
Documentation Coming Soon!
