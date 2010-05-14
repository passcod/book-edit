<?php

function getRemoteFile($url)
{
	// get the host name and url path
	$parsedUrl = parse_url($url);
	$host = $parsedUrl['host'];
	if (isset($parsedUrl['path'])) {
		$path = $parsedUrl['path'];
	} else {
		// the url is pointing to the host like http://www.mysite.com
		$path = '/';
	}

	if (isset($parsedUrl['query'])) {
		$path .= '?' . $parsedUrl['query'];
	}

	if (isset($parsedUrl['port'])) {
		$port = $parsedUrl['port'];
	} else {
		// most sites use port 80
		$port = '80';
	}

	$timeout = 10;
	$response = '';

	// connect to the remote server
	$fp = @fsockopen($host, $port, $errno, $errstr, $timeout );

	if( !$fp ) {
		echo "Cannot retrieve $url";
	} else {
		// send the necessary headers to get the file
		fputs($fp, "GET $path HTTP/1.0\r\n" .
		"Host: $host\r\n" .
		"User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
		"Accept: */*\r\n" .
		"Accept-Language: en-us,en;q=0.5\r\n" .
		"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
		"Keep-Alive: 300\r\n" .
		"Connection: keep-alive\r\n" .
		"Referer: http://$host\r\n\r\n");

		// retrieve the response from the remote server
		while (!feof($fp)) {
			$line=fgets($fp, 1024);               
			if (stristr($line,"location:")!="") {
				$redirect=preg_replace("/location:/i","",$line);
				$redirect = trim($redirect);
				return getRemoteFile($redirect);
			}
			$response .= $line;
		}
		
		fclose( $fp );

		// strip the headers
		$pos      = strpos($response, "\r\n\r\n");
		$response = substr($response, $pos + 4);
	}
	
	// return the file content
	return $response;
}

function get_web_page( $url )
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => true,    // don't return headers
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
    );
    
    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    
    $data = curl_exec($ch);
    $info =    curl_getinfo($ch);
    $http_code = $info['http_code'];
    
    if ($http_code == 301 || $http_code == 302 || $http_code == 303) {
        list($header) = explode("\r\n\r\n", $data, 2);
        
        $matches = array();
        preg_match('/(Location:|URI:)(.*?)\n/', $header, $matches);
        $url = trim(array_pop($matches));
        $url_parsed = parse_url($url);
        
        if (isset($url_parsed['host'])) {
            return get_web_page($url);
        }
    }

    elseif($http_code == 200){
        $matches = array();
        preg_match('/(<meta http-equiv=)(.*?)(refresh)(.*?)(url=)(.*?)[\'|"]\s*>/', strtolower($data), $matches);
        $url = trim(array_pop($matches));
        $url_parsed = parse_url($url);
        
        if (isset($url_parsed['host'])) {
            return get_web_page($url);
        }
    }
    
    curl_close( $ch );

    return $data;
}


$source = 'http://toolserver.org/~hippietrail/randompage.fcgi?langname=English';

if  ( in_array  ('curl', get_loaded_extensions()) )
{
	$file = get_web_page($source);

	$matches = array();
	$c = preg_match("/<title>(.+) - Wiktionary<\/title>/", $file, $matches);

	echo ( !empty($matches[1]) )? "<a href='".$matches[1]."' target='_blank'>".$matches[1]."</a>" : "";
}
else
{
	$file = getRemoteFile($source);

	$matches = array();
	$c = preg_match("/<title>(.+) - Wiktionary<\/title>/", $file, $matches);

	echo ( !empty($matches[1]) )? "<a href='".$matches[1]."' target='_blank'>".$matches[1]."</a>" : "";
}

?>
