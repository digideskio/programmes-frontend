#!/bin/bash

if [ -z "$1" ]; then
    echo "Usage: translate-alphabetiseAll.sh programmes"
    exit
fi

SCRIPTPATH=$( cd $(dirname $0) ; pwd -P )

TRPATH="${SCRIPTPATH}/../translations/${1}"

$SCRIPTPATH/translate-poFileSorter.php "${TRPATH}/${1}.pot" "${TRPATH}/${1}.pot"
$SCRIPTPATH/translate-updateFromTemplate.sh "${1}"
