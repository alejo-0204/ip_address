<html>
	<body>
		<form action='' method='post'>
			<input type='text' id='netmask' name='netmask' />
			<input type='submit' value="Calculate"/>
		</form>
	</body>
</html>

<?php

/** 
 * Get the number of netmask bits from a netmask in presentation format 
 * 
 * @param string $netmask Netmask in presentation format 
 *  
 * @return integer Number of mask bits 
 * @throws Exception 
 */  
function netmask2bitmask($netmask) {

	$isIpV6 = false;
    $isIpV4 = false;
	$number = 0;
	if($netmask == null)
	{
		throw new Exception("Please type a <b>Netmask</b>.");
	}
	
    if(filter_var($netmask, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) 
	{
        $isIpV6 = true;
    }
    else if(filter_var($netmask, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) 
	{
        $isIpV4 = true;
    }
    if(!$isIpV4 && !$isIpV6) 
	{
		throw new Exception("Please type a valid <b>Netmask</b>.");
    }
	
	if($isIpV6)
	{
		$array = explode(":", $netmask);
		$array = array_filter($array);		

		foreach ($array as $value)
		{
			$number += strlen(rtrim(base_convert($value,16,2),'0'));
		}		
		echo $number; 
	}
	
	if($isIpV4)
	{	
		if($netmask === "0.0.0.0")
		{
			echo "0";
			return;
		}
		
		$long = ip2long($netmask);
		$base = ip2long('255.255.255.255');
		echo 32-log(($long ^ $base)+1,2);
	}
}

if(isset($_POST['netmask']))
{
	try 
	{
		netmask2bitmask($_POST['netmask']);
	}
	catch(Exception $e) 
	{
		echo '<h2><b>Message:</b></h2> '.$e->getMessage();
	}
}
?>
