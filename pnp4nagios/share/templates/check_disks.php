<?php
/*
	TEMPLATE check_disks
	RRDFILE /usr/local/pnp4nagios/var/perfdata/glaucus.hampshire.edu/disks.rrd
	RRD_STORAGE_TYPE SINGLE
	RRD_HEARTBEAT 8640
	IS_MULTI 0
	DS 1-8
	NAME _ __max
	LABEL / /_max
	UNIT B
	ACT
	WARN
	WARN_MIN
	WARN_MAX
	WARN_RANGE_TYPE
	CRIT
	CRIT_MIN
	CRIT_MAX
	CRIT_RANGE_TYPE
	MIN
	MAX
*/

for($i = 0; $i < sizeof($this->DS) - 3; $i += 4) {
	$ds = $this->DS[$i];
	$dsmax = $this->DS[$i + 1];
	$ids = $this->DS[$i + 2];
	$idsmax = $this->DS[$i + 3];

	$ds_name[$i / 2] = "Disk space";
	$opt[$i / 2]  = "--vertical-label 'Bytes' -b 1024 -l 0 --title \"" . $this->MACRO["DISP_HOSTNAME"] . "/" . $this->MACRO["DISP_SERVICEDESC"] . ": " . $ds["LABEL"] . "\" ";
	$def[$i / 2]  = "DEF:used=" . $ds["RRDFILE"] . ":" . $ds["DS"] . ":AVERAGE ";
	$def[$i / 2] .= "AREA:used#CC0000:\"" . str_pad($ds["LABEL"] . " used space", 15, " ") . "\" ";
	$def[$i / 2] .= "GPRINT:used:MIN:\"Min\: %7.2lf %s" . $ds["UNIT"] . "\" ";
	$def[$i / 2] .= "GPRINT:used:AVERAGE:\"Average\: %7.2lf %s" . $ds["UNIT"] . "\" ";
	$def[$i / 2] .= "GPRINT:used:MAX:\"Max\: %7.2lf %s" . $ds["UNIT"] . "\" ";
	$def[$i / 2] .= "GPRINT:used:LAST:\"Last\: %7.2lf %s" . $ds["UNIT"] . "\\n\" ";
	$def[$i / 2] .= "DEF:total=" . $dsmax["RRDFILE"] . ":" . $dsmax["DS"] . ":AVERAGE ";
	$def[$i / 2] .= "CDEF:free=total,used,- ";
	$def[$i / 2] .= "AREA:free#256AEF:\"" . str_pad($ds["LABEL"] . " free space", 15, " ") . "\":STACK ";
	$def[$i / 2] .= "LINE1:used#000000:\"\" ";
	$def[$i / 2] .= "LINE1:total#000000:\"\" ";
	$def[$i / 2] .= "GPRINT:free:MIN:\"Min\: %7.2lf %s" . $dsmax["UNIT"] . "\" ";
	$def[$i / 2] .= "GPRINT:free:AVERAGE:\"Average\: %7.2lf %s" . $dsmax["UNIT"] . "\" ";
	$def[$i / 2] .= "GPRINT:free:MAX:\"Max\: %7.2lf %s" . $dsmax["UNIT"] . "\" ";
	$def[$i / 2] .= "GPRINT:free:LAST:\"Last\: %7.2lf %s" . $dsmax["UNIT"] . "\\n\" ";

	if($ds["WARN"]) {
		$def[$i / 2] .= "HRULE:" . $ds["WARN"] . "#FFFF00:\"Warning on  " . $ds["WARN"] . "\\n\" ";
	}
	if($ds["CRIT"]) {
		$def[$i / 2] .= "HRULE:" . $ds["CRIT"] . "#FF0000:\"Critical on  " . $ds["CRIT"] . "\\n\" ";
	}

	$def[$i / 2] .= "COMMENT:\\u ";
	$def[$i / 2] .= "COMMENT:\"Template\: " . $ds["TEMPLATE"] . "\\r\" ";
	//$def[$i / 2] .= "COMMENT:\"Command\: " . $this->MACRO["CHECK_COMMAND"] . "\\r\" ";
	/*
	foreach($ds as $key => $val) {
		$def[$i / 2] .= "COMMENT:\"" . $key . " => " . $val . "\\r\" ";
	}
	*/

	$ds_name[$i / 2 + 1] = "inodes";
	$opt[$i / 2 + 1]  = "--vertical-label 'inodes' -b 1024 -l 0 --title \"" . $this->MACRO["DISP_HOSTNAME"] . "/" . $this->MACRO["DISP_SERVICEDESC"] . ": " . $ds["LABEL"] . " inodes\" ";
	$def[$i / 2 + 1]  = "DEF:used=" . $ids["RRDFILE"] . ":" . $ids["DS"] . ":AVERAGE ";
	$def[$i / 2 + 1] .= "AREA:used#CC0000:\"" . str_pad($ds["LABEL"] . " used inodes", 15, " ") . "\" ";
	$def[$i / 2 + 1] .= "GPRINT:used:MIN:\"Min\: %7.2lf %s" . $ids["UNIT"] . "\" ";
	$def[$i / 2 + 1] .= "GPRINT:used:AVERAGE:\"Average\: %7.2lf %s" . $ids["UNIT"] . "\" ";
	$def[$i / 2 + 1] .= "GPRINT:used:MAX:\"Max\: %7.2lf %s" . $ids["UNIT"] . "\" ";
	$def[$i / 2 + 1] .= "GPRINT:used:LAST:\"Last\: %7.2lf %s" . $ids["UNIT"] . "\\n\" ";
	$def[$i / 2 + 1] .= "DEF:total=" . $idsmax["RRDFILE"] . ":" . $idsmax["DS"] . ":AVERAGE ";
	$def[$i / 2 + 1] .= "CDEF:free=total,used,- ";
	$def[$i / 2 + 1] .= "AREA:free#256AEF:\"" . str_pad($ds["LABEL"] . " free inodes", 15, " ") . "\":STACK ";
	$def[$i / 2 + 1] .= "LINE1:used#000000:\"\" ";
	$def[$i / 2 + 1] .= "LINE1:total#000000:\"\" ";
	$def[$i / 2 + 1] .= "GPRINT:free:MIN:\"Min\: %7.2lf %s" . $idsmax["UNIT"] . "\" ";
	$def[$i / 2 + 1] .= "GPRINT:free:AVERAGE:\"Average\: %7.2lf %s" . $idsmax["UNIT"] . "\" ";
	$def[$i / 2 + 1] .= "GPRINT:free:MAX:\"Max\: %7.2lf %s" . $idsmax["UNIT"] . "\" ";
	$def[$i / 2 + 1] .= "GPRINT:free:LAST:\"Last\: %7.2lf %s" . $idsmax["UNIT"] . "\\n\" ";

	if($ids["WARN"]) {
		$def[$i / 2 + 1] .= "HRULE:" . $ids["WARN"] . "#FFFF00:\"Warning on  " . $ids["WARN"] . "\\n\" ";
	}
	if($ids["CRIT"]) {
		$def[$i / 2 + 1] .= "HRULE:" . $ids["CRIT"] . "#FF0000:\"Critical on  " . $ids["CRIT"] . "\\n\" ";
	}

	$def[$i / 2 + 1] .= "COMMENT:\\u ";
	$def[$i / 2 + 1] .= "COMMENT:\"Template\: " . $ids["TEMPLATE"] . "\\r\" ";
	//$def[$i / 2 + 1] .= "COMMENT:\"Command\: " . $this->MACRO["CHECK_COMMAND"] . "\\r\" ";
}
?>
