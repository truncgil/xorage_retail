<?php 
 header('content-type: application/json; charset=utf-8');
if(getisset("id")) {
$data = db("planning-board")
->where("type",get("type"))
->where("id",get("id"))
->orderBy("id","DESC")->first();	
} else {
$data = db("planning-board")
->where("type",get("type"))
->orderBy("id","DESC")->first();	
}
$array = json_decode($data->json,true);
$downsetup = array();
/*
(
	[html] => <div class="job fill j20524650674" draggable="true" ondragstart="drag(event)" jid="20524650674" style="background: rgba(226, 162, 162, 0.3); color: black;" title="" data-original-title="0.5/ Part 1">1,1E+11 / 2GG16 OZK30 \ 2GG16 OZK30 / 2 / P-SI-H2 / 23.08.2016<div class="delete"></div></div>
	[jid] => 20524650674
	[workstation] => P-SI-H2
	[date] => 2020-01-22
	[id] => p-si-h2-2020-01-22-f
)
*/
switch(get("type")) {
	case "silikapr-workstations": $db = "silikapr"; $col = "SilikaPR-Workstation"; break;
	case "schamottepr-workstations": $db = "schamottepr"; $col = "SchamottePR-Workstation"; break;
	case "configration-handformerei": $db = "handformerei"; $col = "Handformerei-Workstation"; break;
	case "configration-endbearbeitung": $db = "endbearbeitung"; $col = "Endbearbeitung-Workstation"; break;
}
$sorgu = db($db)->get();
$vardiya = db("contents")->where("kid","configuration-shift-hours")->get();

foreach($sorgu AS $s) {
	$j = json_decode($s->json,true);
	$workstation = @$j[$col];
	$diger ="workstation='$workstation' json='{$s->json}'";
	if($j['setupTime']!="") {
		$title = "{$j['setupTime']} - {$j['setupTimeTo']}";
		$rawdate1 = $j['setupTime'];
		$rawdate2 = $j['setupTimeTo'];
		$date = date("Y-m-d",strtotime($j['setupTime']));
		$saat = date("H:i",strtotime($j['setupTime']));
		$label = __("Setup Time");
		$html = '<div class="setup" date="'.$date.'" title="'.$title.'" '.$diger.'>'.$label.'</div>';
		
		
		
		
		
		
	}
	if($j['downTime']!="") {
		$title = "{$j['downTime']} - {$j['downTimeTo']}";
		$rawdate1 = ($j['downTime']);
		$rawdate2 = ($j['downTimeTo']);
		$date = date("Y-m-d",strtotime($j['downTime']));
		$label = __("Down Time");
		$saat = date("H:i",strtotime($j['downTime']));
		$now = strtotime($saat);
		$html = '<div class="down" date="'.$date.'" title="'.$title.'" '.$diger.'>'.$label.'</div>';
		
		
		
		
		
	}
	//print_r($j);
	$vardiyane="";
	$bas_ay = date("m",strtotime($rawdate1));
	$son_ay = date("m",strtotime($rawdate2));
	$bas_gun = date("m",strtotime($rawdate1));
	$son_gun = date("m",strtotime($rawdate2));
	$gun_farki = (strtotime($rawdate2)-strtotime($rawdate1))/86400;
	//son güne kadar her güne ait vardiyaları döngü yap
	/*
	$var = array();
	for($k=0;$k<=$gun_farki;$k++) {
		$date = date("Y-m-d",strtotime($rawdate1."+$k days"));
		$saat = date("H:i",strtotime($rawdate1."+$k days"));
		for($a=0;$a<=24;$a++) {
			foreach($vardiya AS $v) {
				$now = strtotime($saat."+$a hours");
			//	echo "$now ";
				$begintime = strtotime($v->time1);
				$endtime = strtotime($v->time2);
			///	echo $v->title[0];
				if($now >= $begintime && $now <= $endtime) {
					$vardiyane = $v->title[0];
					if($vardiyane=="S") {
						$vardiyane="M";
					}
				}
			}
			$id = str_slug("$workstation $date $vardiyane");
			if(!in_array($id,$var)) {
				array_push($var,$id);
				array_push($downsetup,array(
					"html" => $html,
					"jid" => rand(111,999),
					"workstation" => $workstation,
					"date" => $date,
					"id" => $id
				));
			}
		}
	}
	*/
	/*
	for($k=0;$k<=$gun_farki;$k++) {
		$dizi = explode(",","f,f2,m,m2,n,n2");
		$date = date("Y-m-d",strtotime($rawdate1."+$k days"));
		$saat = date("H:i",strtotime($rawdate1."+$k days"));
		echo "$date $saat / ";
		if($k==0) { // ilk saati hangi vardiyada?
			foreach($vardiya AS $v) {
				$now = strtotime($saat);
				$begintime = strtotime($v->time1);
				$endtime = strtotime($v->time2);
				if($now >= $begintime && $now <= $endtime) {
					$vardiyane = $v->title[0];
					if($vardiyane=="S") {
						$vardiyane="M";
					}
				}
			}
			$vardiyane = str_slug($vardiyane);
		}
		
	}
	*/
	
}
//print_r($downsetup); exit();
$new = array_merge($array,$downsetup);
$data = $data->json;
//echo $data;
echo json_encode($new);
 ?>