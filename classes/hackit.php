<?php
/**
 * Hackit class
 * which should be implemented by all hackits
 *
 * @author Christian Haschek
*/
 
interface Hackit
{
	public function getDescription();

	public function getName();
	
	// tags,comma,seperated
	public function getTags();

	// do some preparations like set session variables or whatever
	public function prepare();

	//Should contain the algorithms
	//used for checking if a level is solved
	//returns true or false
	public function isSolved();

	//Everything that is returned will
	//be displayed as level
	public function render();
}