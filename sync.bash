#!/bin/bash
storageDir="/var/www/BiktrixBins/storage/app/images"
serverStatus="$storageDir/server_status.txt"
remoteName="biktrix"
if [ `pgrep rclone | wc -w` -gt 0 ]; then exit; fi;
date >> $storageDir/sync_log.txt 2>&1
echo rclone move biktrix:1 $storageDir/1 -v >> $storageDir/sync_log.txt 2>&1
rclone move biktrix:1 $storageDir/1 -v >> $storageDir/sync_log.txt 2>&1
date > $serverStatus
df -h >> $serverStatus
free -h >> $serverStatus
tail -100 $storageDir/sync_log.txt > $storageDir/t.txt; mv $storageDir/t.txt $storageDir/sync_log.txt
rclone move biktrix:1 $storageDir/1 -n > $storageDir/to_sync.txt 2>&1
rclone copy $storageDir/server_status.txt biktrix:
rclone copy $storageDir/sync_log.txt biktrix:
rclone copy $storageDir/to_sync.txt biktrix: