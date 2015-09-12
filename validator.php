<!DOCTYPE html>
<html>
	<head>
		<title>rss-bridge-tester</title>
		<meta charset='utf-8'>
		<link rel="stylesheet" href="css/style.css" />
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

$PATH_TO_RSS_BRIDGE = "http://teromene.fr/projects/rss-bridge-tester/";

require_once __DIR__ . '/lib/RssBridge.php';

Bridge::setDir(__DIR__ . '/bridges/');
Format::setDir(__DIR__ . '/formats/');
Cache::setDir(__DIR__ . '/caches/');

$bridges = Bridge::searchInformation();



$parameters = array("BandcampBridge" => array("tag" => "test"),
		    "CpasbienBridge" => array("q" => "test"),
		    "CryptomeBridge" => array("n" => "5"),
		    "BooruprojectBridge" => array("i" => "shadowcrest.booru.org", "p" => "post", "t" => "cats"),
		    "DanbooruBridge" => array("p" => "popular", "t" => "test"),
		    "DauphineLibereBridge" => array("u" => "france-monde"),
		    "DailymotionBridge" => array(array("u" => "test"), array("p" => "x3w8ri_knamhawall_pyaar-ki-yeh-ek-kahani-1st-playlist"), array("s" => "test", "pa" => "1")),
		    "DollbooruBridge" => array("p" => "3"),
		    "DuckDuckGoBridge" => array("u" => "test"),
		    "EZTVBridge" => array("i" => "1253"),
		    "FacebookBridge" => array("u" => "Numerama"),
		    "FourchanBridge" => array("t" => "https://boards.4chan.org/b/thread/638605020"),
		    "FlickrTagBridge" => array(array("u" => "7326810@N08"), array("q" => "photo")),
		    "Freenews" => array("id" => ""),
		    "Gawker" => array("site" => "lifehacker"),
		    "GelbooruBridge" => array("p" => "1", "t" => "test"),
		    "GiphyBridge" => array(array("s" => "test"), array("s" => "test", "n" => "12")),
		    "GooglePlusPostBridge" => array("username" => "+LinusTorvalds"),
		    "GoogleSearchBridge" => array("q" => "test"),
		    "HDWallpapersBridge" => array("c" => "anime", "m" => "5", "r" => "1920x1200"),
		    "IdenticaBridge" => array("u" => "bobjonkman"),
		    "InstagramBridge" => array("u" => "korben00"),
		    "KonachanBridge" => array("p" => "1", "t" => "hatsune_miku"),
		    "LeBonCoinBridge" => array("r" => "nord_pas_de_calais", "k" => "meuble"),
		    "LolibooruBridge" => array("p" => "1", "t" => "long_image"),
		    "MilbooruBridge" => array("p" => "1", "t" => "photo"),
		    "MspabooruBridge" => array("p" => "1", "t" => "blood"),
		    "OpenClassroomsBridge" => array("u" => "informatique"),
		    "ParuVenduImmoBridge" => array("minarea" => "1", "maxprice" => "10000000", "pa" => "FR", "lo" => "62"),
		    "PickyWallpapersBridge" => array("c" => "anime", "s" => "other", "m" => "10", "r" => "1920x1200"),
		    "PinterestBridge" => array(array("u" => "petersom", "b" => "peter-som-spring-2014"), array("q" => "photo")),
		    "Rule34Bridge" => array("p" => "1", "t" => "photo"),
		    "Rule34pahealBridge" => array("p" => "1"),
		    "SafebooruBridge" => array("p" => "1", "t" => "photo"),
		    "SakugabooruBridge" => array("p" => "1", "t" => "various_artists"),
		    "ScoopItBridge" => array("u" => "test"),
		    "SuperbWallpapersBridge" => array("c" => "anime", "m" => "10", "r" => "1920x1200"),
		    "SoundcloudBridge" => array("u" => "futureisnow"),
		    "TagBoardBridge" => array("u" => "test"),
		    "TbibBridge" => array("p" => "1", "t" => "test"),
		    "ThePirateBayBridge" => array("q" => "test"),
		    "TwitchApiBridge" => array(array("channel" => "cdv_nesblog", "broadcasts" => "true"), array("channel" => "cdv_nesblog", "broadcasts" => "true", "limit" => "5")),
		    "TwitterBridge" => array(array("q" => "test"), array("u" => "Mitsukarenai")),
		    "TwitterBridgeClean" => array(array("q" => "test"), array("u" => "Mitsukarenai")),
		    "TwitterBridgeExtended" => array(array("q" => "test"), array("u" => "Mitsukarenai")),
 		    "TwitterBridgeTweaked" => array(array("q" => "test"), array("u" => "Mitsukarenai")),
            "UnsplashBridge" => array("m" => "10", "w" => "1920", "q" => "100"),
		    "WallpaperStopBridge" => array("c" => "animal-wallpaper", "s" => "bear-wallpaper", "m" => "10", "r" => "1920x1200"),
		    "WhydBridge" => array("u" => "u/530642f37e91c862b2b5aa57"),
		    "WordPressBridge" => array("url" => "https://odieuxconnard.wordpress.com", "name" => "test"),
		    "WorldOfTanks" => array("lang" => "en", "category" => "18"),
		    "XbooruBridge" => array("p" => "1", "t" => "photo"),
		    "YandereBridge" => array("p" => "1", "t" => "photo"),
		    "YoutubeBridge" => array(array("u" => "epenser1"), array("c" => "UCHnyfMqiRRG1u-2MsSQLbXA"), array("p" => "PLGPWPtcc-r80AIhTGU5oAbsruqBCtv9JU"), array("s" => "test"))
);

