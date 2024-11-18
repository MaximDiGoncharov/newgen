<?php



$conn = new mysqli($localhost, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



function getGenerator(string $sql)
{
    global $conn;
    $res = $conn->query($sql);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}


function addGenerator(string $table, array $param, array $fileds, array $reqFields, array $intFields = [])
{
    global $conn;
    $vals = '';
    $keys = '';

    foreach ($param as $key => $val) {
        if (in_array($key, $fileds)) {
            $keys .= $key . ',';
            if (in_array($key, $intFields)) {
                $vals .= (int) $val . ',';
            } else {
                $vals .= '"' . db_safe($val) . '"' . ',';
            }
        }
    }

    if ($vals != '') {
        $vals = substr($vals, 0, -1);
        $keys = substr($keys, 0, -1);

        $query = 'INSERT INTO `' . $table . '`(' . $keys . ') VALUES(' . $vals . ')';
        // die($query);
        $conn->query($query);
        $res = $conn->affected_rows;
        return $res > 0 ? $conn->insert_id : 0;
    }
}


function db_safe(string|int $str, int $fl = DB_SAFE_NO_QUOTAS): string
{
    global $conn;

    if ($fl & DB_SAFE_QUOTAS) {
        $str = trim($str);
    }
    if ($str != 'NULL') {
        $str = $conn->real_escape_string($str);
    }
    if ($fl & DB_SAFE_QUOTAS) {
        if ($str != 'NULL') {
            $str = '\'' . $str . '\'';
        }

        if ($fl & DB_SAFE_COMMA_START) {
            $str = ',' . $str;
        }

        if ($fl & DB_SAFE_COMMA_END) {
            $str .= ',';
        }
    }

    return $str;
}
