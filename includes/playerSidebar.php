<?php
$links = array();
$links['page1.html'] = 'Page 1';
$links['page2.html'] = 'Page 2';
$links['page3.html'] = 'Page 3';

$curr_page = basename($_SERVER['PHP_SELF']);

foreach($links as $k=>$v)
{
    echo '<a href="'.$k.'"';
    if($curr_page === $k)
    {
        echo ' class="active_link"';
    }
    echo '>'.$v.'</a>';
}
?>