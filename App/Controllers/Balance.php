<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;
use \App\Models\Incomes;
use \App\Models\Expenses;
use \App\Auth;
use \App\Dates;
use \App\Flash;

class Balance extends Authenticated
{

    public function indexAction()
    {				
			View::renderTemplate('Balance/index.html', [
			'incomeCategoriesAmount' => Balances::getIncomeCategoriesAmount(),	
			'expenseCategoriesAmount' => Balances::getExpenseCategoriesAmount(),	
			'incomeCategoriesInDetail' => Balances::getIncomeCategoriesAmuntInDetail(),	
			'expenseCategoriesInDetail' => Balances::getExpenseCategoriesAmuntInDetail(),
			'todaysDate' => Dates::getTodaysDate(),
			'yesterdaysDate' => Dates::getYesterdaysDate(),
			'firstDate' => Balances::getFirstEchoDate(),
			'secondDate' => Balances::getSecondEchoDate(),
			'userIncomes' => Incomes::getUserIncomeCategories(),
			'userExpenses' => Expenses::getUserExpenseCategories(),
			'paymentMethods' => Expenses::getUserPaymentMethods(),
			'lastDate' => Dates::getLastDayOfNextMonth(),
			'balance' => Balances::getBalance(),
			'balanceNote' => Balances::balanceNote()
			]);
	
    }
	
	public function updateIncome() 
	{
		if(isset($_POST['amount'])) {
			
			$income = new Incomes($_POST);

			if ($income->update()) {

				Flash::addMessage('Przychód został zedytowany.');

				$this->redirect('/balance/index');

			} else {
					
				Flash::addMessage('Nie udało się edytować przychodu.',Flash::DANGER);	
					
				View::renderTemplate('Balance/index.html', [
				'incomeCategoriesAmount' => Balances::getIncomeCategoriesAmount(),	
				'expenseCategoriesAmount' => Balances::getExpenseCategoriesAmount(),	
				'incomeCategoriesInDetail' => Balances::getIncomeCategoriesAmuntInDetail(),	
				'expenseCategoriesInDetail' => Balances::getExpenseCategoriesAmuntInDetail(),
				'todaysDate' => Dates::getTodaysDate(),
				'yesterdaysDate' => Dates::getYesterdaysDate(),
				'firstDate' => Balances::getFirstEchoDate(),
				'secondDate' => Balances::getSecondEchoDate(),
				'userIncomes' => Incomes::getUserIncomeCategories(),
				'userExpenses' => Expenses::getUserExpenseCategories(),
				'paymentMethods' => Expenses::getUserPaymentMethods(),
				'income' => $income				
				]);
				
			} 	
		} else {
			$this->redirect('/balance/index');
		}
		
	}
	
	public function deleteIncome() 
	{	
		if(isset($_POST['amount'])) {
			
			$income = new Incomes($_POST);

			$income->delete();

			Flash::addMessage('Przychód został usunięty.');

			$this->redirect('/balance/index');
			
		} else {
			$this->redirect('/balance/index');
		}

	}
	
	public function updateExpense() 
	{
		if(isset($_POST['amount'])) {
			
			$expense = new Expenses($_POST);

			if ($expense->update()) {

				Flash::addMessage('Wydatek został zedytowany.');

				$this->redirect('/balance/index');

			} else {
					
				Flash::addMessage('Nie udało się edytować wydatku.',Flash::DANGER);	
					
				View::renderTemplate('Balance/index.html', [
				'incomeCategoriesAmount' => Balances::getIncomeCategoriesAmount(),	
				'expenseCategoriesAmount' => Balances::getExpenseCategoriesAmount(),	
				'incomeCategoriesInDetail' => Balances::getIncomeCategoriesAmuntInDetail(),	
				'expenseCategoriesInDetail' => Balances::getExpenseCategoriesAmuntInDetail(),
				'todaysDate' => Dates::getTodaysDate(),
				'yesterdaysDate' => Dates::getYesterdaysDate(),
				'firstDate' => Balances::getFirstEchoDate(),
				'secondDate' => Balances::getSecondEchoDate(),
				'userIncomes' => Incomes::getUserIncomeCategories(),
				'userExpenses' => Expenses::getUserExpenseCategories(),
				'paymentMethods' => Expenses::getUserPaymentMethods(),
				'expense' => $expense				
				]);
				
			} 	
		} else {
			$this->redirect('/balance/index');
		}
		
	}
	
	public function deleteExpense() 
	{	
		if(isset($_POST['amount'])) {
			
			$expense = new Expenses($_POST);

			$expense->delete();

			Flash::addMessage('Wydatek został usunięty.');

			$this->redirect('/balance/index');
			
		} else {
			$this->redirect('/balance/index');
		}

	}
	
}


