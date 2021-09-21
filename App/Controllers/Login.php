<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

/**
 * Login controller
 *
 * PHP version 7.0
 */
class Login extends \Core\Controller
{

    /**
     * Show the login page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Login/new.html');
    }
	
	 /**
     * Log in a user
     *
     * @return void
     */
    public function createAction()
    {
		$user = User::authenticate($_POST['email'], $_POST['password']);
		
		$remember_me = isset($_POST['remember_me']);
		
		if ($user) {
			
			Auth::login($user, $remember_me);
			
			//Remember the login here
			//...
			
			
			Flash::addMessage('Logowanie zakończone sukcesem.');
			
			$this->redirect(Auth::getReturnToPage());
			
		} else {
		
		Flash::addMessage('Logowanie nieudane, proszę spróbuj ponownie.', Flash::WARNING);
		
		View::renderTemplate('Login/new.html' , [
			'email' => $_POST['email'],
			'remember_me' => $remember_me
			]);
		}
	}
	
	 /**
     * Log out a  user
     *
     * @return void
     */
      public function destroyAction()
		{
			Auth::logout();
			
			$this->redirect('/login/show-logout-message');
		}
		
		/**
     * Show logout message and redirected to the homepage.
     *
     * @return void
     */
      public function showLogoutMessageAction()
		{	
			Flash::addMessage('Wylogowanie powiodło się.');

			$this->redirect('/');
		}
	
}
