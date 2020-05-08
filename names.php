<?php
//PHP array containing forenames.
$names = array_map('str_getcsv', file('names/Human_Names_-_Fantasy_Male.csv'));
$surnames = array_map('str_getcsv', file('names/Human_Names_-_Fantasy_Surname.csv'));
 
//Generate a random forename.
$random_name = $names[mt_rand(0, sizeof($names) - 1)];
 
//Generate a random surname.
$random_surname = $surnames[mt_rand(0, sizeof($surnames) - 1)];
 
//Combine them together and print out the result.
echo $random_name[1] . ' ' . $random_surname[1];
/*
echo "<hr>";

$vowels = array("a", "e", "o", "u");
$consonants = array("b", "c", "d", "v", "g", "t");

function randVowel()
{
	global $vowels;
	return $vowels[array_rand($vowels, 1)];
}

function randConsonant()
{
	global $consonants;
	return $consonants[array_rand($consonants, 1)];
}

echo ucfirst("" . randConsonant() . "" . randVowel() . "" . "" . randConsonant() . "" . randVowel() . "" . randVowel() . "");
*/

?>