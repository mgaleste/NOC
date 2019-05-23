<?php
$tables='adminlogs';

$rownum	=(isset($_GET['rownum'])) ? $_GET['rownum'] : 50; 
$test = new MyPagina;
$test->sql = "SELECT * FROM $tables ORDER BY datetime DESC";
$test->rows_on_page=$rownum;
$result = $test->get_page_result(); // result set
$num_rows = $test->get_page_num_rows(); // number of records in result set 
$nav_links = $test->navigation(" | ", " | "); // the navigation links (define a CSS class selector for the current link)
$nav_info = $test->page_info(); // information about the number of records on page ("to" is the text between the number)
$simple_nav_links = $test->back_forward_link(false); // the navigation with only the back and forward links, use true to use images
$total_recs = $test->get_total_rows(); // the total number of records


?>
