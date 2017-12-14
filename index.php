<?php
	function getCookie() {
		$c = curl_init();
		$url = 'https://webconnect.bloomfield.org/zangle/StudentPortal/';

		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_HEADER, 1);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION,1);  
		curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
		
		$output = curl_exec($c);
		curl_close($c);
		preg_match('/^Set-Cookie:\s*([^;]*)/mi', $output, $matches);
		$cookie = preg_replace('/^Set-Cookie:\s*/mi', "", $matches[0]);
		return $cookie;
	}

	function loginUser($pin, $password, $cookie) {
		$c = curl_init();
		$url = 'https://webconnect.bloomfield.org/zangle/StudentPortal/Home/Login';

		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_COOKIE, $cookie);
		curl_setopt($c, CURLOPT_POST, 1);
		curl_setopt($c, CURLOPT_POSTFIELDS, "Pin=".$pin."&Password=".$password);
		curl_setopt($c, CURLOPT_HEADER, 0);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION,1);  
		curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
		$output = curl_exec($c);
		curl_close($c);
		return $output;
	}

	function getMain($cookie) {
		$c = curl_init();
		$url = 'https://webconnect.bloomfield.org/zangle/StudentPortal/Home/PortalMainPage/';

		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_COOKIE, $cookie);
		curl_setopt($c, CURLOPT_HEADER, 0);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION,1);  
		curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
		$output = curl_exec($c);
		curl_close($c);
		preg_match('/(<tr\s*id\s*=\s*".*"\s*class\s*=\s*".*sturow".*>)/', $output, $tr);
		preg_match('/(\s*id\s*=\s*"[^"]*")/', $tr[0], $match);
		return preg_replace('/(["=\s[id])/', "", $match[0]);
	}

	function selectUser($user, $cookie) {
		$c = curl_init();
		$url = 'https://webconnect.bloomfield.org/zangle/StudentPortal/StudentBanner/SetStudentBanner/'.$user;

		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_COOKIE, $cookie);
		curl_setopt($c, CURLOPT_HEADER, 0);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION,1);  
		curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
		curl_exec($c);
	}

	function getAssignments($cookie) {
		$c = curl_init();
		$url = 'https://webconnect.bloomfield.org/zangle/StudentPortal/Home/LoadProfileData/Assignments';

		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_COOKIE, $cookie);
		curl_setopt($c, CURLOPT_HEADER, 0);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION,1);  
		curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
		$output = curl_exec($c);
		return $output;
	}

	if(isset($_POST['Pin']) && !empty($_POST['Pin'])) {
		$pin = $_POST['Pin'];
	}

	if(isset($_POST['Password']) && !empty($_POST['Password'])) {
		$password = $_POST['Password'];
	}

	$cookie = getCookie();
	$loggedIn = loginUser($pin, $password, $cookie);
	$mainPageUserId = getMain($cookie);
	selectUser($mainPageUserId, $cookie);
	$assignments = getAssignments($cookie);

	echo $assignments;
?>