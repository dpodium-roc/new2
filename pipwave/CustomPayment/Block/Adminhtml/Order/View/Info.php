<?php
namespace pipwave\CustomPayment\Block\Adminhtml\Order\View;

class Info extends \Magento\Backend\Block\Widget
{
	public function __construct(
		\pipwave\CustomPayment\Model\NotificationInformationFactory $NotificationInformationFactory,
		\pipwave\CustomPayment\Model\ResourceModel\NotificationInformationFactory $NotificationInformationFactoryDB,
		\Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        array $data = []
    ) {
		$this->NotificationInformationFactory = $NotificationInformationFactory;
		$this->NotificationInformationFactoryDB = $NotificationInformationFactoryDB;
        $this->_adminHelper = $adminHelper;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
	/*
	
	public function __construct(
		\pipwave\CustomPayment\Model\NotificationInformationFactory $NotificationInformationFactory//,
		//\Magento\Backend\Block\Template\Context $context,
		//array $data = []
		//\pipwave\CustomPayment\Controller\Notification\Index $index
		//\pipwave\CustomPayment\Block\InformationNeeded $information
	) {
		//$this->information = $information;
		//$this->index = $index;
		$this->NotificationInformationFactory = $NotificationInformationFactory;
		
	}
	*/
	
	protected $information;
	protected $index;
	protected $NotificationInformationFactory;
	protected $_coreRegistry;
	protected $NotificationInformationFactoryDB;
	protected $_adminHelper;
	
	public function sayHello()
	{
		return __('Hello World');
	}
	
	protected $bla = 'werty';
	public function say()
	{
		return __($this->name);
		var_dump($this->name);
	}
	
	protected $name;
	public function setName($name){
		$this->name = $name;
	}
	public function getName(){
		return $this->name;
	}
	public function getOrder()
    {
        if ($this->hasOrder()) {
            return $this->getData('order');
        }
        if ($this->_coreRegistry->registry('current_order')) {
            return $this->_coreRegistry->registry('current_order');
        }
        if ($this->_coreRegistry->registry('order')) {
            return $this->_coreRegistry->registry('order');
        }
        throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t get the order instance right now.'));

		//return $this->getId();
    }
	
	public function showInfo($id)
	{
		$model = $this->NotificationInformationFactory->create();
		//$text = $model->load($id);
		//$text = $this->NotificationInformationFactoryDB->create()->load($model, $id);
		//->addFieldToFilter('order_id',$id)
		$model->load($id);
		$text = $model;
		//$text = $text['order_id'];
		return $text;
		
	}
}
