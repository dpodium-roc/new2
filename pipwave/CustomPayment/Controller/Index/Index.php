<?php
namespace pipwave\CustomPayment\Controller\Index;

use \pipwave\CustomPayment\Block\InformationNeeded as information;
use \pipwave\CustomPayment\Model\PipwaveIntegration as pipwaveIntegration;
//use \Magento\Framework\Controller\ResultFactory as resultFactory;
//use \Magento\Framework\UrlInterface as urlBuilder;
use \Magento\Sales\Model\Order as order;
 
class Index extends \Magento\Framework\App\Action\Action
{
        /**
         * @var \Magento\Framework\View\Result\PageFactory
         */
        protected $resultPageFactory;
        protected $resultJsonFactory;
        protected $resultFactory;
        protected $urlBuilder;
        protected $information;
        protected $pipwaveIntegration;
        protected $order;
        
        /**
         * @param \Magento\Framework\App\Action\Context $context
         * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
         */
        public function __construct(
            \Magento\Framework\App\Action\Context $context,
            //\Magento\Framework\View\Result\PageFactory $resultPageFactory,
            \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
            //urlBuilder $urlBuilder,
            //resultFactory $resultFactory,
            information $information,
            order $order,
            pipwaveIntegration $pipwaveIntegration

            
        )
        {
            //$this->resultPageFactory = $resultPageFactory;
            $this->resultJsonFactory = $resultJsonFactory;
            //$this->urlBuilder = $urlBuilder;
            //$this->resultFactory = $resultFactory;
            $this->information = $information;
            $this->order = $order;
            $this->pipwaveIntegration = $pipwaveIntegration;
            parent::__construct($context);
        }
    /**
     * Default customer account page
     *
     * @return void
     */
    public function execute()
    {
        /*
        $result = $this->resultJsonFactory->create();
        if($this->getRequest()->isAjax())
        {
            $test = 'ginnie';
            $result->setData($test);
            
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $information = $objectManager->get('pipwave\CustomPayment\Block\InformationNeeded');
            $information->get_manager();
            
            $information->set_data();
            $this->data = $information->get_data();
            
            //var_dump($this->data);
            
            $information->set_signature_param();
            $information->insert_signature();
            
            $this->data = $information->get_data();
            //echo '<br>data with signature';
            //var_dump($this->data);
            
            $this->url = $information->get_url();
            //echo '<br>var dump url';
            //var_dump($this->url);
            
            //echo 'i used the sendRequest() here and get this';
            $information->sendRequest();
            
            //from sendRequest()
            $temp = $information->get_response();
            //var_dump($temp);
            
            //$information->render();
            //$form = $information->get_result();
            
            
            return $result;
        }
        */
        
        //create url
        //$successUrl = $this->urlBuilder->getUrl('success/index/success');
        //$failUrl = $this->urlBuilder->getUrl('');
        
        
        
        
        //run function
        $this->information->prepareData();
        
        //variables
        $rawData = $this->information->get_data();
        //var_dump($rawData);
        //$rawData['api_override']['success_url'] = $successUrl;
        
        
        $signatureParam = $this->information->get_signature_param();
        $url = $this->information->get_url();
        $renderUrl = $this->information->get_render_url();
        $loadingImageUrl = $this->information->get_loading_img_url();
        $callerVersion = $this->information->get_version();
        
        $rawData['signature'] = $this->pipwaveIntegration->generate_pw_signature($signatureParam);
        $agent = $this->pipwaveIntegration->get_agent();
        $response = $this->pipwaveIntegration->send_request_to_pw($rawData, $rawData['api_key'], $url, $agent);
        
        
        /*
        $this->pipwaveIntegration->render_sdk($response, $rawData['api_key'], $renderUrl, $loadingImageUrl);
        $form = $this->pipwaveIntegration->get_result();
        */
        
        $result = $this->resultJsonFactory->create();
        if($this->getRequest()->isAjax())
        {
            if ($response['status'] == 200) {
                $test = 
                [
                    'loadingImageUrl' => $loadingImageUrl,
                    'apiData'=> json_encode([
                                    'api_key' => $rawData['api_key'],
                                    'token' => $response['token'],
                                    'caller_version' => $callerVersion
                                ]),
                    'sdkUrl' => $renderUrl,
                    'status' => $response['status']
                ];
            } else {
                $test = 
                [
                    'data' => $rawData,
                    'status' => $response['status'],
                    'message' => $response['message']
                ];                
            }
            
            $result->setData($test);
            
            //set state to pending payment
            /*
            $order = $this->order->load($rawData['txn_id']);
            $state = $this->information->getOrderStatus(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
            
            $order->setState($state)->setStatus($state);
        
            */
            return ($result);
        }
        
        //return $this->resultPageFactory->create();
    }
}
?>