<?php
$req = glob('./lib/*.php');
foreach ($req as $r) {
    require_once $r;
}