<?php

// setting up modx access

require_once ('/PATH_TO_MODX/config.core.php');
require_once ('/PATH_TO_MODX_CORE/model/modx/modx.class.php');
$modx = new modX();
$modx->initialize('web');
$modx->getService('error', 'error.modError');

// define locations  UPDATE BEFORE USING!
// base file path to where static files will be saved
$basepath = '/FULL_SERVER_PATH/public_html/';

// url to your modx install
$mdxurl = 'http://URL-TO-MODX-INSTALL/';

// - - - - - - - - - end configuration - - - - - - - - - 


// getting the published ids
// get collection of resources, determine id and if published
$docs = $modx->getCollection('modResource');
foreach ($docs as $doc) {
      $pub = $doc->get('published');
      $folder = $doc->get('isfolder');
      $rid = $doc->get('id');
      $web_url = $doc->get('uri');

      // if published, fetch url and build static webpage
      if (($pub == '1') && ($folder == '0')) {

      // determine if folders exist and create if not    
      $path_parts = pathinfo($basepath . $web_url);
      $target_path = $path_parts['dirname'];
          
      if (!file_exists($target_path)) {
         mkdir($target_path, 0755, true);
      }
      		
      // get the webpage from MODX
      $contents = file_get_contents($mdxurl . 'index.php?id=' . $rid);
         
      // remove comments
      $contents = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/Uis', '', $contents);
      	
      // minify
      $contents = preg_replace('/^\s+|\n|\r|\s+$/m', '', $contents);

      // save new copy
      file_put_contents($mdxurl . $web_url, $contents);
		
   }
	
}
