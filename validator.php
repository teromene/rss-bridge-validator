<!DOCTYPE html>
<html>
	<head>
		<title>rss-bridge-tester</title>
		<meta charset='utf-8'>
		<link rel="stylesheet" href="style.css" />
		<style>
			.error {

				color : red;

			}

			.warning {

				color: orange;

			}
			.status {

				font-size : 1.2em;

			} 

			.success {

				color : green;

			}

		</style>
	</head>
	<body>

<?php

/**===================================================================**/
/*|                       rss-bridge-tester                           |*/        
/*|                          par teromene                             |*/
/*|																	  |*/
/*| Ce projet vise à pouvoir tester automatiquement le fonctionnement |*/
/*| des bridges de rss-bridge pour détecter ceux qui nécessitent une  |*/
/*|	mise à jour.													  |*/
/*| Pour qu'il fonctionne, vous devez activer tous les bridges dans	  |*/
/*|	votre whitelist de rss-bridge, et remplir la variable             |*/
/*|	$PATH_TO_RSS_BRIDGE	ci-dessous.									  |*/
/*|	Le test utilise des données bidons pour les bridges qui prennent  |*/
/*|	des paramètres, données stoqués dans l'array $parameters.         |*/
/**===================================================================**/

ini_set('display_errors','1'); error_reporting(E_ALL);  // For debugging only.

require_once '../rss-bridge/lib/RssBridge.php';
require_once 'test_datas.php';

Bridge::setDir('../rss-bridge/bridges/');
Format::setDir('../rss-bridge/formats/');
Cache::setDir('../rss-bridge/caches/');


?>

<div>
	<p>Status : <span id="system-status">Test in progress...</span></p>
	<p id="progress"><span id="progress-actual">0</span>/<span id="progress-total">0</span></p>
</div>
<div id="resultSet">

</div>

<script type="text/javascript">
	var testArray = 
	<?php
		echo "[";
		foreach(Bridge::listBridges() as $bridgeName) {

			echo "['".$bridgeName ."',";
			if(array_key_exists($bridgeName, $GLOBALS['params'])) {
				echo count($GLOBALS['params'][$bridgeName]) - 1;
			} else {
				echo "0";
			}
			echo "],";
		}
		echo "]";
	?>

	var lastQueried = 0;
	var queryNumber = -1;
	var hasPrinted = false;
	var writeBody = document.getElementById("resultSet");

	function performRequest() {
		
		if(lastQueried < testArray.length) {

			var t_Array = testArray[lastQueried];

			if(queryNumber == 0 && !hasPrinted) {

				printBrigeTestStart(t_Array[0]);
				hasPrinted = !hasPrinted;
				performRequest();

			}
			if(queryNumber < t_Array[1]) {

				queryNumber++;
				console.log("Requesting "+t_Array[0]+" test n°"+queryNumber);

				var oReq = new XMLHttpRequest();

				oReq.addEventListener("load", testRenderer);
				oReq.addEventListener("load", performRequest);
				oReq.open("GET", "bridge_test.php?bridge="+encodeURIComponent(t_Array[0])+"&t_number="+queryNumber);
				oReq.send();

			} else {

				printBrigeTestEnd();
				queryNumber = -1;
				lastQueried++;
				hasPrinted = !hasPrinted;

			}

		} else {
			console.log("Ended");
		}
	}

	function testRenderer(resultSet) {

		console.log(resultSet);
		bridgeName = resultSet.target.responseURL.split("bridge=")[1].split("&")[0];

		if(resultSet.target.status != 200) {

			printFailedTest();

		} else {
			try {
				var resultSet = JSON.parse(resultSet.target.responseText);
				if(resultSet.status == "sucess") {
					printTestSuccess(resultSet);
				} else if(resultSet.status == "warning") {
					printTestWarning(resultSet);
				} else {
					printFailedTest();
				}


			} catch (e) {
				console.log(e);
				printFailedTest();
			}

		}

	}


	function write(string) {

		writeBody.lastChild.innerHTML += string;

	}

	function printTestSuccess(resultSet) {

		write("<p class=\"success\">Test success ! Number of elements : "+resultSet.elementHasContent+" <br /></p>");

	}

	function printTestWarning(resultSet) {


		returnString = "<p class=\"warning\">Test success ! However : <br />";
		returnString += resultSet.message+"<br />";
		returnString += "List of elements : "+resultSet.elementHasDate+" with date, "+resultSet.elementHasTitle+" with title, "+resultSet.elementHasContent+" with content.</p>";

		write(returnString);

	}

	function printFailedTest() {

		write("<p class=\"error\">Test failed !</p>");

	}

	function printBrigeTestStart(bridgeName) {
		writeBody.innerHTML += "<section>Test for "+bridgeName;
	}
	function printBrigeTestEnd() {
		writeBody.innerHTML += "</section>";
	}

</script>


<?php


?>
