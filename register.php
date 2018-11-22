<?php

foreach($_POST as $key => $value) {
	if(empty($_POST[$key])) {
		$error_message = "All Fields are required";
		break;
	}
}