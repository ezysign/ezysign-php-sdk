<?php
namespace EzySignSdk;
/** Field Classes */

class Recipient
{
    public $id; //String
    public $tnc_agreed; //boolean
    public $status; //String
    public $name; //String
    public $email; //String
    public $phone_number; //String

}
class SignformMeta
{
    public $imageUrl; //String
    public $userId; //String

}
class Signaturefield
{
    public $name; //String
    public $text; //String
    public $userid; //String
    public $signformMeta; //SignformMeta

}
class Meta
{
    public $recipient; //Recipient
    public $signaturefield; //Signaturefield
    public $required; //boolean
    public $verificationRequired; //boolean

}
class Field
{
    public $objectId; //String
    public $top; //int
    public $left; //int
    public $title; //String
    public $itemType; //String
    public $meta; //Meta

}


class Template {
    public $created_at; //Date
    public $deleted_at; //Date
    public $document_id; //String
    public $fields; //array( Field )
    public $group_id; //String
    public $id; //String
    public $is_protected; //boolean 
    public $name; //String
    public $save_as_template; //boolean 
    public $template_name; //String
    public $template_url; //String
    public $updated_at; //Date
    public $user_id; //String
   
   }