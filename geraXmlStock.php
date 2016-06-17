<?php

/**
* Gera XML apartir da lista de produtos do magento
* @return XML com os Stock gerado.
*/
class GeraXMLStock
{
	
	public function geraXMLStock()
	{
		include_once('helper/constantes.php');
		require_once('../ma/app/Mage.php');
		umask(0);
		Mage::app();

		$doc = new DomDocument('1.0');
		$doc->formatOutput = true;


		$doc->formatOutput = true;

		$root = $doc->appendChild($doc->createElement('AmazonEnvelope'));
		$root->appendChild($doc->createAttribute('xmlns:xsi'))
		->appendChild($doc->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));
		$root->appendChild($doc->createAttribute('xsi:noNamespaceSchemaLocation'))
		->appendChild($doc->createTextNode('amzn-envelope.xsd'));


		$head = new DOMElement('header');
		$root->appendChild($head);

		$DocumentVersion = new DOMElement('DocumentVersion','1.0.0');
		$head->appendChild($DocumentVersion);

		$MerchantIdentifier = new DOMElement('MerchantIdentifier','M_SELLER_354577');
		$head->appendChild($MerchantIdentifier);

		$MessageType = new DOMElement('MessageType','Inventory');
		$root->appendChild($MessageType);

		$Message = new DOMElement('Message');
		$root->appendChild($Message);

		#############################################################
		# Definindo uma colletions para pesquisa de produto MAGENTO #
		#############################################################
		$collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSort('created_at', 'DESC')
			->addAttributeToSelect('*');
		$collection->getSelect()->limit(2);

		$indice = 0;
		foreach ($collection as $product) {
			$indice++; //Contador

			//Atributos
			$sku        = $product->getData('sku');
			$amazonFlag = $product->getData('amazon_feed');

			$stocklevel = (int)Mage::getModel('cataloginventory/stock_item')
                ->loadByProduct($product->getID())->getQty();

            if ($amazonFlag == 0) {
            	$stocklevel = 0;
            }

			$MessageID = new DOMElement('MessageID',$indice);
			$Message->appendChild($MessageID);
			$OperationType = new DOMElement('OperationType', op_update);
			$Message->appendChild($OperationType);

			$Inventory = new DOMElement('Inventory');
			$Message->appendChild($Inventory);

			$SKU = new DOMElement('SKU' , $sku);
			$Inventory->appendChild($SKU);


			$Quantity = new DOMElement('Quantity' , $stocklevel);
			$Inventory->appendChild($Quantity);
			
			$FulfillmentLatency = new DOMElement('FulfillmentLatency' , NUM_5);
			$Inventory->appendChild($FulfillmentLatency);

		}


		//$dateSave = date("YmdHms");
		//$doc->save("c:/tmp/ProductStockXML-".$dateSave.".xml");
		return $doc->savexml();


	}
}

$GeraXMLStock = new GeraXMLStock();
echo $GeraXMLStock->geraXMLStock();

?>