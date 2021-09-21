<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Auth;
use \App\Dates;
use \App\Flash;

class Expense extends Authenticated
{
    public function indexAction()
    {	
        View::renderTemplate('Expense/index.html', [
		'todaysDate' => Dates::getTodaysDate(),
		'userExpenses' => Expenses::getUserExpenseCategories(),
		'paymentMethods' => Expenses::getUserPaymentMethods(),
		'lastDate' => Dates::getLastDayOfNextMonth()
		]);
    }

    public function createAction()
    {
		if(isset($_POST['amount'])) {
			$expense = new Expenses($_POST);
			

			if ($expense->save()) {

				Flash::addMessage('Sukces! Wydatek został dodany.');

				$this->redirect('/expense/index');

			} else {
					
				View::renderTemplate('Expense/index.html', [
					'expense' => $expense,
					'todaysDate' => Dates::getTodaysDate(),
					'userExpenses' => Expenses::getUserExpenseCategories(),
					'paymentMethods' => Expenses::getUserPaymentMethods(),
					'lastDate' => Dates::getLastDayOfNextMonth()
				]);
				
			} 	
		} else {
			$this->redirect('/expense/index');
		}
    }
	
	public function  checkLimitAction()
	{
		 if(isset($_POST["expenseCategory"]))  
		 {  $expense = new Expenses($_POST);
			$expense->showExpenseLimit();
		 } else {
			$this->redirect('/expense/index');
		 }			 
	}	
	
	public function  getFinalValueAction()
	{
		 if(isset($_POST["amount"]))  
		 { 	
			$expense = new Expenses($_POST);
			$value = $expense->getFinalValue();
		 } else {
			$this->redirect('/expense/index');
		 }
	}
}
