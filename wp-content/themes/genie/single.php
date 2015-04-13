<?php

get_header();

?>


<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54fe540e443baec1" async="async"></script>

<?php

/* Global Variables for Page Count */ 

global $page, $pages, $numpages;


/* User Agent */
$userAgents = new Mobile_Detect();

if ($userAgents->isMobile()) {
	include 'singledesktop.php';
}
else {
	include 'singlemobile.php';
}
