#!/usr/bin/bash

# 사용법 안내
if [ "$#" -ne 2 ]; then
    echo "Usage: $0 <source_directory> <target_directory>"
    exit 1
fi

SOURCE_DIR="$1"
TARGET_DIR="$2"

# 소스 디렉토리가 존재하지 않으면 종료
if [ ! -d "$SOURCE_DIR" ]; then
    echo "Error: Source directory '$SOURCE_DIR' does not exist."
fi

# 대상 디렉토리가 존재하지 않으면 종료
if [ ! -d "$TARGET_DIR" ]; then
    echo "Error: Target directory '$TARGET_DIR' does not exist."
fi

for file in "$SOURCE_DIR"/{{서비스코드}}-BCB-*; do
    if [ -f "$file" ]; then
        mv "$file" "$TARGET_DIR/HDD-${file#\{\{서비스코드\}\}-}"
        echo "$TARGET_DIR/$file"
    fi
done
