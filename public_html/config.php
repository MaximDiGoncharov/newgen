<?php
$localhost = '';
$username = '';
$password = '';
$database = '';


define('DB_SAFE_NO_QUOTAS', 0);
define('DB_SAFE_QUOTAS', 1);
define('DB_SAFE_COMMA_START', 2);
define('DB_SAFE_QUOTAS_COMMA_START', DB_SAFE_QUOTAS | DB_SAFE_COMMA_START);
define('DB_SAFE_COMMA_END', 4);
define('DB_SAFE_QUOTAS_COMMA_END', DB_SAFE_QUOTAS | DB_SAFE_COMMA_END);
define('DB_SAFE_QUOTAS_COMMAS', DB_SAFE_QUOTAS_COMMA_START | DB_SAFE_QUOTAS_COMMA_END);
