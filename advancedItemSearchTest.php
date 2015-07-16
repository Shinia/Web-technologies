
<?php
function test_input($data) {
	  $data = trim($data);
	  $data = preg_replace('/\s+/', '', $data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
?>

<?php
if (is_ajax()) {
  if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
    $action = $_GET["action"];
    switch($action) { //Switch case for value of action
      case "test": test_function(); break;
    }
  }
}

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function test_function(){
		
		$return = $_GET;
		$KeyWord = test_input($return["keyword"]); //  need to validate the keyword first!!! test_input($_GET["KeyWord"]);
		$sortOrder = $return["SortSelection"];
		$NumPerPage = $return["ResultsNum"];
		$condition = "";
		$index = 0;
		if(!empty($return["condition"])){
			$count = 0;
			$condition = "&itemFilter($index).name=Condition";
			foreach($return["condition"] as $selected) {
				$condition.="&itemFilter($index).value($count)=$selected";
				$count++;
			}
			$index++;
		}		
		$minPrice = "";
		if(!empty($return["priceLow"])){
			$minPrice = "&itemFilter($index).name=MinPrice&itemFilter($index).value(0)={$return["priceLow"]}";
			$index++;
		}
		$maxPrice = "";
		if(!empty($return["priceHigh"])){
			$maxPrice = "&itemFilter($index).name=MaxPrice&itemFilter($index).value(0)={$return["priceHigh"]}";
			$index++;
		}
		$buyingFormat = "";
		if(!empty($return["BuyFormat"])){
			$buyingFormat = "&itemFilter($index).name=ListingType";
			$count = 0;
			foreach($return["BuyFormat"] as $selected) {
				$buyingFormat.="&itemFilter($index).value($count)=$selected";
				$count++;
			}
			$index++;
		}
		$seller = "&itemFilter($index).name=ReturnsAcceptedOnly";
		if(!empty($return["seller"])){
			$seller.="&itemFilter($index).value(0)=true";
		}
		else{
			$seller.="&itemFilter($index).value(0)=false";
		}
		$index++;
		$FreeShippingOnly = "&itemFilter($index).name=FreeShippingOnly";
		if(!empty($return["FreeShippingOnly"])){
			$FreeShippingOnly.="&itemFilter($index).value(0)=true";
		}
		else{
			$FreeShippingOnly.="&itemFilter($index).value(0)=false";
		}
		$index++;
		$ExpeditedShippingType = "";
		if(!empty($return["ExpeditedShippingType"])){
			$ExpeditedShippingType="&itemFilter($index).name=ExpeditedShippingType&itemFilter($index).value(0)=Expedited";
			$index++;
		}
		$MaxHandlingTime = "";
		if(!empty($return["MaxHandlingTime"])){
			$MaxHandlingTime = "&itemFilter($index).name=MaxHandlingTime&itemFilter($index).value(0)={$return["MaxHandlingTime"]}";
			$index++;
		}
		$SellerInfo = "&outputSelector(0)=SellerInfo";
		$PictureURLSuperSize = "&outputSelector(1)=PictureURLSuperSize";
		$StoreInfo = "&outputSelector(2)=StoreInfo";
		$pageNumber = $return["pageNumber"];
		$URL ="http://svcs.ebay.com/services/search/FindingService/v1?siteid=0&SECURITY-APPNAME=yuliu2b3b-3e0b-4ee3-add6-9c96e89e823&OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&RESPONSE-DATA-FORMAT=XML&keywords=";
		$URL = $URL.$KeyWord."&paginationInput.entriesPerPage=".$NumPerPage."&paginationInput.pageNumber=".$pageNumber."&sortOrder=".$sortOrder.$condition.$minPrice.$maxPrice.$buyingFormat.$seller.$FreeShippingOnly.$ExpeditedShippingType.$MaxHandlingTime.$SellerInfo.$PictureURLSuperSize.$StoreInfo;//.$SellerInfo.$PictureURLSuperSize.$StoreInfo
		//$URL_test = "http://svcs.eBay.com/services/search/FindingService/v1?siteid=0&SECURITY-APPNAME=yuliu2b3b-3e0b-4ee3-add6-9c96e89e823&OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&RESPONSE-DATA-FORMAT=XML&keywords=harry%20potter&paginationInput.entriesPerPage=5&sortOrder=PricePlusShippingLowest";
		$XML = new SimpleXMLElement(file_get_contents($URL));
/*		http://svcs.ebay.com/services/search/FindingService/v1?%20OPERATION-NAME=findItemsAdvanced&%20SERVICE-VERSION=1.7.0&%20SECURITY-APPNAME=YourAppID&%20RESPONSE-DATA-FORMAT=XML&%20REST-PAYLOAD&%20itemFilter(0).name=Seller&%20itemFilter(0).value=eforcity&%20paginationInput.entriesPerPage=3&%20outputSelector=SellerInfo
		$fileContents= file_get_contents($URL);//get contents of the XML file

		$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);//remove the newlines, returns and tabs.
		$fileContents = trim(str_replace('"', "'", $fileContents));// replace double quotes with single quotes and trim leading and trailing spaces

		$XML = simplexml_load_string($fileContents);
		$return["json"]  = json_encode($XML);//convert the XML to JSON, Encode $return to JSON, set it as $return["json"]
		
		
		//$return["json"]  = json_encode($XML);
		$return["ack"]  = json_encode($XML->ack);//!!!!!!!!!!!!!you must define "ack" inside PHP file !!!!!
		
		$return["resultCount"]  = json_encode($XML->paginationOutput->totalEntries);
		$return["pageNumber"]  = json_encode($XML->paginationOutput->pageNumber);
		$return["itemCount"]  = json_encode($XML->paginationOutput->entriesPerPage);
	*/	
		$json['ack']  = "$XML->ack";
		$paginationOutput = $XML->paginationOutput; 
		$json['resultCount']  = "$paginationOutput->totalEntries";// variables inside "" cannot be longer than 2!!
		$json['pageNumber']  = "$paginationOutput->pageNumber";
		$json['itemCount']  = "$paginationOutput->entriesPerPage";
		$json['totalPages']  = "$paginationOutput->totalPages";
		
		$i = 0;
		foreach($XML->searchResult->item as $item){
			$json['item'][$i]['basicInfo']['title'] = "$item->title";
			$json['item'][$i]['basicInfo']['viewItemURL'] = "$item->viewItemURL";
			$json['item'][$i]['basicInfo']['galleryURL'] = "$item->galleryURL";
			$json['item'][$i]['basicInfo']['pictureURLSuperSize'] = "$item->pictureURLSuperSize";
			$sellingStatus = $item->sellingStatus;
			$json['item'][$i]['basicInfo']['convertedCurrentPrice'] = "$sellingStatus->convertedCurrentPrice";
			$shippingInfo = $item->shippingInfo;
			$json['item'][$i]['basicInfo']['shippingServiceCost'] = "$shippingInfo->shippingServiceCost";
			$condition = $item->condition;
			$json['item'][$i]['basicInfo']['conditionDisplayName'] = "$condition->conditionDisplayName";
			$primaryCategory = $item->primaryCategory;
			$json['item'][$i]['basicInfo']['categoryName'] = "$primaryCategory->categoryName";
			$listingInfo = $item->listingInfo;
			$json['item'][$i]['basicInfo']['listingType'] = "$listingInfo->listingType";
			$json['item'][$i]['basicInfo']['location'] = "$item->location";
			$sellerInfo = $item->sellerInfo;
			$json['item'][$i]['sellerInfo']['sellerUserName'] = "$sellerInfo->sellerUserName";
			$json['item'][$i]['basicInfo']['topRatedListing'] = "$item->topRatedListing";
			$json['item'][$i]['sellerInfo']['feedbackScore'] = "$sellerInfo->feedbackScore";
			$json['item'][$i]['sellerInfo']['positiveFeedbackPercent'] = "$sellerInfo->positiveFeedbackPercent";
			$json['item'][$i]['sellerInfo']['feedbackRatingStar'] = "$sellerInfo->feedbackRatingStar";
			$json['item'][$i]['sellerInfo']['topRatedSeller'] = "$sellerInfo->topRatedSeller";
			$storeInfo = $item->storeInfo;
			$json['item'][$i]['sellerInfo']['sellerStoreName'] = "$storeInfo->storeName";
			$json['item'][$i]['sellerInfo']['sellerStoreURL'] = "$storeInfo->storeURL";
			$shippingInfo = $item->shippingInfo;
			$json['item'][$i]['shippingInfo']['shippingType'] = "$shippingInfo->shippingType";
			$json['item'][$i]['shippingInfo']['shipToLocations'] = "$shippingInfo->shipToLocations";			
			$json['item'][$i]['shippingInfo']['expeditedShipping'] = "$shippingInfo->expeditedShipping";
			$json['item'][$i]['shippingInfo']['oneDayShippingAvailable'] = "$shippingInfo->oneDayShippingAvailable";
			$json['item'][$i]['shippingInfo']['returnsAccepted'] = "$item->returnsAccepted";
			$json['item'][$i]['shippingInfo']['handlingTime'] = "$shippingInfo->handlingTime";
			$i++;
		}
		echo json_encode($json);//return the JSON
		
		
  
}
?>
