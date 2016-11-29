<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* Below we will have a really small function set as we will only be using 1 function in this 
sanitize system. That will be an escape function as this will be a really important function when
outputting data that's been stored in a database. We can pick and choose whether we would like 
to sanitize data when it's going in or coming out, best practice would be to do both so it will
be sanitized going in and we can escape when it's coming out. The function below is just an escape 
function. In the function we will define a couple of options to make using the function more secure */

function escape($string){								// Define the function and pass a string into the function.
	return htmlentities($string, ENT_QUOTES, 'UTF-8');	// ENT_QUOTES will escape single and double quotes, we also define the character encoding or charset which will make 
}														// it more secure if we are dealing with encoding of a different type we can be reasonably assured by these entities,
														// there are more we can add but these are good for now.
?>														