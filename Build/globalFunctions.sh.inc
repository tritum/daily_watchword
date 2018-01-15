# *************************************************************************
# @version 0.0.2
# @author Ralf Zimmermann <ralf.zimmermann@tritum.de>

function checkVariablesExist()
{
    for _VARIABLE_NAME in "$@"
    do
        if [[ -z "${!_VARIABLE_NAME}" ]];
        then
            echo "The variable ${_VARIABLE_NAME} is required but not set"
            exit 1;
        fi
    done
    unset _VARIABLE_NAME
}

function setVariable()
{
    if [[ -z "${!1}" ]];
    then
        eval $1=\$2
    fi
}