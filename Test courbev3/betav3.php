<?php
//connection at github
function my_get_json($url){
  $base = "https://api.github.com/";
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $base . $url);
  curl_setopt($curl, CURLOPT_USERAGENT, "Farconer");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($curl, CONNECTTIMEOUT, 1);
  $content = curl_exec($curl);
  curl_close($curl);
  return $content;
}
function my_get_repo($name_orga)
{
	$parsed_json = json_decode(my_get_json("orgs/$name_orga/repos?access_token=864c7603a908717989fdea759e7c46b7048e080b"),true);
	$i = 0;
	while (isset($parsed_json[$i]['full_name']))
	{
		$tab[$i] = $parsed_json[$i]['full_name'];
		$i++;
	}
	return ($tab);
}
function get_nbr_contrib($tab)
{
	$weeks = array();
	$first = true;
	for ($i = 0; $i < count($tab); $i++)
	{
		$parsed_json = json_decode(my_get_json("repos/$tab[$i]/stats/contributors?access_token=a6f162fe9dd5745cfaa1e387321b3ce59ede3a27"),true);
		for($j = 0; $j < count($parsed_json); $j++)
		{
			for($k = 0; $k < count($parsed_json[$j]['weeks']); $k++)
			{
				$date = $parsed_json[$j]['weeks'][$k]['w'];
				if (isset($weeks[$date]))
				{
					$weeks[$date]['a'] = $weeks[$date]['a'] + $parsed_json[$j]['weeks'][$k]['a'];
					$weeks[$date]['d'] = $weeks[$date]['d'] + $parsed_json[$j]['weeks'][$k]['d'];
					$weeks[$date]['c'] = $weeks[$date]['c'] + $parsed_json[$j]['weeks'][$k]['c'];
				}
				else
				{
					$weeks[$date]['a'] = $parsed_json[$j]['weeks'][$k]['a'];
					$weeks[$date]['d'] = $parsed_json[$j]['weeks'][$k]['d'];
					$weeks[$date]['c'] = $parsed_json[$j]['weeks'][$k]['c'];
				}
			}
			$first = false;
		}
	}
	return($weeks);
}
function get_code_frequency($tab)
{
	$weeks = array();
	for ($i = 0; $i < count($tab); $i++)
	{
		$parsed_json = json_decode(my_get_json("repos/$tab[$i]/stats/code_frequency?access_token=aa7de7b2dbde85ccba017cd41a271560f0c1b4b0"),true);
		for($j = 0; $j < count($parsed_json); $j++)
		{
			$date = $parsed_json[$j][0];
			if (isset($weeks[$date]))
			{
				$weeks[$date]['a'] = $weeks[$date]['a'] + $parsed_json[$j][1];
				$weeks[$date]['d'] = $weeks[$date]['d'] + $parsed_json[$j][2];
			}
			else
			{
				$weeks[$date]['a'] = $parsed_json[$j][1];
				$weeks[$date]['d'] = $parsed_json[$j][2];
			}
		}
	}
	return($weeks);
}
function get_commit_activity($tab)
{
	$length = count($tab);
	$i = 0;
	$tab_activity = null;
	while ($i < $length)
	{
		$parsed_json = json_decode(my_get_json("repos/$tab[$i]/stats/commit_activity?access_token=42af8d0539f5f5036ea8a7ebb6a8d3142fbe60cc"),true);
		if (!isset($tab_activity))
		{
			$tab_activity = $parsed_json;
			$i++;
			$parsed_json = json_decode(my_get_json("repos/$tab[$i]/stats/commit_activity?access_token=42af8d0539f5f5036ea8a7ebb6a8d3142fbe60cc"),true);
		}
		
		$k = 0;
		while (isset($parsed_json[$k]))
		{
			$j=0;
			while (isset($parsed_json[$k]['days'][$j]))
			{
				$tab_activity[$k]['days'][$j] = $parsed_json[$k]['days'][$j] + $tab_activity[$k]['days'][$j];
				$tab_activity[$k]['total'] = $tab_activity[$k]['total'] + $parsed_json[$k]['total'];
				$j++;
			}
			$k++;
		}
		$i++;
	}
	return ($tab_activity);
}
function get_punch_card($tab)
{
	$length = count($tab);
	$i = 0;
	$tab_punch = null;
	while ($i < $length)
	{
		$parsed_json = json_decode(my_get_json("repos/$tab[$i]/stats/punch_card?access_token=1bdd2c42224a359c5c36aba31dca949e97a7207d"),true);
		if (!isset($tab_punch))
		{
			$tab_punch = $parsed_json;
			$i++;
			$parsed_json = json_decode(my_get_json("repos/$tab[$i]/stats/punch_card?access_token=1bdd2c42224a359c5c36aba31dca949e97a7207d"),true);
		}
		
		$k = 0;
		while (isset($parsed_json[$k]))
		{
				$tab_punch[$k][2] = $tab_punch[$k][2] + $parsed_json[$k][2];
			$k++;
		}
		$i++;
	}
	return ($tab_punch);
}
function get_participation($tab)
{
	$length = count($tab);
	$i = 0;
	$tab_parti = null;
	while ($i < $length)
	{
		$parsed_json = json_decode(my_get_json("repos/$tab[$i]/stats/participation?access_token=ea0723fa28d6dde7ccfd7a9b46c457398ca8685a"),true);
		if (!isset($tab_parti))
		{
			$tab_parti = $parsed_json;
			$i++;
			$parsed_json = json_decode(my_get_json("repos/$tab[$i]/stats/participation?access_token=ea0723fa28d6dde7ccfd7a9b46c457398ca8685a"),true);
		}
			$j=0;
			while(isset($parsed_json['all'][$j]))
			{
				$tab_parti['all'][$j] = $parsed_json['all'][$j] +  $tab_parti['all'][$j];
				$j++;
			}
			$j = 0;
			while(isset($parsed_json['owner'][$j]))
			{
				$tab_parti['owner'][$j] = $parsed_json['owner'][$j] +  $tab_parti['owner'][$j];
				$j++;
			}
		$i++;
	}
	return ($tab_parti);
}
function get_info_by_organization($user) {
	//test if limit reach
	$parsed_json = json_decode(my_get_json("rate_limit?access_token=a6f162fe9dd5745cfaa1e387321b3ce59ede3a27"),true);
	if ($parsed_json['rate']['remaining'] != 0)
	{
		$repos_orgarnization = my_get_repo($user);
		//test
		//var_dump($repos_orgarnization[$i]['full_name']);
		get_nbr_contrib($repos_orgarnization);
		get_commit_activity($repos_orgarnization);
		get_code_frequency($repos_orgarnization);
		get_punch_card($repos_orgarnization);
		get_participation($repos_orgarnization);
	}
	else
	{
		echo "Une erreur est apparue (limite ou autres)<br/>";
	}
}
#modif
// get_info_by_organization("etnascc");
?>