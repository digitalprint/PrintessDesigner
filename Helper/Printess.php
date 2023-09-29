<?php

namespace Digitalprint\PrintessDesigner\Helper;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Api\LinkManagementInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class Printess extends AbstractHelper
{
    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepositoryInterface;

    /**
     * @var LinkManagementInterface
     */
    protected LinkManagementInterface $linkManagement;

    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepositoryInterface
     * @param LinkManagementInterface $linkManagement
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepositoryInterface,
        LinkManagementInterface $linkManagement
    ) {
        $this->productRepository = $productRepositoryInterface;
        $this->linkManagement = $linkManagement;

        parent::__construct($context);
    }

    /**
     * @param $sku
     * @return array
     * @throws NoSuchEntityException
     * @throws \JsonException
     */
    public function getStartDesign($sku = null): array
    {
        if (! is_null($sku)) {
            $product = $this->productRepository->get($sku);
            $config = $product->getData('printess_start_design');

            if (! is_null($config)) {
                $config = json_decode($config, true, 512, JSON_THROW_ON_ERROR);
                if (isset($config['templateName'], $config['documentName'])) {
                    return $config;
                }
            }
        }

        return [];
    }

    /**
     * @param $sku
     * @return bool
     * @throws NoSuchEntityException
     */
    public function hasTemplate($sku = null): bool
    {
        if (! is_null($sku)) {
            $product = $this->productRepository->get($sku);

            $printessTemplate = $product->getData('printess_template');
            if ($printessTemplate) {
                return true;
            }

            if ($product->getTypeId() === 'configurable') {
                $children = $this->linkManagement->getChildren($product->getSku());

                foreach ($children as $child) {
                    $childProduct = $this->productRepository->get($child->getSku());

                    $printessTemplate = $childProduct->getData('printess_template');

                    if ($printessTemplate) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getDesignerUrl(): string
    {
        return $this->_getUrl('designer/page/view');
    }
}
