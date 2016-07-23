<?php
//NOTES:
//条粉肠 Jens Segers好像后来用了别的框架重写，网上找到2013的（即本文件最接近的) 在这里：
//https://github.com/jw2013/sharedProxy/blob/master/proxy.php
//暂时未知有没有解决不了的反向问题，先用来玩着先.
//201509: 还真有个BUG，就是在上传附件时不知道什么原因 500，所以暂时后台不使用这个入口....
//2016: 这个BUG已经解决了.查找下方带 hack的位置.
//20160723: not yet fully test the FILES (upload) in new cmp_app_server...TODO
class cmppx
{
	protected $ch;

	protected $config = array();

	function __construct()
	{
		$config = array();

		$config['timeout'] = 58;//ISP like ALIYUN Said 1min...

		$this->config = $config;

		// initialize curl
		$this->ch = curl_init();
		//@curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($this->ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);//TODO
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);//
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);//
		curl_setopt($this->ch, CURLOPT_HEADER, true);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->config["timeout"]);
	}

	public function forward($url = '',$server,$port=80,$scheme='http')
	{
		// build the correct url
		$config['server'] = $server;
		$config['port']  = $port;
		$this->config=$config;

		//wjc.patch:
		$url = "$scheme://" . $this->config["server"] . ":" . $this->config["port"] . "/" . ltrim($url, "/");

		// set url
		curl_setopt($this->ch, CURLOPT_URL, $url);

		// forward request_headers
		$req_headers = $this->get_request_headers();
		$this->set_request_headers($req_headers);

		// forward post
		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(in_array($this->get_content_type($header), array('application/x-www-form-urlencoded','multipart/form-data')))
			{
				$this->set_post($_POST);
			}
			else
			{
				// just grab the raw post data
				$fp = fopen('php://input','r');
				$post = stream_get_contents($fp);
				fclose($fp);
				$this->set_post($post);
			}
		}

		$data = curl_exec($this->ch);
		$info = curl_getinfo($this->ch);

		$body = $info["size_download"] ? substr($data, $info["header_size"], $info["size_download"]) : "";

		curl_close($this->ch);

		$resp_headers = substr($data, 0, $info["header_size"]);
		$this->set_response_headers($resp_headers);

		if($body){
			echo $body;
		}
	}

	/**
	 *  Get the content-type of the request
	 */
	protected function get_content_type( $headers )
	{
		foreach( $headers as $name => $value ){
			if( 'content-type' == strtolower($name) ){
				$parts = explode(';', $value);
				return strtolower($parts[0]);
			}
		}
		return null;
	}

	/**
	 * Get the headers of the current request
	 */
	protected function get_request_headers()
	{
		// use native getallheaders function
		if (function_exists('getallheaders')) return getallheaders();

		// fallback
		$headers = '';
		foreach ($_SERVER as $name => $value)
		{
			if (substr($name, 0, 5) == 'HTTP_')
			{
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}

		return $headers;
	}

	/**
	 * Pass the request headers to cURL
	 *
	 * @param array $request
	 */
	protected function set_request_headers($request)
	{
		// headers to strip
		$strip = array("Content-Length", "Host");
		$flag_file=count($_FILES);//hack

		$headers = array();
		foreach ($request as $key => $value)
		{
			//hack:
			if($key=="Content-Type")
			{
				$headers[] = "Debug-Flag-File: $flag_file";
				//$headers[]="$key: multipart/form-data;";
				if($flag_file) continue;//if have file upload, skip this header and curl will pack it later..
			}
			if ($key && !in_array($key, $strip))
			{
				$headers[] = "$key: $value";
			}
		}

		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
	}

	/**
	 * Pass the cURL response headers to the user
	 *
	 * @param array $response
	 */
	protected function set_response_headers($response)
	{
		$dbga=array();

		// headers to strip
		$strip = array("Transfer-Encoding");

		// split headers into an array
		$headers = explode("\n", $response);

		$dbga=$response;

		// process response headers
		foreach ($headers as &$header)
		{
			// skip empty headers
			if (!$header) continue;

			// get header key
			$pos = strpos($header, ":");
			$key = substr($header, 0, $pos);

			//NOTES: wjc: just let front to do the redirect...
			// modify redirects
			if (strtolower($key) == "location")
			{
				//$base_url = $_SERVER["HTTP_HOST"];
				//$base_url .= rtrim(str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["SCRIPT_NAME"]), "/");

				//// replace ports and forward url
				//$header = str_replace(":" . $this->config["port"], "", $header);
				//$header = str_replace($this->config["server"], $base_url, $header);
				header($header);die;
			}

			// set headers
			if (!in_array($key, $strip))
			{
				header($header, FALSE);
			}
		}
		return $dbga;
	}

	/**
	 * Set POST values including FILES support
	 *
	 * @param array $post
	 */
	protected function set_post($post)
	{
		// file upload support
		if (count($_FILES))
		{
			//hack... repost the files to remote...
			//https://gist.github.com/iovar/9091078
			//foreach ($_FILES as $key => $value) {
			//	$full_path = realpath( $_FILES[$key]['tmp_name']);
			//	$data_str[$key] = '@'.$full_path;
			//}
			$post2=array();
			foreach ($_FILES as $key => $file)
			{
				//wjc
				$full_path = realpath( $file['tmp_name'] );
				//$full_path = quoted_printable_encode($full_path);
				$filename=$file['name'];
				$post2[$key] = '@'.$full_path .';filename='.$filename;
				;
				//$post["file$c"] = '@'.$full_path;
				//var_dump($post);die;
			}
			//$post=$post2+$post;//WTF...
			//$post=array_merge($post2,$post);//WTF...
			$post=$post2;
		}
		else if( is_array( $post ) )
		{
			$post = http_build_query($post);
		}

		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
		return $post;//for debug
	}
}
