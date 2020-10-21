<?php

require_once './src/index.php';
require_once './src/exception.php';

use EzySignSdk\SignDocument;
use EzySignSdk\EzySignException;
use PHPUnit\Framework\TestCase;

class SignDocumentTest extends TestCase
{
    public function testCorrectDocumentPayload()
    {
        $MOCK_TEMPLATE = json_decode('{"data":{"created_at":"2020-10-20T12:05:21.38835Z","deleted_at":null,"document_id":"ddd0822c-329b-467c-8eae-37bdbe2cfd1c","fields":[{"itemType":"signature","left":231,"meta":{"recipient":{"email":"","name":"","phone_number":""},"required":false,"signaturefield":{"name":"","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"verificationRequired":false},"objectId":"jYLRyqblqC_XFPZqCO57g","title":"Signature ","top":61},{"itemType":"initial","left":464,"meta":{"initialfield":{"signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false,"verificationRequired":false},"objectId":"U9iw4mp9Ub2yGOx-OyRsT","title":"Initial","top":60},{"itemType":"datetime","left":612,"meta":{"datefield":{"dateBy":"","format":"M/D/YYYY","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false},"objectId":"KAB_ogiiPz-lRrt66SdO3","title":"Date","top":64},{"itemType":"text","left":771,"meta":{"recipient":{"email":"","name":"","phone_number":""},"required":false,"textfield":{"label":"","placeholder":"Text Field","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""}},"objectId":"hUWbC4Ao7mlEI1o4VnkwF","title":"Text","top":61},{"itemType":"checkbox","left":240,"meta":{"checkboxfield":{"label":"Do you agree","signformMeta":{"imageUrl":"","userId":""},"userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false},"objectId":"RvOrvPsD3658KkxqXTZkg","title":"CheckBox","top":153}],"group_id":"716e537d-f316-4cef-9227-f6b7d65d74d7","id":"d3ba9b6a-299e-4046-adee-cdb7fb823b19","is_protected":false,"name":"","save_as_template":true,"template_name":"AWS August Bill.pdf","template_url":"","updated_at":"2020-10-20T12:05:21.38835Z","user_id":"57722059-3bd3-442a-80e6-9aecd2e7fa03"},"message":"OK"}', true);

        $signDoc = new SignDocument($MOCK_TEMPLATE['data'], "123456", "123456", null);

        $dec = json_decode('[
    {

      "name": "WIN HTAIK AUNG",
      "email": "winhtaikaung1@hotmail.com",
      "phone_number": ""
    },
    {
      "name": "Win Aung",
      "email": "winhtaikaung2@gmail.com",
      "phone_number": ""
    },
    {
        "name": "Win ezuSign",
        "email": "win3@ezysign.cc",
        "phone_number": ""
      }
  ]', true);
        $mapping = array('winhtaikaung2@gmail.com' => [1, 3], 'winhtaikaung1@hotmail.com' => [0, 2], 'win3@ezysign.cc' => [4]);
        $signDoc->set_document_name("Hello.pdf");
        $recipients = $signDoc->generate_recpients($dec);

        $payload = $signDoc->get_signdocument_payload($recipients, $mapping);
        

        $this->assertSame(3, count($recipients));
        $this->assertSame($payload['template_id'], "d3ba9b6a-299e-4046-adee-cdb7fb823b19");
        $this->assertSame($payload['sign_document_name'], "Hello.pdf");
        $this->assertSame($payload['sender_id'], "123456");
        $this->assertSame($payload['group_id'], "123456");

        // array_push($stack, 'foo');
        // $this->assertSame('foo', $stack[count($stack) - 1]);
        // $this->assertSame(1, count($stack));

        // $this->assertSame('foo', array_pop($stack));
        // $this->assertSame(0, count($stack));
    }

