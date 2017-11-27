<?php
namespace pipwave\CustomPayment\Block;

use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

use Magento\Paypal\Model\Config;
use Magento\Paypal\Model\ConfigFactory;

class Form extends \Magento\Payment\Block\Form
{
	
	protected $_config;
	protected $_isScopePrivate;
	protected $currentCustomer;
	protected $_paypalConfigFactory;
	protected $_localeResolver;
	
	public function __construct(
        Context $context,
        ConfigFactory $paypalConfigFactory,
		ResolverInterface $localeResolver,
        CurrentCustomer $currentCustomer,
        array $data = []
    ) {
        $this->_config = null;
		$this->_paypalConfigFactory = $paypalConfigFactory;
		$this->_localeResolver = $localeResolver;
        $this->_isScopePrivate = true;
        $this->currentCustomer = $currentCustomer;
        parent::__construct($context, $data);
    }
	
	protected function _construct()
    {
        $this->_config = $this->_paypalConfigFactory->create()
            ->setMethod($this->getMethodCode());
        $mark = $this->_getMarkTemplate();
        $mark->setPaymentAcceptanceMarkSrc(
            $this->_config->getPaymentMarkImageUrl($this->_localeResolver->getLocale())
        );

        // known issue: code above will render only static mark image
        $this->_initializeRedirectTemplateWithMark($mark);
        parent::_construct();

        $this->setRedirectMessage(__('You will be redirected to the PayPal website.'));
    }
	
	
	protected function _getMarkTemplate()
    {
        /** @var $mark Template */
        $mark = $this->_layout->createBlock(\Magento\Framework\View\Element\Template::class);
        $mark->setTemplate('pipwave_CustomPayment::payment/mark.phtml');
        return $mark;
    }
	
	protected function _initializeRedirectTemplateWithMark(Template $mark)
    {
        $this->setTemplate(
            'Magento_Paypal::payment/redirect.phtml'
        )->setRedirectMessage(
            __('You will be redirected to the PayPal website when you place an order.')
        )->setMethodTitle(
            // Output PayPal mark, omit title
            ''
        )->setMethodLabelAfterHtml(
            $mark->toHtml()
        );
}