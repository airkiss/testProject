<?php
/* Created By Airkiss
	Self-Test 
	2013/9/16
*/
# Memcached Supported
$PRODUCTION_URL = "http://api.dddgaming.com:3003/api/get_exchange_rates";
$TEST_URL = "http://111.235.134.234:3003/api/get_exchange_rates";
if(class_exists("Memcached"))
{
	$mc = new Memcached();
	$mc->addServer('127.0.0.1',11211);
	$resData = $mc->get('CoinRate');
	if($mc->getResultCode() == Memcached::RES_NOTFOUND)
	{
		if(function_exists("curl_init"))
		{
			echo "Try fetch rate table...<BR>";
			$curl_link = curl_init();
			curl_setopt($curl_link, CURLOPT_URL, $TEST_URL); 
			curl_setopt($curl_link, CURLOPT_VERBOSE, 0); 
			curl_setopt($curl_link, CURLOPT_HEADER, 0); 
			curl_setopt($curl_link, CURLOPT_RETURNTRANSFER, 1); 
			$resData = curl_exec($curl_link); 
			if(!curl_errno($curl_link))
			{ 
		  		$info = curl_getinfo($curl_link); 
		  		echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'] . "<BR>"; 
			}
			else
			{ 
				echo 'Curl error: ' . curl_error($curl_link);
				die();
			} 
			curl_close($curl_link);
			$mc->set('CoinRate',$resData,86400);	# Keep the cache one day
		}
		else
		{
			echo "Curl Not Exists!<BR>";
			die();
		}
	}
	
	if(function_exists("json_decode"))
	{
		echo "Try parse data....<BR>";
		$RateArray = json_decode($resData);
		foreach($RateArray as $key=>$valueObject)
			echo $valueObject->code . '=>' . $valueObject->basic . "<BR>";
	}
	else
		echo "Json Not Exists!<BR>";
}
else
	echo "Memcached No Supported!<BR>";
# SQL Access
if(class_exists("PDO"))	{
	echo "PDO Class Exists!<BR>DataBase Self-Test<BR>";
	try {
		$dbh = new PDO('mysql:host=172.31.82.110;port=3306;dbname=xxxx;charset=utf8', 'mingda', 'mingda', 
				array(	PDO::ATTR_PERSISTENT => false,
				)
			);
		$stmt = $dbh->prepare("CALL getname()");
		// call the stored procedure
		$stmt->execute();
		echo "outputting...<BR>";
		while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
			echo "output: ".$rs->name."<BR>";
		}
		echo "<BR><B>".date("r")."</B>";
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/><BR>";
		die();
	}
}
else
	echo "PDO Class Not Exists!<BR>";

?>
