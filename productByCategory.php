<?php
require_once('../app/Mage.php');
umask(0);
Mage::app();
/**
* Classe para Pegar Produtos por Categorias
*/
class ProductByCategories
{

	public function getProducts()
	{

	    
	    $attrSetName = 'Products CWR';
	    $attributeSetId = Mage::getModel('eav/entity_attribute_set')->load($attrSetName, 'attribute_set_name')->getAttributeSetId();
	    
	     
		$collection = Mage::getModel('catalog/product')
		->getCollection()
		->addAttributeToSort('created_at', 'DESC')
		->addAttributeToFilter('status' , '2')
		->addAttributeToSelect('*')
		//->addAttributeToFilter('sku', '60716')
		->addFieldToFilter('attribute_set_id', $attributeSetId)
		->setPageSize(10000);
		$collection->getSelect();
		for ($i=1; $i <= $collection->getLastPageNumber(); $i++) {
			if ($collection->isLoaded()) {
				$collection->clear();
				$collection->setPage($i);
				$collection->setPageSize(10000);
			}
			if ($collection->count() >0) {
				foreach ($collection as $p) {

					$product = Mage::getModel('catalog/product')->load($p->getId());
					$cats = $product->getCategoryIds();


					foreach ($cats as $category_id) {
					    $_cat = Mage::getModel('catalog/category')->setStoreId(Mage::app()->getStore()->getId())->load($category_id);
					       $nameCat = $_cat->getName();
					       $catID = $_cat->getId();
					}

					if ($catID == 1392) {
						echo "Product" . $product->getName() . " | CAT: " . $_cat->getName() . " ";
						$product->setCategoryIds(array(1051));
						$product->save();
						echo "<br><hr><br>" ;
						
					}

				}

			}else{
				echo "Vazio";
			}
		}
	}
}

$skus = new ProductByCategories();
$skus->getProducts();