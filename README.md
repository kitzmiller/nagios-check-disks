# A local Nagios check for disk utilization

This is a local nagios check written in php to check disk and inode utilization of linux machines. Features:

* Checks disk utilization and inode utilization
* Offers performance data
* Included template for pnp4nagios
* Sane defaults for warning and critical levels
* Sensical text output `OK: / 188.94/367.94 GiB (51.35%): /boot 32.44/235.32 MiB (13.78%)`

##Compatibility
* Tested on Debian 7 and 8.
* Does not work on OS X 10.10.

##Requirements
* php

##Defaults
Defaults to 90% utilization for warnings and 95% utilization for critical alerts.

##Usage
    ./check_disks.php [-w warnlevel] [-c critlevel] [--inodewarn iwarnlevel] [--inodecrit icritlevel]
    -w            Warning level for bytes used
    -c            Critical level for bytes used
    --inodewarn   inode usage warning level
    --inodecrit   inode usage critical level

If any of the above values are less than 100 then that number will be
interpreted as a percentage, otherwise it will be intrepreted as a raw value
