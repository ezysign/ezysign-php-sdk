<?php
require_once('src/index.php');


use EzySignSdk\EzySign;
use EzySignSdk\SignDocument;
use EzySignSdk\Helper;

$ezysign = new EzySign();
$ezysign->login("win@ezysign.cc", "123456");
$ezysign->refresh_token();

// print_r($ezysign->get_sender_id());

// print_r(json_encode($ezysign->get_templates()));
// print_r(json_encode($ezysign->get_templates_by_id("07441809-06bd-48fa-9492-1ee3d9d11afb")));
// echo $ezysign->get_token();

$template = $ezysign->get_templates_by_id("d3ba9b6a-299e-4046-adee-cdb7fb823b19");

$signDoc = new SignDocument($template, $ezysign->get_group_id(), $ezysign->get_sender_id(), null);
$dec = json_decode('[
    {

      "name": "WIN HTAIK AUNG",
      "email": "winhtaikaung28@hotmail.com",
      "phone_number": ""
    },
    {
      "name": "Win Aung",
      "email": "winhtaikaung76@gmail.com",
      "phone_number": ""
    },
    {
        "name": "Win ezuSign",
        "email": "win@ezysign.cc",
        "phone_number": ""
      }
  ]', true);
$signDoc->set_document_name("Hello.pdf");
$recipients = $signDoc->generate_recpients($dec);
$mapping = array('winhtaikaung76@gmail.com' => [1, 3], 'winhtaikaung28@hotmail.com' => [0, 2], 'win@ezysign.cc' => [4]);
$payload = $signDoc->get_signdocument_payload($recipients, $mapping);
$signDocument=$ezysign->post_document($payload);
print_r(json_encode($signDocument));
// print_r(json_encode($ezysign->get_document('47df455d-8c2b-4d97-9980-db8d51583ff9')));

?>