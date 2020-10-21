<?php
namespace EzySignSdk;

interface EzySignSdkInterface
{
    public function login($email, $password);
    public function refresh_token();
    public function set_meta($token);
    public function get_templates();
    public function get_templates_by_id($id);
    public function post_document($documentPayload);
    public function get_document($documentID);
}
