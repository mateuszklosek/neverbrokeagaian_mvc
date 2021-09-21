<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
		//\App\Mail::send('klosekmateusz@gmail.com' , 'Test' , 'This is a test' , '<h1>This is a test </h1>');
		
		if (! Auth::getUser()) {
			
				View::renderTemplate('Home/index.html');
			} else {
			View::renderTemplate('Menu/index.html'); 
			}		
        
    }
}
