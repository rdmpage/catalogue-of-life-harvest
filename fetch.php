<?php

require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/couchsimple.php');


$root = '536dda4f800637e1591617e74cd651be';
//$root = 'b2228c1741e75697e6eb4fb3a3b43002';

//$root = '16d67d9bc4107dbbb362a6088dcc22c9';

$root = '63904d01b32de267c17adcaddcda47ad'; // Begonia (stopped, I got bored)

$root = 'ac86cda4f305ec7e24b916d1f0b9fa3d';

$stack = array();
$stack[] = $root;

while (count($stack) > 0)
{
	$id = array_pop($stack);

	$url = 'http://www.catalogueoflife.org/col/webservice?id=' . $id . '&format=json&response=full';
	
	$json = get($url);
	
	if ($json != '')
	{
	
		$obj = json_decode($json);
		
		$obj->_id = $obj->id;
		
		$couch->add_update_or_delete_document($obj->results[0],  $obj->_id);
	
		echo $obj->results[0]->name . "\n";
	
	
		// children (next part of the tree to parse)
		if (isset($obj->results[0]->child_taxa))
		{
			foreach ($obj->results[0]->child_taxa as $child)
			{
				$stack[] = $child->id;
			}
		}
		
		// synonyms
		if (isset($obj->results[0]->synonyms))
		{
			foreach ($obj->results[0]->synonyms as $synonym)
			{
				$stack[] = $synonym->id;
			}
		}
		
		
	}
	
}


?>