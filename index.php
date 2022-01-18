<?php

echo "<h1>To działa !!!</h1>";

$test = ['01', '02', '03', '04'];

function dump($data)
{
    echo '<div style="
    display: inline-block;
    padding: 10px;
    border: 1px solid black;
    background-color: lightgray;
    ">
    <pre>';
    print_r($data);
    echo '</pre></div>';
}

dump($test);
