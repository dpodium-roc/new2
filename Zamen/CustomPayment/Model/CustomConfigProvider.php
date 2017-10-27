<?php
namespace Zamen\CustomPayment\Model;

class CustomConfigProvider implements ConfigProviderInterface
{
	private $configProviders;
	
	public function __construct(
        array $configProviders
    ) {
        $this->configProviders = $configProviders;
    }
	
	public function getConfig()
    {
        $config = [];
        foreach ($this->configProviders as $configProvider) {
            $config = array_merge_recursive($config, $configProvider->getConfig());
        }
        return $config;
    }
}