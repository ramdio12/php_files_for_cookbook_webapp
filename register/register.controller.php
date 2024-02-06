<?php

/*

	this file responsible for data checking
	if returned true, then there is indeed an error, create_user function will not be executed, we will show the error to the user
	if returned false meaning there are no errors and create_user function will execute

*/
declare(strict_types=1);

function isEmpty(string $name, string $username, string $email, string $password)
{

	if (empty($name) || empty($username) || empty($email) || empty($password)) {
		return true;
	} else {
		return false;
	}
}

function isShort(string $name, string $username, string $email, string $password)
{

	if (strlen($name)<2 || strlen($username)<2 || strlen($email)<2 || strlen($password)<2) {
		return true;
	} else {
		return false;
	}
}

function invalidEmail(string $email)
{
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
		return false;
	}
}

function passwordError(string $password)
{
	if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[!@#$%^&*()_+{}|":<>?~\-=\[\];\',.\/]/', $password)) {
		return true;
	} else {
		return false;
	}
}


function isEmailTaken(object $conn, string $email)
{
	if (getEmail($conn, $email)) {
		return true;
	} else {
		return false;
	}
}

function isUsernameTaken(object $conn, string $username)
{
	if (getUsername($conn, $username)) {
		return true;
	} else {
		return false;
	}
}
function create_user(object $conn, string $name, string $username, string $email, string $password)
{
	set_user($conn, $name, $username, $email, $password);
}
