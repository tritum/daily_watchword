# *************************************************************************
# @version 0.0.2
# @author Ralf Zimmermann <ralf.zimmermann@tritum.de>

# ***************
# Initialisierung

THIS_SCRIPT_ROOT_REAL_PATH="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

_GLOBAL_CONFIGURATION_PATH="${THIS_SCRIPT_ROOT_REAL_PATH}/globalConfiguration.sh.inc"
_GLOBAL_FUNCTIONS_PATH="${THIS_SCRIPT_ROOT_REAL_PATH}/globalFunctions.sh.inc"

# ************************
# globale Funktionen laden

if [[ ! -f "${_GLOBAL_FUNCTIONS_PATH}" ]];
then
    echo "Global functions file "${_GLOBAL_FUNCTIONS_PATH}" does not exist";
    exit 1;
fi;

. "${_GLOBAL_FUNCTIONS_PATH}"

# ***********************
# globale Variablen laden

if [[ ! -f "${_GLOBAL_CONFIGURATION_PATH}" ]];
then
    echo "Configuration file "${_GLOBAL_CONFIGURATION_PATH}" does not exist";
    exit 1;
fi;

. "${_GLOBAL_CONFIGURATION_PATH}"

INITIALIZED=1

# ***********************
# lösche lokale Variablen

unset _GLOBAL_CONFIGURATION_PATH \
      _GLOBAL_FUNCTIONS_PATH