    /**
     * @expectedException EzySignSdk\EzySignException
     */
    public function testInvalidRecipientCountError(){
        $this->expectException(EzySignSdk\EzySignException::class);
        $MOCK_TEMPLATE = json_decode('{"data":{"created_at":"2020-10-20T12:05:21.38835Z","deleted_at":null,"document_id":"ddd0822c-329b-467c-8eae-37bdbe2cfd1c","fields":[{"itemType":"signature","left":231,"meta":{"recipient":{"email":"","name":"","phone_number":""},"required":false,"signaturefield":{"name":"","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"verificationRequired":false},"objectId":"jYLRyqblqC_XFPZqCO57g","title":"Signature ","top":61},{"itemType":"initial","left":464,"meta":{"initialfield":{"signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false,"verificationRequired":false},"objectId":"U9iw4mp9Ub2yGOx-OyRsT","title":"Initial","top":60},{"itemType":"datetime","left":612,"meta":{"datefield":{"dateBy":"","format":"M/D/YYYY","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false},"objectId":"KAB_ogiiPz-lRrt66SdO3","title":"Date","top":64},{"itemType":"text","left":771,"meta":{"recipient":{"email":"","name":"","phone_number":""},"required":false,"textfield":{"label":"","placeholder":"Text Field","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""}},"objectId":"hUWbC4Ao7mlEI1o4VnkwF","title":"Text","top":61},{"itemType":"checkbox","left":240,"meta":{"checkboxfield":{"label":"Do you agree","signformMeta":{"imageUrl":"","userId":""},"userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false},"objectId":"RvOrvPsD3658KkxqXTZkg","title":"CheckBox","top":153}],"group_id":"716e537d-f316-4cef-9227-f6b7d65d74d7","id":"d3ba9b6a-299e-4046-adee-cdb7fb823b19","is_protected":false,"name":"","save_as_template":true,"template_name":"AWS August Bill.pdf","template_url":"","updated_at":"2020-10-20T12:05:21.38835Z","user_id":"57722059-3bd3-442a-80e6-9aecd2e7fa03"},"message":"OK"}', true);

        $signDoc = new SignDocument($MOCK_TEMPLATE['data'], "123456", "123456", null);

        $dec = json_decode('[
    {

      "name": "WIN HTAIK AUNG",
      "email": "winhtaikaung1@hotmail.com",
      "phone_number": ""
    },
    {
      "name": "Win Aung",
      "email": "winhtaikaung2@gmail.com",
      "phone_number": ""
    },
    {
        "name": "Win ezuSign",
        "email": "win3@ezysign.cc",
        "phone_number": ""
      }
  ]', true);
        $mapping = array('winhtaikaung1@gmail.com' => [1, 3], 'winhtaikaung2@hotmail.com' => [0, 2], 'win3@ezysign.cc' => [4]);
        $signDoc->set_document_name("Hello.pdf");
        $recipients = $signDoc->generate_recpients($dec);        
        
        $signDoc->get_signdocument_payload($recipients, $mapping);
        
    }

     /**
     * @expectedException EzySignSdk\EzySignException
     */
    public function testMorethanOneRecipientAssignedError(){
        $this->expectException(EzySignSdk\EzySignException::class);
        $MOCK_TEMPLATE = json_decode('{"data":{"created_at":"2020-10-20T12:05:21.38835Z","deleted_at":null,"document_id":"ddd0822c-329b-467c-8eae-37bdbe2cfd1c","fields":[{"itemType":"signature","left":231,"meta":{"recipient":{"email":"","name":"","phone_number":""},"required":false,"signaturefield":{"name":"","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"verificationRequired":false},"objectId":"jYLRyqblqC_XFPZqCO57g","title":"Signature ","top":61},{"itemType":"initial","left":464,"meta":{"initialfield":{"signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false,"verificationRequired":false},"objectId":"U9iw4mp9Ub2yGOx-OyRsT","title":"Initial","top":60},{"itemType":"datetime","left":612,"meta":{"datefield":{"dateBy":"","format":"M/D/YYYY","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false},"objectId":"KAB_ogiiPz-lRrt66SdO3","title":"Date","top":64},{"itemType":"text","left":771,"meta":{"recipient":{"email":"","name":"","phone_number":""},"required":false,"textfield":{"label":"","placeholder":"Text Field","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""}},"objectId":"hUWbC4Ao7mlEI1o4VnkwF","title":"Text","top":61},{"itemType":"checkbox","left":240,"meta":{"checkboxfield":{"label":"Do you agree","signformMeta":{"imageUrl":"","userId":""},"userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false},"objectId":"RvOrvPsD3658KkxqXTZkg","title":"CheckBox","top":153}],"group_id":"716e537d-f316-4cef-9227-f6b7d65d74d7","id":"d3ba9b6a-299e-4046-adee-cdb7fb823b19","is_protected":false,"name":"","save_as_template":true,"template_name":"AWS August Bill.pdf","template_url":"","updated_at":"2020-10-20T12:05:21.38835Z","user_id":"57722059-3bd3-442a-80e6-9aecd2e7fa03"},"message":"OK"}', true);

        $signDoc = new SignDocument($MOCK_TEMPLATE['data'], "123456", "123456", null);

        $dec = json_decode('[
    {

      "name": "WIN HTAIK AUNG",
      "email": "winhtaikaung1@hotmail.com",
      "phone_number": ""
    },
    {
      "name": "Win Aung",
      "email": "winhtaikaung2@gmail.com",
      "phone_number": ""
    },
    {
        "name": "Win ezuSign",
        "email": "win3@ezysign.cc",
        "phone_number": ""
      }
  ]', true);
        $mapping = array('winhtaikaung76@gmail.com' => [1, 3], 'winhtaikaung28@hotmail.com' => [1, 2], 'win3@ezysign.cc' => [4]);
        $signDoc->set_document_name("Hello.pdf");
        $recipients = $signDoc->generate_recpients($dec);        
        
        $signDoc->get_signdocument_payload($recipients, $mapping);
        
    }

