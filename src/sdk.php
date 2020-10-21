<?php
namespace EzySignSdk;

require_once 'vendor/autoload.php';

require_once "entity.php";
require_once "helper.php";
require_once "http_client.php";
require_once "sign_document.php";



use EzySignSdk\EzySignHttpClient;
use EzySignSdk\Helper;


define("ezysign_env", array(
    "PRODUCTION" => "https://api.ezysign.cc",
    "SANDBOX" => "https://dev-api.ezysign.cc",
    "LOCAL" => "http://127.0.0.1:4006")
    , true);

class EzySign
{
    // Properties
    public $http_client;
    public $clientid;
    public $clientsecret;
    public $group_id;
    public $sender_id;
    public $username;
    public $password;
    public $helper;

    private $token;
    private $url;

    public function __construct()
    {
        $env = getenv('EZY_SIGN_ENV',true);
        $this->helper= new Helper();
        $this->url = ($env != null)?ezysign_env[$env]:ezysign_env["LOCAL"];
        
        return $this;
    }
    private function set_token($token)
    {
        $this->token = $token;

    }
    private function get_token()
    {
        return $this->token;

    }

    public function set_sender_id($senderid)
    {
        $this->sender_id = $senderid;
    }
    public function get_sender_id()
    {
        return $this->sender_id;

    }

    public function get_group_id()
    {
        return $this->group_id;

    }

    public function set_group_id($groupId)
    {

        $this->group_id = $groupId;
    }

    public function get_helper()
    {
        return $this->helper;

    }

    public function set_helper($helper)
    {

        $this->helper = $helper;
    }
    /**
     * This method refresh the token and set the updated token in instance of EzySignSdk
     */
    public function login($email, $password)
    {
        $data = null;
        $http_client = new EzySignHttpClient($this->url);
        $promise = $http_client->post("/api/v1/user/login", [], ['email' => trim($email, " "), 'password' => trim($password, " ")]);
        if (isset($promise)) {
            if ($promise->getStatusCode() === 200) {
                $this->set_token(json_decode($promise->getBody(), true)["data"]);
                $this->set_meta($this->get_token());
            }
        }
    }
    /**
     * This method refresh the token and set the updated token in instance of EzySignSdk
     */
    public function refresh_token()
    {
        $data = null;
        $http_client = new EzySignHttpClient($this->url);
        $promise = $http_client->patch("/api/v1/user/token/refresh", ["Authorization" => $this->token], []);
        if (isset($promise)) {
            if ($promise->getStatusCode() === 200) {
                $this->set_token(json_decode($promise->getBody()->getContents(), true)["data"]);
                $this->set_meta($this->get_token());
            }
        }
    }

    public function set_meta($token)
    {
        $token = $this->helper->convert_jwt($token);

        if (array_key_exists("jti", $token)) {

            $this->set_sender_id($token["jti"]);
            $this->set_group_id(json_decode($token["sub"], true)["group_id"]);
        }

    }
    /**
     * Return Template List or empty array
     *
     *
     */
    public function get_templates()
    {
        $this->refresh_token();
        $http_client = new EzySignHttpClient($this->url);
        $promise = $http_client->get("/api/v1/template?filter=" . urlencode('{"limit":10000,"name":null,"save_as_template":"true","start_date":null,"end_date":null}'), ["Authorization" => $this->token]);
        if (isset($promise)) {
            if ($promise->getStatusCode() !== 200) {

                return [];
            }
            return json_decode($promise->getBody()->getContents(), true)["data"];
        }
        return [];

    }

    /**
     * Return template that includes all the field information and document
     *
     * @param string id
     */
    public function get_templates_by_id($id)
    {
        $this->refresh_token();
        $http_client = new EzySignHttpClient($this->url);
        $promise = $http_client->get("/api/v1/template/" . $id, ["Authorization" => $this->token]);
        if (isset($promise)) {
            if ($promise->getStatusCode() !== 200) {

                return null;
            }
            return json_decode($promise->getBody()->getContents(), true)["data"];
        }
        return null;

    }
    /**
     * Return sent document or null if faile Send out an envelope to all the recipients
     *
     * @param Document documentPayload
     */
    public function post_document($documentPayload)
    {
        $data = null;
        $http_client = new EzySignHttpClient($this->url);
        $promise = $http_client->post("/api/v1/signdocument/create", ["Authorization" => $this->token], $documentPayload);
        if (isset($promise)) {
            if ($promise->getStatusCode() !== 200) {
                return null;
            }
            return json_decode($promise->getBody(), true)["data"];
        }return null;

    }

    /**
     * Get Envelope by DocumentID
     *
     * @param documentID
     */
    public function get_document($documentID)
    {
        $data = null;
        $http_client = new EzySignHttpClient($this->url);
        $promise = $http_client->get("/api/v1/signdocument/" . $documentID, ["Authorization" => $this->token]);
        if (isset($promise)) {
            if ($promise->getStatusCode() !== 200) {

                return null;
            }
            return json_decode($promise->getBody()->getContents(), true)["data"];
        }return null;

    }

}


