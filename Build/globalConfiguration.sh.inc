setVariable PROJECT_ROOT_PATH "${THIS_SCRIPT_ROOT_REAL_PATH}/.."

setVariable PHP_BINARY_PATH "/usr/bin/php"

setVariable PHP_CS_FIXER_SEARCH_PATHS "[
    \"${PROJECT_ROOT_PATH}\"
]"

setVariable PHP_CS_FIXER_CONFIGURATION_PATH "${THIS_SCRIPT_ROOT_REAL_PATH}/Scripts/.php_cs"
setVariable PHP_CS_FIXER_BINARY_PATH "/usr/local/bin/php-cs-fixer"
