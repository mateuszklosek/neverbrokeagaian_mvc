<?php

namespace App\Controllers;

use \App\Models\User;

/**
 * Account controller
 *
 * PHP version 7.0
 */
class Account extends \Core\Controller
{

    /**
     * Validate if email is avalible (AJAX) for a new signup
     *
     * @return void
     */
	 public function validateEmail()
	 {
		 $is_valid = ! User::emailExists($_GET['email']);
		 
		 header('Content-Type: application/json');
		 echo json_encode($is_valid);
	 }
	 
	 	public function validatePasswordAction()
    {	
		if(isset($_GET['oldPassword'])) {
        $is_valid = User::validateOldPassword($_GET['oldPassword'],$_GET['user_id']);
        
        header('Content-Type: application/json');
        echo json_encode($is_valid);
		} else {
			$this->redirect('/settings/index');
		}
    }	
	
	    public function validateEmailAction()
    {
		if(isset($_GET['email'])) {
        $is_valid = ! User::emailExists($_GET['email'],$_GET['ignore_id'] ?? null);
        
        header('Content-Type: application/json');
        echo json_encode($is_valid);
		} else {
			$this->redirect('/settings/index');
		}
    }   

}
