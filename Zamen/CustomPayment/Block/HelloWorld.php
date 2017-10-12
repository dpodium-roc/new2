<?php
namespace Zamen\CustomPayment\Block;

class HelloWorld extends \Magento\Framework\View\Element\Template
{
    public function getHelloWorldTxt()
    {
        return 'Hello world!';
    }
}