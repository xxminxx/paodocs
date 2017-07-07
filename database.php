<?php
	/* "paodms" --> database name
	   "root"   --> username
	   ""       --> password
	*/
	mysql_select_db("paodms", mysql_connect("localhost", "root", "")) or die(mysql_error());
?>