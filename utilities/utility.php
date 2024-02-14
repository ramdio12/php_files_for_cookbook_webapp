<?php 

// this function is to make sure that the data entered are safe and cleaned

function sanitize($input){
	 $input = trim($input);
     $input = htmlspecialchars($input);
     $input = stripslashes($input);

        return $input;

}