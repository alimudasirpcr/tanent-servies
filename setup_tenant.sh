#!/bin/bash

# Log output to a file
LOG_FILE="/home/sareehap/public_html/setup_tenant_log.txt"
echo "Script started at $(date)" >> $LOG_FILE

# Check if tenant name is provided
if [ -z "$1" ]; then
    echo "Tenant name is missing." >> $LOG_FILE
    exit 1
fi

TENANT_NAME=$1
SOURCE_DIR="/home/mainsar/public_html"
DEST_DIR="/home/$TENANT_NAME"."/public_html"

# Logging paths
echo "Received tenant name: $TENANT_NAME" >> $LOG_FILE
echo "Source directory: $SOURCE_DIR" >> $LOG_FILE
echo "Destination directory: $DEST_DIR" >> $LOG_FILE



# Copy files
cp -r $SOURCE_DIR/* $DEST_DIR/
if [ $? -eq 0 ]; then
    echo "Files copied successfully." >> $LOG_FILE
else
    echo "Failed to copy files." >> $LOG_FILE
fi

echo "Script ended at $(date)" >> $LOG_FILE
