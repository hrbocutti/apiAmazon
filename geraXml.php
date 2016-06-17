<?php

/**
* Gera XML apartir da lista de produtos do magento
* @return XML com os produtos gerados.
*/
class GeraXML
{
	
	public function geraProduto()
	{	
		include_once('MarketplaceWebService/Samples/SubmitFeedSample.php');
		include_once('helper/constantes.php');
		require_once('../ma/app/Mage.php');
		umask(0);
		Mage::app();

		$doc = new DomDocument('1.0' , 'utf-8');
		$doc->formatOutput = true;

		$root = $doc->appendChild($doc->createElement('AmazonEnvelope'));
		$root->appendChild($doc->createAttribute('xmlns:xsi'))
		->appendChild($doc->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));
		$root->appendChild($doc->createAttribute('xsi:noNamespaceSchemaLocation'))
		->appendChild($doc->createTextNode('amzn-envelope.xsd'));


		$head = new DOMElement('Header');
		$root->appendChild($head);

		$DocumentVersion = new DOMElement('DocumentVersion','1.01');
		$head->appendChild($DocumentVersion);

		$MerchantIdentifier = new DOMElement('MerchantIdentifier','A147A61KSAHFTB');
		$head->appendChild($MerchantIdentifier);

		$MessageType = new DOMElement('MessageType','Product');
		$root->appendChild($MessageType);

		$PurgeAndReplace = new DOMElement('PurgeAndReplace','false');
		$root->appendChild($PurgeAndReplace);

		#############################################################
		# Definindo uma colletions para pesquisa de produto MAGENTO #
		#############################################################
		$collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSort('created_at', 'DESC')
			->addAttributeToSelect('*')->setPageSize(100);
		$collection->getSelect();
		//$collection->load();
		
