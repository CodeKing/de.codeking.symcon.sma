#!/bin/bash

# config
DIR=$(cd `dirname $0` && pwd)
REPO=$1

if [ -z $REPO ]; then
    REPO='de.codeking.symcon.*'
fi

# colors
RED='\033[0;31m'
GREEN='\033[0;32m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

# prompt
echo ""
while true; do
    read -p "Push to github after update? [y|n] " yn
    case $yn in
        [Yy]* ) PUSH=1; break;;
        "") PUSH=1; break;;
        [Nn]* ) PUSH=0; break;;
        * ) echo "Please answer yes or no.";;
    esac
done

# update git submodules
echo ""
find ../ -type d -iname "${REPO}" -print0 | while IFS= read -r -d $'\0' folder; do
    project=$(echo $folder | cut -d'/' -f 2)
    echo -e -n "${CYAN}updating${NC} ${BOLD}${project}${NC}... "

    cd $folder
    git submodule update --remote --force --quiet &> /dev/null

    if [ $PUSH -eq 1 ]; then
        git commit -a -m "helpers updated" &> /dev/null
        git remote add origin https://github.com/CodeKing/${project}.git &> /dev/null
        git push -u origin master &> /dev/null
    fi

    echo -e "${GREEN}done!${NC}"
    cd $DIR
done

