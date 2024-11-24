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
SOURCE_DIR="/home/mainsare/public_html"
DEST_DIR="/home/$TENANT_NAME/public_html"

# Logging paths
echo "Received tenant name: $TENANT_NAME" >> $LOG_FILE
echo "Source directory: $SOURCE_DIR" >> $LOG_FILE
echo "Destination directory: $DEST_DIR" >> $LOG_FILE

# Check if source directory exists
if [ ! -d "$SOURCE_DIR" ]; then
    echo "Error: Source directory does not exist: $SOURCE_DIR" >> $LOG_FILE
    exit 1
fi

# Create destination directory if it doesn't exist
if [ ! -d "$DEST_DIR" ]; then
    mkdir -p "$DEST_DIR"
    if [ $? -ne 0 ]; then
        echo "Error: Failed to create destination directory: $DEST_DIR" >> $LOG_FILE
        exit 1
    fi
    echo "Created destination directory: $DEST_DIR" >> $LOG_FILE
fi

# Copy files and log detailed errors
cp -r "$SOURCE_DIR/"* "$DEST_DIR/" 2>> $LOG_FILE
if [ $? -eq 0 ]; then
    echo "Files copied successfully." >> $LOG_FILE
else
    echo "Error: Failed to copy files from $SOURCE_DIR to $DEST_DIR. See error details above." >> $LOG_FILE
fi

echo "Script ended at $(date)" >> $LOG_FILE
