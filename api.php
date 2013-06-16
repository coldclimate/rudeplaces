<?php
require 'vendor/autoload.php';


$app = new \Slim\Slim();

$app->get('/lat/:lat/long/:long', function ($lat,$long) {
	$connection = new MongoClient();
	$collection = $connection->rude->places;

	$query = array( 'loc' => array ('$near' => array("lat" => (float)($lat), "long" => (float)($long))) );

	$projection = array("name" => true, "loc" => true);
	$cursor = $collection->find($query,$projection);
	$cursor->limit(10);
	$results=array();

	foreach($cursor as $rude){
		$results[]=array("id" => $rude["_id"]->{'$id'},"name" => $rude["name"], "lat" =>$rude["loc"]["lat"], "long" =>$rude["loc"]["long"]);
	}
	echo json_encode($results);

});

$app->run();