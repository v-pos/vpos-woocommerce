<?php

interface Repository
{
    public function create_table();
    public function get($id);
    // To upgrade table see wordpress documentation: https://codex.wordpress.org/Creating_Tables_with_Plugins
    public function update($id, $model);
    public function delete($id);
    public function insert($model);
}

?>
