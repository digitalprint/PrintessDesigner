<?php

namespace Digitalprint\PrintessDesigner\Model;

use Digitalprint\PrintessDesigner\Api\Data\PrintessProductPriceInfoInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class PrintessProductPriceInfo extends AbstractExtensibleModel implements PrintessProductPriceInfoInterface
{

    /**
     * {@inheritdoc}
     */
    public function getValue(): array
    {
        return [json_decode($this->getData(self::VALUE), true, 512, JSON_THROW_ON_ERROR)];
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(?string $value)
    {
        return $this->setData(self::VALUE, $value);
    }
}