		if ($collection->getSize() >= 0) {
		
		$indice = 0;
		for ($i=1; $i <= $collection->getLastPageNumber(); $i++) {
			if ($collection->isLoaded()) {
				$collection->clear();
				$collection->setPage($i);
				$collection->setPageSize(100);
			}

			foreach ($collection as $product) {
			$indice++; //Contador

			//Recebe os produtos 
			$sku  		  = $product->getSku();
			//titulo do produto -> maximo 100 caracteres
			$title 		  = substr($product->getName(), NUM_0, NUM_100);
			$name         = str_replace('&', '&amp;', strip_tags($title));
			$condition    = $product->getAttributeText('condition');
			$price 		  = $product->getPrice();
			$description  = substr($product->getData('description'), NUM_0, NUM_2000);
			/*
			$res 		  = str_replace('&', '&amp;', strip_tags($description));
			$shortDesc    = $product->getShortDescription();

			$catgory      = $product->getAttributeText('cat_amazon');
			$catConv 	  = str_replace('&', '&amp;', $catgory);
			$catExplode   = explode('>', $catConv);
			
			$upc 		  = $product->getUpc();
			$brand     	  = $product->getAttributeText('manufacturer');
			$manufacturer = $brand;
			$mfrPartNum   = $product->getData('part_number');

			//Dimensões do produto
			$length_Prod = number_format($product->getLength(),2);
			$width_Prod  = number_format($product->getWidth(),2);
			$height_Prod = number_format($product->getHeight(),2);
			$weight_Prod = number_format($product->getWeight(),2);

			//Dimensões de Embalagem
			$length_Pack = $length_Prod;
			$width_Pack  = $width_Prod; 
			$height_Pack = $height_Prod;
			$weight_Pack = $weight_Prod;

			$searchTerms = $product->getData('meta_keyword');

			$msrp 		 = number_format($product->getMsrp(),2);

			*/

			//Inicio Varias Mensagem ( amazon feed )
			$Message = new DOMElement('Message');
			$root->appendChild($Message);
			
			$MessageID = new DOMElement('MessageID',$indice);
			$Message->appendChild($MessageID);
			$OperationType = new DOMElement('OperationType', op_update);
			$Message->appendChild($OperationType);
			$Product = new DOMElement('Product');
			$Message->appendChild($Product);

			$Sku = new DOMElement('SKU', $sku);
			$Product->appendChild($Sku);
			
			$StandardProductID = new DOMElement('StandardProductID');
			$Product->appendChild($StandardProductID);

			$Type = new DOMElement('Type', UPC);
			$StandardProductID->appendChild($Type);

			$Value = new DOMElement('Value', $upc);
			$StandardProductID->appendChild($Value);

			//$ProductTaxCode = new DOMElement('ProductTaxCode');
			//$Product->appendChild($ProductTaxCode);

			//$LaunchDate = new DOMElement('LaunchDate');
			//$Product->appendChild($LaunchDate);

			$Condition = new DOMElement('Condition');
			$Product->appendChild($Condition);

			$ConditionType = new DOMElement('ConditionType', $condition);
			$Condition->appendChild($ConditionType);

			$DescriptionData = new DOMElement('DescriptionData');
			$Product->appendChild($DescriptionData);
			
			$Title = new DOMElement('Title', $name);
			$DescriptionData->appendChild($Title);

			$Brand = new DOMElement('Brand', $brand);
			$DescriptionData->appendChild($Brand);

			$Description = new DOMElement('Description' , $res);			
			$DescriptionData->appendChild($Description);

			/*
			$BulletPoint = new DOMElement('BulletPoint' , $shortDesc);
			$DescriptionData->appendChild($BulletPoint);
			*/

			
			
			/**
			* @todo Pegar categoria mapeada no magento para o marketplace especifico 
			*/

			/*
			foreach ($cats as $category_id) {
				$_cat = Mage::getModel('catalog/category')->setStoreId(Mage::app()
					->getStore()
					->getId())
				->load($category_id);
			    $category = $_cat->getName();
			    
			    $ItemType = new DOMElement('ItemType', $category);
				$Product->appendChild($ItemType);         
			}
			
			
			foreach ($catExplode as $categories) {
				$ItemType = new DOMElement('ItemType', $categories);
				$Product->appendChild($ItemType);
			}
			*/
			
			$ItemDimensions = new DOMElement('ItemDimensions');
			$DescriptionData->appendChild($ItemDimensions);

			//Definindo unidade de Medidas e Peso.
			$Length = new DOMElement('Length' , $length_Prod);
			$ItemDimensions->appendChild($Length);
			$Length->setAttribute('unitOfMeasure', unMedida_IN);

			$Width = new DOMElement('Width' , $width_Prod);
			$ItemDimensions->appendChild($Width);
			$Width->setAttribute('unitOfMeasure', unMedida_IN);

			$Height = new DOMElement('Height' , $height_Prod);
			$ItemDimensions->appendChild($Height);
			$Height->setAttribute('unitOfMeasure', unMedida_IN);
			
			$Weight = new DOMElement('Weight' , $weight_Prod);
			$ItemDimensions->appendChild($Weight);
			$Weight->setAttribute('unitOfMeasure', unPeso_LB);


			$PackageDimensions = new DOMElement('PackageDimensions');
			$DescriptionData->appendChild($PackageDimensions);

			//Definindo unidade de Medidas e Peso.
			$Length = new DOMElement('Length' , $length_Pack);
			$PackageDimensions->appendChild($Length);
			$Length->setAttribute('unitOfMeasure', unMedida_IN);

			$Width = new DOMElement('Width' , $width_Pack);
			$PackageDimensions->appendChild($Width);
			$Width->setAttribute('unitOfMeasure', unMedida_IN);

			$Height = new DOMElement('Height' , $height_Pack);
			$PackageDimensions->appendChild($Height);
			$Height->setAttribute('unitOfMeasure', unMedida_IN);
			
			$Weight = new DOMElement('Weight' , $weight_Pack);
			$PackageDimensions->appendChild($Weight);
			$Weight->setAttribute('unitOfMeasure', unPeso_LB);

			$PackageWeight = new DOMElement('PackageWeight' , $weight_Pack);
			$DescriptionData->appendChild($PackageWeight);
			$PackageWeight->setAttribute('unitOfMeasure', unPeso_LB);

			$ShippingWeight = new DOMElement('ShippingWeight' , $weight_Pack);
			$DescriptionData->appendChild($ShippingWeight);
			$ShippingWeight->setAttribute('unitOfMeasure', unPeso_LB);
			
			$MSRP = new DOMElement('MSRP' , $msrp);
			$DescriptionData->appendChild($MSRP);
			$MSRP->setAttribute('currency', unMoeda_USD);

			$Manufacturer = new DOMElement('Manufacturer' , $manufacturer);
			$DescriptionData->appendChild($Manufacturer);
			
			$MfrPartNumber = new DOMElement('MfrPartNumber' , $mfrPartNum);
			$DescriptionData->appendChild($MfrPartNumber);
			
			$SearchTerms = new DOMElement('SearchTerms' , $searchTerms);
			$DescriptionData->appendChild($SearchTerms);

			/*
			$contCat = 0;
			foreach ($catExplode as $categories) {
				$contCat++;
			}

			$ItemType = new DOMElement('ItemType', $catExplode[$contCat - NUM_1]);
			$DescriptionData->appendChild($ItemType);
			
			
			$ProductData = new DOMElement('ProductData');
			$Product->appendChild($ProductData);
			
			$Home = new DOMElement('Home');
			$ProductData->appendChild($Home);

			$ProductType = new DOMElement('ProductType');
			$Home->appendChild($ProductType);

			$FurnitureAndDecor = new DOMElement('FurnitureAndDecor');
			$ProductType->appendChild($FurnitureAndDecor);
			$ColorMap = new DOMElement('ColorMap');
			$FurnitureAndDecor->appendChild($ColorMap);
			$Material = new DOMElement('Material');
			$FurnitureAndDecor->appendChild($Material);
			$Shape = new DOMElement('Shape');
			$FurnitureAndDecor->appendChild($Shape);
			*/

			/*
			
			$img = Mage::helper('catalog/image')->init($product, 'image');
			echo "===============================================================================";
			echo ("<br>Produto salvo: ".
			 "<br>SKU: ".$sku.
			 "<br>Nome: ".$name.
			 "<br>Preço: ".$price.
			 "<br>Descrição: ".$description.
			 "<br>Descrição Curta: ".$shortDesc.
			 "<br>Img URL Base: <a href='".$img->resize(500,500)."'>Img Base</a>".
			 "<br>Img URL Thumbnail: <a href='".$img->resize(50,50)."'>Img Thumbnail</a>".
			 "<br>Img URL Small: <a href='".$img->resize(100,100)."'>Img Small</a><br>");
			 */

			}
		}
		$dateSave = date("YmdHms");
		$doc->save("c:/tmp/ProductXML-".$dateSave.".xml");
		return $doc->savexml();
	}

