<?php


class db_object
{

    private string $table;
    private array $all;
    private array $main;
    private array $int_convert;


    function __construct(
        string $table,
        array $all,
        array $main = null,
        array $int_convert = []
    ) {
        $this->table = $table;
        $this->all = $all;
        if (is_null($main)) {
            $main = &$all;
        }
        $this->main = $main;
        $this->int_convert = $int_convert;
    }

    public function _add($param)
    {
        // ++
        return addGenerator($this->table, $param, $this->all, $this->main, $this->int_convert);
    }
}
