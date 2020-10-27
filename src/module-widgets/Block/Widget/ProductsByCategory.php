<?php
/**
* 
* Widgets para Magento 2
* 
* @category     Dholi
* @package      Modulo Widgets
* @copyright    Copyright (c) 2019 dholi (https://www.dholi.dev)
* @version      1.0.0
* @license      https://www.dholi.dev/license/
*
*/
declare(strict_types=1);

namespace Dholi\Widgets\Block\Widget;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Widget\Block\BlockInterface;

class ProductsByCategory extends AbstractProduct implements BlockInterface {

	const DEFAULT_PRODUCTS_COUNT = 12;

	private $productCollectionFactory;

	private $categoryFactory;

	private $itemCollection;

	public function __construct(Context $context,
	                            StoreManagerInterface $storeManager,
	                            CategoryFactory $categoryFactory,
	                            array $data = []) {

		$this->storeManager = $storeManager;
		$this->categoryFactory = $categoryFactory;

		parent::__construct($context, $data);
	}

	private function getStoreId() {
		return $this->storeManager->getStore()->getId();
	}

	public function getTitle() {
		return $this->getData('title');
	}

	protected function _toHtml() {
		if (!$this->getItems()->getSize()) {
			return '';
		}

		return parent::_toHtml();
	}

	public function getProductsCount() {
		if ($this->hasData('products_count')) {
			return $this->getData('products_count');
		}
		if (null === $this->getData('products_count')) {
			$this->setData('products_count', self::DEFAULT_PRODUCTS_COUNT);
		}

		return $this->getData('products_count');
	}

	public function getItems() {
		if (!$this->itemCollection) {
			if (!$this->getData('id_path')) {
				throw new \RuntimeException('Parameter id_path is not set.');
			}
			$rewriteData = $this->parseIdPath($this->getData('id_path'));

			$category = $this->categoryFactory->create()->load($rewriteData[1]);
			$this->itemCollection = $category->getProductCollection();
			$this->itemCollection->addMinimalPrice()
				->addFinalPrice()
				->addTaxPercents()
				->addAttributeToSelect('*')
				->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
				->addAttributeToFilter('visibility', 4)
				->addStoreFilter($this->getStoreId())->setPageSize($this->getProductsCount());
		}

		return $this->itemCollection;
	}

	protected function parseIdPath($idPath) {
		$rewriteData = explode('/', $idPath);

		if (!isset($rewriteData[0]) || !isset($rewriteData[1])) {
			throw new \RuntimeException('Wrong id_path structure.');
		}
		return $rewriteData;
	}

	public function getAlias() {
		return md5(uniqid('', true));
	}
}