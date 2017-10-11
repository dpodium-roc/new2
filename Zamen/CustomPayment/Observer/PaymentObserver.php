<?php
namespace Zamen\CustomPayment\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Zamen\CustomPayment\Custom\InformationNeeded;
use Zamen\CustomPayment\Custom\PipwaveIntegration;

use Zamen\CustomPayment\Custom\SetInfo;

class PaymentObserver implements ObserverInterface
{
	//@param EventObserver observer
	//@return void
	public function  execute(EventObserver $observer)
	{

        /*
         * first, if not sec [below]
         *
		//get info process info
		$info = new InformationNeeded();
		$pw = new PipwaveIntegration();

        $info->process_data();
        //bring input into data array
        $info->set_data();
        $data = $info->get_data();
        $api_key = $data['api_key'];

        $info->set_signature_param($data);
		$signature_param = $info->get_signature_param();

		$data['signature'] = $pw->generate_pw_signature($signature_param);

		//send request
		$response = $pw->send_request_to_pw($data, $api_key);

		//retrieve token
		$token = $response['token'];

        //render to sdk
        $pw->render_sdk($response, $token, $api_key);
        $result = $pw->get_result();
        echo $result;

        */

        $info = new SetInfo();
        $pw = new PipwaveIntegration();
		var_dump($info);

        $info->process_data();
        //bring input into data array
        $info->set_data();
        $data = $info->get_data();
        $api_key = $data['api_key'];

        $info->set_signature_param();
		$signature_param = $info->get_signature_param();

		$data['signature'] = $pw->generate_pw_signature($signature_param);

		//send request
		$response = $pw->send_request_to_pw($data, $api_key);
		echo var_dump($response);

		//retrieve token
		$token = $response['token'];

        //render to sdk
        $pw->render_sdk($response, $token, $api_key);
        $result = $pw->get_result();
        echo $result;



	}
}