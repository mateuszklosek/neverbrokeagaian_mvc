<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Incomes;
use \App\Models\Expenses;
use \App\Models\User;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Settings extends Authenticated
{
	protected function before()
	{	
		parent::before();
		$this->user = Auth::getUser();
	}
	
    public function indexAction()
    {	
			View::renderTemplate('Settings/index.html', [
			'userIncomes' => Incomes::getUserIncomeCategories(),
			'userExpenses' => Expenses::getUserExpenseCategories(),
			'paymentMethods' => Expenses::getUserPaymentMethods(),
			'user' => $this->user
			]);
	}
	
	public function updateProfileData()
	{
		if(isset($_POST['name'])) {
			
			$user = new User($_POST);

			if ($user->updateProfile()) {

				Flash::addMessage('Profil został zedytowany.');

				$this->redirect('/settings/index');

			} else {
					
				Flash::addMessage('Podany e-mail jest już zajęty lub jest w niepoprawnym formacie.',Flash::WARNING);	
					
				View::renderTemplate('Settings/index.html', [
				'userIncomes' => Incomes::getUserIncomeCategories(),
				'userExpenses' => Expenses::getUserExpenseCategories(),
				'paymentMethods' => Expenses::getUserPaymentMethods(),
				'user' => $user
				]);
					
			} 	
		} else {
			$this->redirect('/settings/index');
		}
	}	
	
	public function deleteAccount()
	{
		if(isset($_POST['deleteAccount'])) {
			
		$user = new User();
		$user->deleteAccount();

		Auth::logout();
		
		$this->redirect('/login/new');
		} else {
			$this->redirect('/settings/index');
		}
	
	}	
	
	public function resetAccountTransactions()
	{
		if(isset($_POST['resetAccount'])) {
			
		Incomes::deleteAllUserIncomes();
		Expenses::deleteAllUserExpenses();
		
		Flash::addMessage('Wszystkie Twoje transakcje zostały usunięte.');

		$this->redirect('/settings/index');
		} else {
			$this->redirect('/settings/index');
		}
	
	}	
	
	public function changePassword()
	{
		if(isset($_POST['password'])) {
			
			$user = new User($_POST);

			if ($user->changeUserPassword()) {

				Flash::addMessage('Twoje hasło zostało zmienione.');

				$this->redirect('/settings/index');

			} else {
					
				Flash::addMessage('Nie udało się zmienić hasła.',Flash::WARNING);	
					
				$this->redirect('/settings/index');	
			} 	
		} else {
			$this->redirect('/settings/index');
		}
	
	}
	
	public function updateIncomeCategory() 
	{
		if(isset($_POST['incomeCategory'])) {
			
			$income = new Incomes($_POST);

			if ($income->updateCategory()) {

				Flash::addMessage('Kategoria przychodów została zedytowana.');

				$this->redirect('/settings/index');

			} else {
					
				Flash::addMessage('Podana kategoria już istnieje.',Flash::WARNING);	
					
				$this->redirect('/settings/index');
			} 	
		} else {
			$this->redirect('/settings/index');
		}
		
	}
	
	public function deleteIncomeCategory() 
	{	
		if(isset($_POST['incomeCategoryId'])) {
			
			$income = new Incomes($_POST);

			$income->deleteCategory();

			Flash::addMessage('Kategoria przychodów została usunięta, a należące do niej transakcje przeniesiono do kategorii "Inne". Możesz edytować ich kategorię w przeglądzie bilansu.');

			$this->redirect('/settings/index');
			
		} else {
			$this->redirect('/settings/index');
		}

	}		
	
	public function addIncomeCategory() 
	{	
		if(isset($_POST['newIncomeCategory'])) {
			
			$income = new Incomes($_POST);

			if($income->addIncomeCategory()) {

			Flash::addMessage('Nowa kategoria przychodów została dodana.');

			$this->redirect('/settings/index');
			} else {
				
				Flash::addMessage('Podana kategoria już istnieje.',Flash::WARNING);	
					
				$this->redirect('/settings/index');	
			}
			
		} else {
			$this->redirect('/settings/index');
		}

	}	
	
	public function updateExpenseCategory() 
	{
		if(isset($_POST['expenseCategory'])) {

			
			$expense = new Expenses($_POST);

			if ($expense->updateCategory()) {

				Flash::addMessage('Kategoria wydatków została zedytowana.');

				$this->redirect('/settings/index');

			} else {
					
				Flash::addMessage('Podana kategoria już istnieje.',Flash::WARNING);	
					
				$this->redirect('/settings/index');
			} 	
		} else {
			$this->redirect('/settings/index');
		}
		
	}
	
	public function deleteExpenseCategory() 
	{	
		if(isset($_POST['expenseCategoryId'])) {
			
			$expense = new Expenses($_POST);

			$expense->deleteCategory();

			Flash::addMessage('Kategoria wydatków została usunięta, a należące do niej transakcje przeniesiono do kategorii "Inne". Możesz edytować ich kategorię w przeglądzie bilansu.');

			$this->redirect('/settings/index');
			
		} else {
			$this->redirect('/settings/index');
		}

	}

	public function addExpenseCategory() 
	{	
		if(isset($_POST['newExpenseCategory'])) {
			
			$expense = new Expenses($_POST);

			if($expense->addExpenseCategory()) {

			Flash::addMessage('Nowa kategoria wydatków została dodana.');

			$this->redirect('/settings/index');
			} else {
				
				Flash::addMessage('Podana kategoria już istnieje.',Flash::WARNING);	
					
				$this->redirect('/settings/index');	
			}
			
		} else {
			$this->redirect('/settings/index');
		}

	}	

	public function updatePaymentMethod() 
	{
		if(isset($_POST['paymentId'])) {
			
			$expense = new Expenses($_POST);

			if ($expense->updatePaymentMethod()) {

				Flash::addMessage('Sposób płatności został zedytowany.');

				$this->redirect('/settings/index');

			} else {
					
				Flash::addMessage('Podany sposób płatności już istnieje.',Flash::WARNING);	
					
				$this->redirect('/settings/index');
			} 	
		} else {
			$this->redirect('/settings/index');
		}
		
	}	
	
	public function deletePaymentMethod() 
	{	
		if(isset($_POST['paymentId'])) {
			
			$expense = new Expenses($_POST);

			$expense->deletePaymentMethod();

			Flash::addMessage('Metoda płatności została usunięta, a należące do niej transakcje przeniesiono do metody "Inne". Metodę płątności możesz edytować w przeglądzie bilansu.');

			$this->redirect('/settings/index');
			
		} else {
			$this->redirect('/settings/index');
		}

	}
	
	public function addPaymentMethod() 
	{	
		if(isset($_POST['paymentId'])) {
			
			$expense = new Expenses($_POST);

			if($expense->addPaymentMethod()) {

			Flash::addMessage('Nowa metoda płatności została dodana.');

			$this->redirect('/settings/index');
			} else {
				
				Flash::addMessage('Podana metoda płatności już istnieje.',Flash::WARNING);	
					
				$this->redirect('/settings/index');	
			}
			
		} else {
			$this->redirect('/settings/index');
		}
	}	
}