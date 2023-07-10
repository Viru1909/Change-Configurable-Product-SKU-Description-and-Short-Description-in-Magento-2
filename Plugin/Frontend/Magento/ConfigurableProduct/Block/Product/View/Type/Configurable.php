<?php

declare(strict_types = 1);

namespace Vct\ChangeSkuDynamically\Plugin\Frontend\Magento\ConfigurableProduct\Block\Product\View\Type;

use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as MagentoConfigurable;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Store\Model\ScopeInterface;
use Magento\Eav\Model\Config;


class Configurable
{
    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    protected $_eavConfig;

    /**
     * Configurable constructor.
     *
     * @param JsonSerializer       $jsonSerializer
     * @param HttpRequest          $httpRequest
     * @param ScopeConfigInterface $scopeConfig
      * @param Config               $eavConfig
     */
    public function __construct(
        JsonSerializer $jsonSerializer,
        HttpRequest $httpRequest,
        ScopeConfigInterface $scopeConfig,
          Config $eavConfig
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->httpRequest = $httpRequest;
        $this->scopeConfig = $scopeConfig;
           $this->_eavConfig = $eavConfig;
    }

    /**
     * Add product SKU to the config.
     *
     * @param MagentoConfigurable $subject
     * @param string              $result
     *
     * @return string|bool
     */
    public function afterGetJsonConfig(MagentoConfigurable $subject, string $result): string
    {
        $config = (array)$this->jsonSerializer->unserialize($result);
        // Product Detail Page Main Class in $config['fullActionName'] 
        $config['fullActionName'] = $this->httpRequest->getFullActionName();

        $scopeConfig = $this->scopeConfig;
        // Getting Value of Enable/Disable From Backend
        $config['sku']['enable'] = $scopeConfig->getValue(
            'vct_changeskudynamically/sku/enable',
            ScopeInterface::SCOPE_STORE
        );

        if ($config['fullActionName'] === 'catalog_product_view' && $config['sku']['enable']) {
            $config['sku']['selector'] = $scopeConfig->getValue(
                'vct_changeskudynamically/sku/selector',
                ScopeInterface::SCOPE_STORE
            );
            //Short Desc Class Path
            $config['short_description']['selector'] = $scopeConfig->getValue(
                'vct_changeskudynamically/short_description/selector',
                ScopeInterface::SCOPE_STORE
            );
            //Main Desc Class Path
            $config['description']['selector'] = $scopeConfig->getValue(
                'vct_changeskudynamically/description/selector',
                ScopeInterface::SCOPE_STORE
            );
            // Values of Current SKU,Short Desc & Main Desc
            $config['sku']['conf'] = $subject->getProduct()->getSku();
            $config['short_description']['conf'] = $subject->getProduct()->getShortDescription();
            $config['description']['conf'] = $subject->getProduct()->getDescription();

            // This Loop Getting All the Data which Set in Child Also 
            foreach ($subject->getAllowProducts() as $allowProduct) {
                $config['sku'][$allowProduct->getId()] = $allowProduct->getSku();
                $config['short_description'][$allowProduct->getId()] = $allowProduct->getShortDescription();
            }
            // This Loop is Specially Created to get Description Child Data 
            foreach ($subject->getProduct()->getTypeInstance()->getUsedProducts($subject->getProduct()) as $childProduct) {
                $childProduct->load($childProduct->getId()); //loading child product
                $description = $childProduct->getData('description');

                $config['description'][$childProduct->getId()] = $description;
                
                // Custom Attribute Data Here
                $color_more_info = $childProduct->getAttributeText('color');
               
                if (is_array($color_more_info) && count($color_more_info) > 1) {
                    $color_more_info_text = implode(", ", $color_more_info);
                } else if (is_array($color_more_info) && count($color_more_info) == 1) {
                    $color_more_info_text = $color_more_info[0];
                } else {
                    $color_more_info_text = '';
                }

                $config['color'][$childProduct->getId()] = $color_more_info_text;
                // Set them to $config for Future use in js file 
                $size_more_info_text = $childProduct->getattributeText('bowl_size');
                $config['bowl_size'][$childProduct->getId()] = $size_more_info_text;
            }
        }

        return $this->jsonSerializer->serialize($config);
    }
}