    /**
     * @expectedException EzySignSdk\EzySignException
     */
    public function testShouldRaiseFieldExceedThanProvidedRecipientError(){
        $this->expectException(EzySignSdk\EzySignException::class);
        $MOCK_TEMPLATE = json_decode('{"data":{"created_at":"2020-10-20T12:05:21.38835Z","deleted_at":null,"document_id":"ddd0822c-329b-467c-8eae-37bdbe2cfd1c","fields":[{"itemType":"signature","left":231,"meta":{"recipient":{"email":"","name":"","phone_number":""},"required":false,"signaturefield":{"name":"","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"verificationRequired":false},"objectId":"jYLRyqblqC_XFPZqCO57g","title":"Signature ","top":61},{"itemType":"signature","left":231,"meta":{"recipient":{"email":"","name":"","phone_number":""},"required":false,"signaturefield":{"name":"","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"verificationRequired":false},"objectId":"jYLRyqblqC_XFPZqCO57g","title":"Signature ","top":61},{"itemType":"initial","left":464,"meta":{"initialfield":{"signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false,"verificationRequired":false},"objectId":"U9iw4mp9Ub2yGOx-OyRsT","title":"Initial","top":60},{"itemType":"datetime","left":612,"meta":{"datefield":{"dateBy":"","format":"M/D/YYYY","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false},"objectId":"KAB_ogiiPz-lRrt66SdO3","title":"Date","top":64},{"itemType":"text","left":771,"meta":{"recipient":{"email":"","name":"","phone_number":""},"required":false,"textfield":{"label":"","placeholder":"Text Field","signformMeta":{"imageUrl":"","userId":""},"text":"","userid":""}},"objectId":"hUWbC4Ao7mlEI1o4VnkwF","title":"Text","top":61},{"itemType":"checkbox","left":240,"meta":{"checkboxfield":{"label":"Do you agree","signformMeta":{"imageUrl":"","userId":""},"userid":""},"recipient":{"email":"","name":"","phone_number":""},"required":false},"objectId":"RvOrvPsD3658KkxqXTZkg","title":"CheckBox","top":153}],"group_id":"716e537d-f316-4cef-9227-f6b7d65d74d7","id":"d3ba9b6a-299e-4046-adee-cdb7fb823b19","is_protected":false,"name":"","save_as_template":true,"template_name":"AWS August Bill.pdf","template_url":"","updated_at":"2020-10-20T12:05:21.38835Z","user_id":"57722059-3bd3-442a-80e6-9aecd2e7fa03"},"message":"OK"}', true);

        $signDoc = new SignDocument($MOCK_TEMPLATE['data'], "123456", "123456", null);

        $dec = json_decode('[
    {

      "name": "WIN HTAIK AUNG",
      "email": "winhtaikaung1@hotmail.com",
      "phone_number": ""
    },
    {
      "name": "Win Aung",
      "email": "winhtaikaung2@gmail.com",
      "phone_number": ""
    },
    {
        "name": "Win ezuSign",
        "email": "win3@ezysign.cc",
        "phone_number": ""
      }
  ]', true);
        $mapping = array('winhtaikaung76@gmail.com' => [1, 3], 'winhtaikaung28@hotmail.com' => [0,2], 'win3@ezysign.cc' => [4]);
        $signDoc->set_document_name("Hello.pdf");
        $recipients = $signDoc->generate_recpients($dec);        
        
        $signDoc->get_signdocument_payload($recipients, $mapping);
        
    }


}