$workingBridges = 0;
$notWorkingBridges = 0;
$noTestParameters = 0;
$emptyBridges = 0;

$startTime = microtime(true);


foreach($bridges as $name => $ele) {

	$bridgeStartTime = microtime(true);
	set_time_limit(50);
	
	echo "<section class='bridge-data'>";
	echo "<h3>".$name."</h3>";
	
	if(count($ele['use']) == 0) {

		
		$result = (file_get_contents($PATH_TO_RSS_BRIDGE."index.php?action=display&bridge=".$name."&format=AtomFormat"));
		verifyResults($result);
		
		
	} else if(isset($parameters[$name])){
		$paramString = '';
		if(count($ele['use']) == 1) {

			foreach($parameters[$name] as $paramName => $paramValue) {

				$paramString .= "&".$paramName."=".urlencode($paramValue);
		
			}
			echo "Test string is ".$paramString." <br />";
		
			$result = file_get_contents($PATH_TO_RSS_BRIDGE."index.php?action=display&bridge=".$name."&format=AtomFormat".$paramString);
	
			verifyResults($result);
			
		} else {
			$useNumber = 1;
			$status = '';
			foreach($parameters[$name] as $use) {
				$paramString = '';
				foreach($use as $paramName => $paramValue) {

					$paramString .= "&".$paramName."=".urlencode($paramValue);
		
				}
				echo "Testing use ".$useNumber." with test string ".$paramString."<br />";
				$useNumber ++;
				$result = file_get_contents($PATH_TO_RSS_BRIDGE."index.php?action=display&bridge=".$name."&format=AtomFormat".$paramString);
	
				if($result == "" || $result === false ) {

					echo "<p style='color:red;'>Error while loading these parameters ".$name."<p>";
					$status = false;

				} else if(!strpos($result, "<feed")) {

					echo "<p style='color:green;'>Bridge ".$name." loaded with parameters ".$paramString." , but no feed outputed. Output is :".$result;
					$status = false;
				} else {

					echo "<p style='color:green;'>Bridge ".$name." is working with parameters ".$paramString."<p>";
					$status = true;

				}
				
			}
			if($status) {
				$workingBridges ++;
			} else {
				$notWorkingBridges++;
			}

		}
	} else {
		
			echo "<p style='color:orange;'>No datas to test bridge ".$name."</p>";
			$noTestParameters++;
	}
	$bridgeExecTime = microtime(true) - $bridgeStartTime;
	if($bridgeExecTime < 8) {
		echo "<p style='font-size:10px;'>Bridge vérifié en ".$bridgeExecTime."</p>";
	} else {
		echo "<p style='color:orange;'>Bridge vérifié en ".$bridgeExecTime."</p>";
	}
	
	echo "<br />";
	echo "</section>";
	

}


	function verifyResults($resultString, $hasMultipleQueries=false) {
	
		global $notWorkingBridges, $workingBridges, $emptyBridges;

		if($resultString == "" ||$resultString === false) {

			echo "<p style='color:red;'>Error while loading bridge ".$name."</p>";
			if(!$hasMultipleQueries) $notWorkingBridges++;

		} else if(!strpos($resultString, "<feed")) {

			if(isset($paramString)) echo "<p style='color:green;'>Bridge ".$name." loaded with parameters ".$paramString." , but no feed outputed. Output is :".$result."</p>";
			if(!isset($paramString)) echo "<p style='color:green;'>Bridge ".$name." loaded, but no feed outputed. Output is :".$result."</p>";
			if(!$hasMultipleQueries) $notWorkingBridges++;
			
		} else if(!strpos($resultString, "<entry")) {
		
			echo "<p style='color:yellow;'>The bridge ".$name." is working but is returning an empty data set, you should check manually. </p>";
			if(!$hasMultipleQueries) $workingBridges ++;		
			if(!$hasMultipleQueries) $emptyBridges++;
		} else {

			if(isset($paramString)) echo "<p style='color:green;'>Bridge ".$name." is working with parameters ".$paramString."</p>";
			if(!isset($paramString)) echo "<p style='color:green;'>Bridge ".$name." is working</p>";
			if(!$hasMultipleQueries) $workingBridges ++;

		}
	
	}
?>
	<h4>Output of the test : </h4>
	<p>We have <?php echo count($bridges);?> bridges, with <span style="color:green;"><?php echo $workingBridges; ?></span> working bridges,
	<span style="color:red;"><?php echo $notWorkingBridges; ?></span> not working bridges and 
	<span style="color:orange;"><?php echo $noTestParameters; ?></span> bridges without parameters to test.<br />
	You should check <span style="color:yellow;"><?php echo $emptyBridges; ?></span> bridges which are returning empty datas.</p>
	
	<p>Page generated in <?php echo microtime(true) - $startTime ?> </p>

	</body>
</html>
