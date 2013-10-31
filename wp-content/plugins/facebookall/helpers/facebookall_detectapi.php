<?php 

if (isset($_GET['apikey'])) {
  $apikey = trim($_GET['apikey']);
  $apisecret = trim($_GET['apisecret']);
  $apicred = $_GET['api_request'];
  check_api_settings($apikey, $apicred);
}

/**
 * Check api credential settings.
 */

  function check_api_settings($apikey, $apicred) {
    if (isset($apikey)) {
	   $url = "https://graph.facebook.com/".$apikey;
       if ($apicred == 'curl') {
         if (in_array('curl', get_loaded_extensions ()) AND function_exists('curl_exec')) {
           $curl = curl_init();
	       curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	       curl_setopt( $curl, CURLOPT_URL, $url );
	       curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
           $app_response = curl_exec($curl);
		   $curl_response = curl_getinfo($curl);
		   curl_close( $curl );
           $app_result = json_decode($app_response);
		   if ($curl_response['http_code'] == 200) {
             echo '<div id="apisuccess">Your API settings working perfectly. Please Save your current Settings.</div>';
		     die();
           }
		   else {
		     echo '<div id="apierror">Returned error: curl response ='.$curl_response['http_code'].' ,Facebook response=';print_r($app_response).'</div>';
		     die();
		   }
		 }
		 else {
          echo '<div id="apierror">Your '.$apicred.' settings not working try to change API Handler Settings.</div>';
		  die();
		}
      }
      else {
        $app_response = @file_get_contents($url);
		$fopen_response = @$http_response_header;
		if ($fopen_response[0] == 'HTTP/1.0 200 OK' AND !empty($fopen_response[0])) {
		  echo '<div id="apisuccess">Your API settings working perfectly. Please Save your current Settings.</div>';
		  die();
        }
		else if ($fopen_response == NULL) {
          if (!in_array('https', stream_get_wrappers())) {
            echo '<div id="apierror">Openssl not enabled for working fopen on your server.</div>';
			die();
          }
		} 
        else if ($fopen_response[0] != 'HTTP/1.0 200 OK' AND !empty($fopen_response[0])){
          echo '<div id="apierror">Returned error: fopen response ='.$fopen_response[0].' ,Facebook response=';print_r($http_response_header).'</div>';
		  die();
        }
		else {
          echo '<div id="apierror">Your '.$apicred.' settings not working try to change API Handler Settings.</div>';
		  die();
		}
	  }
    }
  }