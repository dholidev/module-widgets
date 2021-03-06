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

namespace Dholi\Widgets\Controller\Adminhtml\Slideritem;

use Magento\Framework\Controller\ResultFactory;

class MassDelete extends \Dholi\Widgets\Controller\Adminhtml\AbstractAction {

	public function execute() {
		$collection = $this->massActionFilter->getCollection($this->_createMainCollection());

		$collectionSize = $collection->getSize();
		foreach ($collection as $item) {
			$item->delete();
		}

		$this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));

		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		return $resultRedirect->setPath('*/*/');
	}
}
