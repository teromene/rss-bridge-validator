<?php

//ini_set('display_errors','1'); error_reporting(E_ALL);  // For debugging only.

require_once '../rss-bridge/lib/RssBridge.php';

Bridge::setDir('../rss-bridge/bridges/');
Format::setDir('../rss-bridge/formats/');
Cache::setDir('../rss-bridge/caches/');

require_once 'test_datas.php';

if($_GET['bridge'] && is_numeric($_GET['t_number'])) {

	test_bridge($_GET['bridge'], $_GET['t_number']);

}
function test_bridge($bridge_name, $test_number) {

	$bridge = Bridge::create($bridge_name);
	$bridge->loadMetadatas();
	$params = $bridge->parameters;
    
	if($bridge->parameters == NULL) {

        $bridgeTestResults = perform_data_test(NULL, 0);

    } else {

        $params_key = array_keys($params)[$test_number];
        $bridgeTestResults = perform_data_test($params[$params_key], $test_number);
	
    }

	echo json_encode($bridgeTestResults);	

}

function perform_data_test($datas, $number) {

    if($datas == NULL) {
        return run_test([]);
    }
    
	$datas = json_decode($datas, true);
	$params = [];
	foreach($datas as $element) {

		$id = $element['identifier'];
		if(!array_key_exists($number, $GLOBALS['params'][$_GET['bridge']])) {
			$value = $GLOBALS['params'][$_GET['bridge']][$id];
		} else  {
			$value = $GLOBALS['params'][$_GET['bridge']][$number][$id];
		}

		$params[$element['identifier']] = $value;

	}

	return run_test($params);
	

}

function run_test($parameters) {

    $cache = Cache::create('FileCache');
    
	$tested_bridge = Bridge::create($_GET['bridge']);

    $tested_bridge->setDatas($parameters);
    $tested_bridge->setCache($cache);
    
	$result = $tested_bridge->getDatas();
    
	$answer = [];
	
	$hasContent = 0;
	$hasDate = 0;
	$hasTitle = 0;

	foreach($result as $element) {

		if($element->content != null) $hasContent++;
		if($element->timestamp != null) $hasDate++;
		if($element->title != null) $hasTitle++;

	}
	
	$answer['elementHasDate'] = $hasDate;
	$answer['elementHasContent'] = $hasContent;
	$answer['elementHasTitle'] = $hasTitle;


	if($hasContent != $hasDate || $hasDate != $hasTitle) {

		$answer["status"] = "warning";
        $answer["message"] = "";
        
        if($hasDate < $hasContent || $hasDate < $hasTitle) {
            $answer["message"] .= "Some elements haven't got dates. ";
        }
        if($hasTitle < $hasContent || $hasTitle < $hasDate) {
            $answer["message"] .= "Some elements haven't got titles. ";
        }
        if($hasContent < $hasTitle || $hasContent < $hasDate) {
            $answer["message"] .= "Some elements haven't got content.";
        }
        

	} else if($hasContent == 0) {
        $answer["status"] = "failed";
    } else {
		$answer["status"] = "sucess";
	}
	return $answer;

}
?>
