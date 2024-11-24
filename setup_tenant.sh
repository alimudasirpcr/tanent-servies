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
SOURCE_DIR="/home/main_folder"
DEST_DIR="/home/$TENANT_NAME"

# Logging paths
echo "Received tenant name: $TENANT_NAME" >> $LOG_FILE
echo "Source directory: $SOURCE_DIR" >> $LOG_FILE
echo "Destination directory: $DEST_DIR" >> $LOG_FILE

# Ensure permissions on the source directory (read and execute for the current user)
chmod -R u+rX "$SOURCE_DIR"
if [ $? -ne 0 ]; then
    echo "Error: Failed to set permissions on source directory: $SOURCE_DIR" >> $LOG_FILE
    exit 1
fi

# Ensure permissions on the destination directory (read, write, and execute for the current user)
chmod -R u+rwX "$DEST_DIR"
if [ $? -ne 0 ]; then
    echo "Error: Failed to set permissions on destination directory: $DEST_DIR" >> $LOG_FILE
    exit 1
fi


# Copy files
cp -r $SOURCE_DIR/* $DEST_DIR/
if [ $? -eq 0 ]; then
    echo "Files copied successfully." >> $LOG_FILE
else
    echo "Failed to copy files." >> $LOG_FILE
fi

echo "Script ended at $(date)" >> $LOG_FILE
