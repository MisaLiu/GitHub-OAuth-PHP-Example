<?php
	/* 
	    __  ____               __    _      
	   /  |/  (_)________ _   / /   (_)_  __
	  / /|_/ / / ___/ __ `/  / /   / / / / /
	 / /  / / (__  ) /_/ /  / /___/ / /_/ / 
	/_/  /_/_/____/\__,_/  /_____/_/\__,_/  
                                        
	*/
	$_github = array();
	
	// ============={参数设置区}=============
	// 必须设置下列要求的参数才可正常运行
	
	$_github["client_id"] = ""; // 应用的 Client ID
	$_github["client_secret"] = ""; // 应用的 Client Secret
	$_github["appName"] = ""; // 应用名称，可用应用创建人 ID 代替



	function getUrl($url, $data = "", $appname = "", $type = 1, $access_token = "", $token_type = "") {
		$curl = curl_init();
		
		if ($type == 1) {
			$headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
			
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			
		} elseif ($type == 2) {
			$headerArray = array("Authorization: " . $token_type . " " . $access_token,  "Accept:application/vnd.github.v3+json");
			
			curl_setopt($curl, CURLOPT_USERAGENT, $appname);
		}
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
	
	session_start();
	if (!isset($_SESSION["userId"])) {
		header("HTTP/1.0 404 Not Found");
	}
	
	if (isset($_GET)) {
		if ($_GET["state"] == $_SESSION["userId"] && isset($_GET["code"])) {
			
			$callback = getUrl("https://github.com/login/oauth/access_token" . "?client_id=" . $_github["client_id"] . "&client_secret=" . $_github["client_secret"] . "&code=" . $_GET["code"]);
			
			$callback = json_decode($callback, true);
			$user_info = getUrl("https://api.github.com/user", "", $_github["appName"], 2, $callback["access_token"], $callback["token_type"]);
			
			$user_info = json_decode($user_info, true);

		}
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
		<meta name="author" content="Misa Liu (https://misaliu.top)">
		<title>GitHub Login Test</title>
	</head>
	<body>
		<? if (!empty($user_info["id"])) { ?>
			<img src="<? echo $user_info["avatar_url"]; ?>" style="width:50px;height:50px;" /><br>
			您好，<? echo $user_info["login"]; ?><br>
			您已成功使用 GitHub 登录本站。
			
		<? } else { ?>
			貌似出了什么问题，获取用户信息失败了...<br>
			错误信息：
			<? if (!empty($user_info["message"])) {
					echo $user_info["message"];
				} elseif (!empty($callback["message"])) {
					echo $callback["message"];
				}
		 } ?>
	</body>
</html>
<? session_destroy(); ?>