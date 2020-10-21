# EzySign API Documentation
  * [Installation](#Installation)
  * [Authentication](#authentication)
    + [Client Authentication(WIP)](#client-authentication-wip-)
    + [User Authentication](#user-authentication)
  * [Templates](#templates)
  * [Document](#document)
  * [Sending Document](#sending-document)
  * [Callback URL](#callback-url)
  * [Errors](#errors)
  * [Usage](#usage)


## Installation
Add this to your composer.json
```json
{
    // other properties
    "require": {
        "ezysign/phpsdk":"dev-master",
        "guzzlehttp/guzzle": "^7.2",
        "firebase/php-jwt": "dev-master"
    },
    "repositories":[
        {
            "type": "vcs",
            "url": "git@github.com:ezysign/ezysign-php-sdk.git"
        }
    ]
}

```
and run follow
```bash
composer require ezysign/phpsdk:dev-master
```

## Authentication

EzySign api requires two steps of authentication.

 -  Client Authentication or Application Authentication
 -  User Authentication 

### Client Authentication(WIP)
- `client_id` & `client_secret` that registered with EzySign will be used as body payload to get client token
- After that you will `token` & `refresh_token` that you may keep the token at somewhere safe

| Method | Route | payload	|
|--|--|--|
| POST |  |  `{"client_id":"","client_secret":""}` |

### User Authentication
- User authentication was handled by EzySign Resource  (i.e you will get user name after [registration](https://dashboard.ezysign.cc) was complete. 
- Pass that User name into EzySign SDK by calling like this
```php
use EzySignSdk\EzySign;

$ezysign  =  new EzySign();
$ezysign->login("username@company.co", "123456");

// Refresh token
$ezysign->refresh_token();
```


## Templates
A predefined source of uploaded document pages  including fields information along with Positions.
In-order to get templates you can access as follow.

```php
use EzySignSdk\EzySign;

$ezysign  =  new EzySign();
$ezysign->login("username@company.co", "123456");

// Refresh token
$ezysign->refresh_token();
// List of templates
$ezysign->get_templates();
// get Single Template
$ezysign->get_templates_by_id("d3ba9b6a-299e-4046-adee-cdb7fb823b19");
```
## Document
A source of both template and fields including tagged recipients which is ready to be sent out.
**Preperation**
Inorder to prepare a document followings parameters are required.
	
| Name | Required |  Description|
|--|--|--|
| Recipient List | true | List of Recipients `Array ( [0] => Array ( [name] => WIN HTAIK AUNG [email] => winhtaikaung28@hotmail.com [phone_number] => ) )`  |
| Recipient List Field Mapping | true | List of Recipients `array('winhtaikaung76@gmail.com' => [1, 3])`  |
| SenderID | true | It will receive after successful login  |
| GroupID | true | represents that envelope belongs to the organisation.  |
| DocumentName | false | It will be Untitled if it was not provided.  |


```php
$signDoc  =  new SignDocument($template, $ezysign->get_group_id(), $ezysign->get_sender_id(), null);
//naming the Document
$signDoc->set_document_name("Hello.pdf");
$recipientJson  =  json_decode('[
{
"name": "WIN HTAIK AUNG",
"email": "winhtaikaung2@hotmail.com",
"phone_number": ""
}]', true);

$recipients = $signDoc->generate_recpients($recipientJson);
$mapping = array('winhtaikaung2@hotmail.com' => [1, 3]);

// This is the payload generation based on the data
$payload = $signDoc->get_signdocument_payload($recipients, $mapping);


```


## Sending Document
Sending document calls the POST api to create a Document. If the document was successfully created it will automatically increase the API count by one. 

 ```php
 $signDocument=$ezysign->post_document($payload);
 ```

## Callback URL
To be implemented

## Errors

**EzySignException("Invalid Recipient count");**
This error will be raised  when recipients inside field mapping and recipients  were not exactly the same.

**EzySignException("Fields exceed than provided recipient. Please check the template fields count.");**
This error will be raised when the recipient field mapping is exceeded than available fields.

**EzySignException("More than one Recipients were assigned to same field.Please check recipientsfieldmapping Index");**
This error will be raised if the same fields were assigned by more than one recipient.

## Usage
```php
use EzySignSdk\EzySign;
use EzySignSdk\SignDocument;
use EzySignSdk\Helper;

  

$ezysign  =  new  EzySign();
$ezysign->login("win@ezysign.cc", "123456");
$ezysign->refresh_token();


$template  =  $ezysign->get_templates_by_id("Your template id");

  

$signDoc  =  new  SignDocument($template, $ezysign->get_group_id(), $ezysign->get_sender_id(), null);

$dec  =  json_decode('[
	{
	"name": "name",
	"email": "name@hotmail.com",
	"phone_number": ""
	}
]', true);

  

$signDoc->set_document_name("Your document name");
$recipients  =  $signDoc->generate_recpients($dec);

  

$mapping  =  array('name@gmail.com'  => [1, 3]);
$payload  =  $signDoc->get_signdocument_payload($recipients, $mapping);

$signDocument=$ezysign->post_document($payload);
// get sent document by id
$ezysign->get_document($signDocument["id"]);
```