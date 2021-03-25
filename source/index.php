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
	
	
	function rand_string($length)
	{
		$str = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 
			'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's', 
			't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D', 
			'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O', 
			'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z', 
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		$keys = array_rand($str, $length); 
		$rand_string = '';
		for($i = 0; $i < $length; $i++)
		{
			$rand_string .= $str[$keys[$i]];
		}
		return $rand_string;
	}
	
	session_start();
	if (!isset($_SESSION["userId"])) {
		$_SESSION["userId"] = rand_string(12);
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
		<form method="get" action="https://github.com/login/oauth/authorize">
			<input type="hidden" name="client_id" value="<? echo $_github["client_id"]; ?>" />
			<input type="hidden" name="state" value="<? echo $_SESSION["userId"] ?>" />
			<button type="submit">使用 GitHub 登录</button>
		</form>
	</body>
</html>