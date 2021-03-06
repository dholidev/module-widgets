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

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportExcel extends \Dholi\Widgets\Controller\Adminhtml\Slideritem {
	public function execute() {
		$fileName = 'itens.xls';

		$resultPage = $this->resultPageFactory->create();
		$content = $resultPage->getLayout()->createBlock('Dholi\Widgets\Block\Adminhtml\Slider\Item\Grid')->getExcel();

		return $this->fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
	}
}
