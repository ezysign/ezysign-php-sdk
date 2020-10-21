<?php
namespace EzySignSdk;
require_once "uuid.php";
require_once "exception.php";


use EzySignSdk\EzySignException;
use EzySignSdk\UUID;

class SignDocument
{

    private $recipients = [];
    private $fields = [];
    private $documentName = 'Untitled';
    private $expiry_date;
    private $senderid='';
    private $groupid='';
    private $template = null;

    public function __construct($template,$groupid,$senderid,$expiry_date)
    {
        $helper=new Helper();
        $this->template = $template;
        $this->expiry_date= isset($expiry_date) ? $expiry_date: $helper->get_two_week();
        $this->senderid=$senderid;
        $this->groupid=$groupid;
        return $this;
    }

    

    public function set_recipients($recipients)
    {
        $this->recipients = $recipients;
    }
    public function get_recipients()
    {
        return $this->recipients;
    }

    public function set_fields($fields)
    {
        $this->fields = $fields;
    }
    public function get_fields()
    {
        return $this->fields;
    }

    public function set_document_name($documentName)
    {
        $this->documentName = $documentName;
    }
    public function get_document_name()
    {
        return $this->documentName;
    }

    public function set_template($template)
    {
        $this->template = $template;
    }
    public function get_template()
    {
        return $this->template;
    }

    public function set_senderid($senderid)
    {
        $this->senderid = $senderid;
    }
    public function get_senderid()
    {
        return $this->senderid;
    }

    public function set_groupid($groupid)
    {
        $this->groupid = $groupid;
    }
    public function get_groupid()
    {
        return $this->groupid;
    }

    public function generate_recpients($recipientsMeta = [])
    {
        return array_map(function ($item) {
            $r = new Recipient();
            $r->id = UUID::v1($item["email"]);
            $r->name = $item["name"];
            $r->status = "DRAFT";
            $r->email = $item["email"];
            $r->tnc_agreed = false;
            $r->phone_number = $item["phone_number"];
            return $r;
        }, $recipientsMeta);
    }

    public function get_signdocument_payload($recipients = [], $recipientsfieldmapping)
    {

        $result = array_filter($recipients, function (Recipient $item) use ($recipientsfieldmapping) {
            return array_key_exists($item->email, $recipientsfieldmapping);
        });


        if (count($result) !== count($recipientsfieldmapping)) {
            throw new EzySignException("Invalid Recipient count");
        }

        $fieldIndexArray = [];

        foreach ($recipients as &$item) {
            foreach ($recipientsfieldmapping[$item->email] as $key=>&$fieldIndex) {
                array_push($fieldIndexArray,$fieldIndex);
                
            }

        }
        $templateFields = $this->template["fields"];

        if (count(array_unique($fieldIndexArray)) !== count($templateFields)){
            throw new EzySignException("Fields exceed than provided recipient. Please check the template fields count.");
        }
        
        if (count(array_unique($fieldIndexArray)) !== count($fieldIndexArray)){
            throw new EzySignException("More than one Recipients were assigned to same field.Please check recipientsfieldmapping Index");
        }
    

        

       
        foreach ($recipients as &$item) {
            foreach ($recipientsfieldmapping[$item->email] as &$fieldIndex) {
                
                $templateFields[$fieldIndex]["meta"]["recipient"]["email"] = $item->email;
                $templateFields[$fieldIndex]["meta"]["recipient"]["id"] = $item->id;
                $templateFields[$fieldIndex]["meta"]["recipient"]["phone_number"] = $item->phone_number;
                $templateFields[$fieldIndex]["meta"]["recipient"]["name"] = $item->name;
               
            }
        }

        
        $this->template["fields"]=$templateFields;
        $signDocumentPayload=array(
            "template_id"=>$this->get_template()["id"],
            "group_id"=>$this->get_groupid(),
            "expiry_date"=>$this->expiry_date,
            "subject"=>"",
            "message"=>"",
            "sign_document_name"=>$this->get_document_name(),
            "sender_id"=>$this->get_senderid(),
            "fields"=>$this->template["fields"],
            "recipients"=>$recipients
        );
        
        return $signDocumentPayload;
        // print_r(json_encode($this->template));
    }

}
