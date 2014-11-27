<?php

namespace Mothership\Site\Controller\Module;

use Message\Cog\Controller\Controller;
use Message\Cog\Field\RepeatableContainer;
use Message\Mothership\Commerce\Product\Product;

class Gallery extends Controller
{
	protected $_productImage;

	public function gallery(Product $product, RepeatableContainer $gallery)
	{
		$currentIndex = $this->get('http.request.master')->query->get('gallery_image', 0);
		$thumbs  = [];
		$current = null;

		if (count($gallery)) {
			$thumbs  = $this->_getFilesFromGallery($gallery);

			$current = (array_key_exists($currentIndex, $thumbs)) ? $thumbs[$currentIndex] : $thumbs[0];
		}

		return $this->render('Mothership:Site::module:product:gallery', [
			'thumbs'  => (count($thumbs) > 1) ? $thumbs : [],
			'current' => $current,
		]);
	}

	protected function _getProductImage(Product $product)
	{
		if (null === $this->_productImage) {
			$this->_productImage = ($product->getImage()) ? $product->getImage()->file : false;
		}

		return $this->_productImage;
	}

	protected function _getFilesFromGallery(RepeatableContainer $gallery)
	{
		$files = [];

		foreach ($gallery->all() as $file) {
			$files[] = $file->image->getFile();
		}

		return $files;
	}
}