<?php
echo "<ul>";
foreach($data as $key => $value) 
{
    $value['title'] = str_replace(" ", "-", $value['title']);
    $value['category'] = str_replace(" ", "-", $value['category']);
    echo "
    <li><a href='Blog/Category/{$value['category']}/{$value['title']}'>{$value['title']}</a></li>
    ";
}
echo "</ul>";
?>