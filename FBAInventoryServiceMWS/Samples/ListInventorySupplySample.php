<?php
/*******************************************************************************
 * Copyright 2009-2016 Amazon Services. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 *
 * You may not use this file except in compliance with the License. 
 * You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR 
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the 
 * specific language governing permissions and limitations under the License.
 *******************************************************************************
 * PHP Version 5
 * @category Amazon
 * @package  FBA Inventory Service MWS
 * @version  2010-10-01
 * Library Version: 2014-09-30
 * Generated: Wed May 04 17:14:15 UTC 2016
 */

/**
 * List Inventory Supply Sample
 */

require_once('../../MarketplaceWebServiceProducts/Samples/.config.inc.php');

/************************************************************************
 * Instantiate Implementation of FBAInventoryServiceMWS
 *
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants
 * are defined in the .config.inc.php located in the same
 * directory as this sample
 ***********************************************************************/
// More endpoints are listed in the MWS Developer Guide
// North America:
$serviceUrl = "https://mws.amazonservices.com/FulfillmentInventory/2010-10-01";
// Europe
//$serviceUrl = "https://mws-eu.amazonservices.com/FulfillmentInventory/2010-10-01";
// Japan
//$serviceUrl = "https://mws.amazonservices.jp/FulfillmentInventory/2010-10-01";
// China
//$serviceUrl = "https://mws.amazonservices.com.cn/FulfillmentInventory/2010-10-01";


 $config = array (
   'ServiceURL' => $serviceUrl,
   'ProxyHost' => null,
   'ProxyPort' => -1,
   'ProxyUsername' => null,
   'ProxyPassword' => null,
   'MaxErrorRetry' => 3,
 );

 $service = new FBAInventoryServiceMWS_Client(
                   AWS_ACCESS_KEY_ID,
                   AWS_SECRET_ACCESS_KEY,
                   $config,
                   APPLICATION_NAME,
                   APPLICATION_VERSION);

/************************************************************************
 * Uncomment to try out Mock Service that simulates FBAInventoryServiceMWS
 * responses without calling FBAInventoryServiceMWS service.
 *
 * Responses are loaded from local XML files. You can tweak XML files to
 * experiment with various outputs during development
 *
 * XML files available under FBAInventoryServiceMWS/Mock tree
 *
 ***********************************************************************/
 // $service = new FBAInventoryServiceMWS_Mock();

/************************************************************************
 * Setup request parameters and uncomment invoke to try out
 * sample for List Inventory Supply Action
 ***********************************************************************/
 // @TODO: set request. Action can be passed as FBAInventoryServiceMWS_Model_ListInventorySupply
 $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyRequest();
 $request->setSellerId(MERCHANT_ID);
 //$request->setSellerSkus("46915");
 

 define("OPERACAO","Consulta_Stock");
 define("QUERY","2016-01-08T12:17:24.000Z");

 $request->setQueryStartDateTime(QUERY);

 // object or array of parameters
 invokeListInventorySupply($service, $request);

/**
  * Get List Inventory Supply Action Sample
  * Gets competitive pricing and related information for a product identified by
  * the MarketplaceId and ASIN.
  *
  * @param FBAInventoryServiceMWS_Interface $service instance of FBAInventoryServiceMWS_Interface
  * @param mixed $request FBAInventoryServiceMWS_Model_ListInventorySupply or array of parameters
  */

  function invokeListInventorySupply(FBAInventoryServiceMWS_Interface $service, $request)
  {
      try {
        $response = $service->ListInventorySupply($request);

        echo ("Service Response\n");
        echo ("=============================================================================\n");

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        echo $dom->saveXML();
        $dom->save("c:/tmp/Inventory(".OPERACAO.").xml");
        //$dom->save("c:/tmp/" + "Inventory(" + $setQueryStartDateTime + ").xml");
        echo("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");

     } catch (FBAInventoryServiceMWS_Exception $ex) {
        echo("Caught Exception: " . $ex->getMessage() . "\n");
        echo("Response Status Code: " . $ex->getStatusCode() . "\n");
        echo("Error Code: " . $ex->getErrorCode() . "\n");
        echo("Error Type: " . $ex->getErrorType() . "\n");
        echo("Request ID: " . $ex->getRequestId() . "\n");
        echo("XML: " . $ex->getXML() . "\n");
        echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
     }
 }
