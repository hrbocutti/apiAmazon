<?php

class EnviaFeed
{

  public function recebeXml($xml , $feed_type)
  {
    
    /** 
   *  PHP Version 5
   *
   *  @category    Amazon
   *  @package     MarketplaceWebService
   *  @copyright   Copyright 2009 Amazon Technologies, Inc.
   *  @link        http://aws.amazon.com
   *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
   *  @version     2009-01-01
   */
  /******************************************************************************* 

   *  Marketplace Web Service PHP5 Library
   *  Generated: Thu May 07 13:07:36 PDT 2009
   * 
   */

  /**
   * Submit Feed  Sample
   */

  include_once ('.config.inc.php'); 

  // United States:
  $serviceUrl = "https://mws.amazonservices.com";
  

  $config = array (
    'ServiceURL' => $serviceUrl,
    'ProxyHost' => null,
    'ProxyPort' => -1,
    'MaxErrorRetry' => 3,
    );

  $service = new MarketplaceWebService_Client(
   AWS_ACCESS_KEY_ID, 
   AWS_SECRET_ACCESS_KEY, 
   $config,
   APPLICATION_NAME,
   APPLICATION_VERSION);

  $marketplaceIdArray = array("Id" => array('ATVPDKIKX0DER'));


  $feedHandle = @fopen('php://temp', 'rw+');
  fwrite($feedHandle, $feed);
  rewind($feedHandle);
  $parameters = array (
    'Merchant' => MERCHANT_ID,
    'MarketplaceIdList' => $marketplaceIdArray,
    'FeedType' => FEED_TYPE,
    'FeedContent' => $feedHandle,
    'PurgeAndReplace' => false,
    'ContentMd5' => base64_encode(md5(stream_get_contents($feedHandle), true)),
    //'MWSAuthToken' => '<MWS Auth Token>', // Optional
    );

  rewind($feedHandle);

  $request = new MarketplaceWebService_Model_SubmitFeedRequest($parameters);
  
  invokeSubmitFeed($service, $request);

  fclose($feedHandle);

  /**
  * Submit Feed Action Sample
  * Uploads a file for processing together with the necessary
  * metadata to process the file, such as which type of feed it is.
  * PurgeAndReplace if true means that your existing e.g. inventory is
  * wiped out and replace with the contents of this feed - use with
  * caution (the default is false).
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_SubmitFeed or array of parameters
  */
  function invokeSubmitFeed(MarketplaceWebService_Interface $service, $request) 
  {
    try {
      $response = $service->submitFeed($request);

      echo ("Service Response\n");
      echo ("=============================================================================\n");

      echo("        SubmitFeedResponse\n");
      if ($response->isSetSubmitFeedResult()) { 
        echo("            SubmitFeedResult\n");
        $submitFeedResult = $response->getSubmitFeedResult();
        if ($submitFeedResult->isSetFeedSubmissionInfo()) { 
          echo("                FeedSubmissionInfo\n");
          $feedSubmissionInfo = $submitFeedResult->getFeedSubmissionInfo();
          if ($feedSubmissionInfo->isSetFeedSubmissionId()) 
          {
            echo("                    FeedSubmissionId\n");
            echo("                        " . $feedSubmissionInfo->getFeedSubmissionId() . "\n");
          }
          if ($feedSubmissionInfo->isSetFeedType()) 
          {
            echo("                    FeedType\n");
            echo("                        " . $feedSubmissionInfo->getFeedType() . "\n");
          }
          if ($feedSubmissionInfo->isSetSubmittedDate()) 
          {
            echo("                    SubmittedDate\n");
            echo("                        " . $feedSubmissionInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
          }
          if ($feedSubmissionInfo->isSetFeedProcessingStatus()) 
          {
            echo("                    FeedProcessingStatus\n");
            echo("                        " . $feedSubmissionInfo->getFeedProcessingStatus() . "\n");
          }
          if ($feedSubmissionInfo->isSetStartedProcessingDate()) 
          {
            echo("                    StartedProcessingDate\n");
            echo("                        " . $feedSubmissionInfo->getStartedProcessingDate()->format(DATE_FORMAT) . "\n");
          }
          if ($feedSubmissionInfo->isSetCompletedProcessingDate()) 
          {
            echo("                    CompletedProcessingDate\n");
            echo("                        " . $feedSubmissionInfo->getCompletedProcessingDate()->format(DATE_FORMAT) . "\n");
          }
        } 
      } 
      if ($response->isSetResponseMetadata()) { 
        echo("            ResponseMetadata\n");
        $responseMetadata = $response->getResponseMetadata();
        if ($responseMetadata->isSetRequestId()) 
        {
          echo("                RequestId\n");
          echo("                    " . $responseMetadata->getRequestId() . "\n");
        }
      } 

      echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
    } catch (MarketplaceWebService_Exception $ex) {
     echo("Caught Exception: " . $ex->getMessage() . "\n");
     echo("Response Status Code: " . $ex->getStatusCode() . "\n");
     echo("Error Code: " . $ex->getErrorCode() . "\n");
     echo("Error Type: " . $ex->getErrorType() . "\n");
     echo("Request ID: " . $ex->getRequestId() . "\n");
     echo("XML: " . $ex->getXML() . "\n");
     echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
   }
  }

  }

}

