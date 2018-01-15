#!/bin/bash
# *************************************************************************
# @version 0.0.1
# @author Ralf Zimmermann <ralf.zimmermann@tritum.de>
#
# install php cs fixer:
# composer create-project friendsofphp/php-cs-fixer
# sudo ln -s /path/to/php-cs-fixer/php-cs-fixer /usr/local/bin/php-cs-fixer
# *************************************************************************

# ***************
# Initialisierung

_THIS_SCRIPT_REAL_PATH="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

. "${_THIS_SCRIPT_REAL_PATH}/../globalInitializeScript.sh.inc"
if [[ -z "${INITIALIZED}" ]]; then echo "The script is not initialized"; exit 1; fi

_COUNTER=0
_DRYRUN=""

# **************************************
# Prüfe globale Variablen dieses Skripts

checkVariablesExist PHP_CS_FIXER_BINARY_PATH \
                    PHP_CS_FIXER_CONFIGURATION_PATH \
                    PHP_CS_FIXER_SEARCH_PATHS \
                    PROJECT_ROOT_PATH \
                    PHP_BINARY_PATH

# ***************************
# Prüfe lokale dieses Skripts

checkVariablesExist _COUNTER

# **************
# Programm start

if [ "$1" = "dryrun" ]
then
    _DRYRUN="--dry-run"
fi

_PHP_CS_FIXER_SEARCH_PATHS="$PHP_CS_FIXER_SEARCH_PATHS" ${PHP_BINARY_PATH} ${PHP_CS_FIXER_BINARY_PATH} --config="${PHP_CS_FIXER_CONFIGURATION_PATH}" fix $PROJECT_ROOT_PATH --path-mode=intersection -v $_DRYRUN

if [ "$?" -gt "0" ]
then
    _COUNTER=$((_COUNTER+1))
fi

if [ ${_COUNTER} -gt 0 ] ; then
    echo "$_COUNTER number of files are not CGL clean. Check $0 to find out what is going wrong."
    exit 1
fi

# **************************************
# lösche lokale Variablen und Funktionen

unset _THIS_SCRIPT_REAL_PATH \
      _PHP_CS_FIXER_SEARCH_PATHS \
      _DRYRUN
