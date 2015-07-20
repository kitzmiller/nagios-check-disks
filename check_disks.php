#!/usr/bin/php
<?php
// ckitzmiller@hampshire.edu - 7/7/15

$opts = getopt("w:c:", array("inodewarn:", "inodecrit:"));
if(!isset($opts["w"])) { $opts["w"] = 90; }
if(!isset($opts["c"])) { $opts["c"] = 95; }
if(!isset($opts["inodewarn"])) { $opts["inodewarn"] = 90; }
if(!isset($opts["inodecrit"])) { $opts["inodecrit"] = 95; }


$units = array("B","KiB","MiB","GiB","TiB","PiB","EiB","ZiB","YiB");

function format_bytes($bytes, $newunit = "GiB") {
	global $units;
	$order = array_search($newunit, $units);
	if(!$order) { return false; } // unfound unit type
	for($i = 0; $i < $order; $i++) {
		$bytes /= 1024;
	}
	return number_format($bytes,2,".","");
}
	
$dfIgnore = "-x tmpfs -x devtmpfs -x rootfs";
$dfAwkLine = "awk '{ print $1 \",\" $2 \",\" $3 \",\" $4 \",\" $5 \",\" $6 }'";

$lastline = exec("df -l $dfIgnore | $dfAwkLine", $output, $retval);
$lastline = exec("df -l $dfIgnore -i | $dfAwkLine", $ioutput, $retval);

$text = array();
$perf = array();
$retcode = array(0);
for($i = 1; $i < sizeof($output); $i++) {
	$splode = explode(",", $output[$i]);
	$isplode = explode(",", $ioutput[$i]);
	$partition = $splode[5];
	$itotal = $isplode[1] * 1024;
	$iused = $isplode[2] * 1024;
	$total = $splode[1] * 1024;
	$used = $splode[2] * 1024;

	$unittest = $total;
	for($j = 0; $unittest >= 1024; $j++) {
		$unittest /= 1024;
	}
	$unit = $units[$j];

	$text[] = $partition . " " . format_bytes($used, $unit) . "/" . format_bytes($total, $unit) . " " . $unit . " (" . number_format($used/$total*100,2,".","") . "%)";

	if($opts["w"] < 100) {
		$warnval = number_format($total * $opts["w"] / 100, 0, ".", "");
		if(($used/$total*100) >= $opts["w"]) {
			$retcode[] = 1;
		}
	} else {
		$warnval = $opts["w"];
		if($used >= $opts["w"]) {
			$retcode[] = 1;
		}
	}
	if($opts["c"] < 100) {
		$critval = number_format($total * $opts["c"] / 100, 0, ".", "");
		if(($used/$total*100) >= $opts["c"]) {
			$retcode[] = 2;
		}
	} else {
		$critval = $opts["c"];
		if($used >= $opts["c"]) {
			$retcode[] = 2;
		}
	}

	$itext = false;
	if($opts["inodewarn"] < 100) {
		$iwarnval = number_format($itotal * $opts["inodewarn"] / 100, 0, ".", "");
		if(($iused/$itotal*100) >= $opts["inodewarn"]) {
			$retcode = 1;
			$itext = true;
		}
	} else {
		$iwarnval = $opts["inodewarn"];
		if($iused >= $opts["inodewarn"]) {
			$retcode = 1;
			$itext = true;
		}
	}
	if($opts["inodecrit"] < 100) {
		$icritval = number_format($itotal * $opts["inodecrit"] / 100, 0, ".", "");
		if(($iused/$itotal*100) >= $opts["inodecrit"]) {
			$retcode = 2;
			$itext = true;
		}
	} else {
		$icritval = $opts["inodecrit"];
		if($iused >= $opts["inodecrit"]) {
			$retcode = 1;
			$itext = true;
		}
	}

	if($itext) {
		$text[] = $partition . " inode usage at " . number_format($iused/$itotal*100,2,".","") . "%";
	}

	$perf[] = $partition . "=" . $used . "B;$warnval;$critval " . $partition . "_max=" . $total . "B";
	$perf[] = $partition . "_inode=" . $iused . ";$iwarnval;$icritval " . $partition . "_inode_max=" . $itotal;
}
$worst = max($retcode);
switch($worst) {
	case 2: echo("Critical: "); break;
	case 1: echo("Warning: "); break;
	case 0: echo("OK: "); break;
	default: echo("Unknown: ");
}
echo(implode("; ", $text) . "|" . implode(" ", $perf) . "\n");
exit($worst);
