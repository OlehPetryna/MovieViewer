#!/usr/bin/env bash

HELP="$(basename "$0") dbHost dbName dbUser dbUserPass"
HELP0="$(basename "$0") dbHost dbName dbUser"
if [[ "$#" -ne 4 ]] && ! ([[ "$#" -eq 3 ]] && [[ -z "$4" ]]); then
printf "passed wrong arguments, command should be : \\n./%s" "$HELP"
printf "\\nif user has empty password : \\n./%s" "$HELP0"
else
    php -f initdb.php "$1" "$2" "$3" "$4"
    php -S localhost:8000 index.php
fi