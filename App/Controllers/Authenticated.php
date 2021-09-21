<?php

namespace App\Controllers;


/**
 * Autheniticated base controller
 *
 * PHP version 7.0
 */
abstract class Authenticated extends \Core\Controller
{

	/**
     * Require the user to be authenticated before giving acces to all methods in the controller
     *
     * @return void
     */
   protected function before()
    {
        $this->requireLogin();
    }

	
}