	public function geraStock()
	{
		include_once('helper/constantes.php');
		require_once('../app/Mage.php');
		umask(0);
		Mage::app();

		$doc = new DomDocument('1.0' , 'utf-8');
		$doc->formatOutput = true;

		$root = $doc->appendChild($doc->createElement('AmazonEnvelope'));
		$root->appendChild($doc->createAttribute('xmlns:xsi'))
		->appendChild($doc->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));
		$root->appendChild($doc->createAttribute('xsi:noNamespaceSchemaLocation'))
		->appendChild($doc->createTextNode('amzn-envelope.xsd'));


		$head = new DOMElement('Header');
		$root->appendChild($head);

		$DocumentVersion = new DOMElement('DocumentVersion','1.01');
		$head->appendChild($DocumentVersion);

		$MerchantIdentifier = new DOMElement('MerchantIdentifier','M_SELLER_354577');
		$head->appendChild($MerchantIdentifier);

		$MessageType = new DOMElement('MessageType','Inventory');
		$root->appendChild($MessageType);

		#############################################################
		# Definindo uma colletions para pesquisa de produto MAGENTO #
		#############################################################
		$collection = Mage::getModel('catalog/product')
		->getCollection()
		->addAttributeToSort('created_at', 'DESC')
		->addAttributeToFilter('amazon_feed' , '1')
		->addAttributeToSelect('*')->setPageSize(NUM_100);
		$collection->getSelect();

		$indice = 0;

		for ($i=1; $i <= $collection->getLastPageNumber(); $i++) { 
			if ($collection->isLoaded()) {
				$collection->clear();
				$collection->setPage($i);
				$collection->setPageSize(NUM_100);
			}

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

	            //Inicio Varias Mensagem ( amazon feed )
				$Message = new DOMElement('Message');
				$root->appendChild($Message);

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

		}
		$dateSave = date("YmdHms");
		$doc->save("log/ProductStockXML-".$dateSave.".xml");
		return $doc->savexml();
	}

	public function geraPrice()
	{
		include_once('helper/constantes.php');
		require_once('../app/Mage.php');
		umask(0);
		Mage::app();

		$doc = new DomDocument('1.0' , 'utf-8');
		$doc->formatOutput = true;

		$root = $doc->appendChild($doc->createElement('AmazonEnvelope'));
		$root->appendChild($doc->createAttribute('xmlns:xsi'))
		->appendChild($doc->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));
		$root->appendChild($doc->createAttribute('xsi:noNamespaceSchemaLocation'))
		->appendChild($doc->createTextNode('amzn-envelope.xsd'));


		$head = new DOMElement('Header');
		$root->appendChild($head);

		$DocumentVersion = new DOMElement('DocumentVersion','1.01');
		$head->appendChild($DocumentVersion);

		$MerchantIdentifier = new DOMElement('MerchantIdentifier','M_SELLER_354577');
		$head->appendChild($MerchantIdentifier);

		$MessageType = new DOMElement('MessageType','Price');
		$root->appendChild($MessageType);

		#############################################################
		# Definindo uma colletions para pesquisa de produto MAGENTO #
		#############################################################
		$collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSort('created_at', 'DESC')
			->addAttributeToFilter('amazon_feed' , '1')
			->addAttributeToSelect('*')->setPageSize(NUM_100);
		$collection->getSelect();

		$indice = 0;
		for ($i=1; $i <= $collection->getLastPageNumber(); $i++) {
			if ($collection->isLoaded()) {
				$collection->clear();
				$collection->setPage($i);
				$collection->setPageSize(NUM_100);
			}
			foreach ($collection as $product) {
				$indice++; //Contador

				//Atributos
				$sku        = $product->getData('sku');
				$price 		= $product->getData('amazon_price');

				//Inicio Varias Mensagem ( amazon feed )
				$Message = new DOMElement('Message');
				$root->appendChild($Message);

				$MessageID = new DOMElement('MessageID',$indice);
				$Message->appendChild($MessageID);
				$OperationType = new DOMElement('OperationType', op_update);
				$Message->appendChild($OperationType);

				$Inventory = new DOMElement('Price');
				$Message->appendChild($Inventory);

				$SKU = new DOMElement('SKU' , $sku);
				$Inventory->appendChild($SKU);


				$Price = new DOMElement('StandardPrice' , $price);
				$Inventory->appendChild($Price);
				$Price->setAttribute('currency', unMoeda_USD);
			}
		}

		$dateSave = date("YmdHms");
		$doc->save("log/ProductPriceXML-".$dateSave.".xml");
		return $doc->savexml();
	}

	public function geraImg()
	{

	 	include_once('helper/constantes.php');
		require_once('../app/Mage.php');
		umask(0);
		Mage::app();

		$doc = new DomDocument('1.0' , 'utf-8');
		$doc->formatOutput = true;

		$root = $doc->appendChild($doc->createElement('AmazonEnvelope'));
		$root->appendChild($doc->createAttribute('xmlns:xsi'))
		->appendChild($doc->createTextNode('http://www.w3.org/2001/XMLSchema-instance'));
		$root->appendChild($doc->createAttribute('xsi:noNamespaceSchemaLocation'))
		->appendChild($doc->createTextNode('amzn-envelope.xsd'));


		$head = new DOMElement('Header');
		$root->appendChild($head);

		$DocumentVersion = new DOMElement('DocumentVersion','1.01');
		$head->appendChild($DocumentVersion);

		$MerchantIdentifier = new DOMElement('MerchantIdentifier','A147A61KSAHFTB');
		$head->appendChild($MerchantIdentifier);

		$MessageType = new DOMElement('MessageType','ProductImage');
		$root->appendChild($MessageType);

		#############################################################
		# Definindo uma colletions para pesquisa de produto MAGENTO #
		#############################################################
		$collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSort('created_at', 'DESC')
			->addAttributeToFilter('amazon_feed' , '1')
			->addAttributeToSelect('*')->setPageSize(NUM_100);
		$collection->getSelect();
		//$collection->load();
				
		$indice = 0;
		for ($i=1; $i <= $collection->getLastPageNumber(); $i++) {
			if ($collection->isLoaded()) {
				$collection->clear();
				$collection->setPage($i);
				$collection->setPageSize(NUM_100);
			}
			foreach ($collection as $product) {
				$indice++; //Contador

				$sku                = $product->getSku();
				$productMediaConfig = Mage::getModel('catalog/product_media_config');
				$product_imgUrl     = $productMediaConfig->getMediaUrl($product->getImage());

				$Message = new DOMElement('Message');
				$root->appendChild($Message);

				$MessageID = new DOMElement('MessageID',$indice);
				$Message->appendChild($MessageID);
				$OperationType = new DOMElement('OperationType', op_update);
				$Message->appendChild($OperationType);

				$ProductImg = new DOMElement('ProductImage');
				$Message->appendChild($ProductImg);

				$Sku = new DOMElement('SKU' , $sku);
				$ProductImg->appendChild($Sku);

				$ImageType = new DOMElement('ImageType', 'Main');
				$ProductImg->appendChild($ImageType);

				$ImageLocation = new DOMElement('ImageLocation', $product_imgUrl);
				$ProductImg->appendChild($ImageLocation);

			}
	 	}
	 	$dateSave = date("YmdHms");
		$doc->save("log/ProductImgXML-".$dateSave.".xml");
	 	return $doc->savexml();
	}

	/**
	* @todo Fazer Chamada para Cliente Amazon
	*/
	 
}



$GeraXML = new GeraXML;

echo $GeraXML->geraProduto();
//$GeraXML->geraStock();
//$GeraXML->geraPrice();
//$GeraXML->geraImg();

?>