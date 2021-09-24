<?php

namespace App\Models;

use PDO;
use \App\Auth;
use \App\Dates;
use \Core\View;

class Expenses extends \Core\Model
{


    public $errors = [];


    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
	
	public static function getUserExpenseCategories()
	{
		$sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name != :name";
	
		$db = static::getDB();
		$expenseCategories = $db->prepare($sql);
        $expenseCategories->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $expenseCategories->bindValue(':name', 'Inne', PDO::PARAM_STR);
		$expenseCategories->execute();

		return $expenseCategories->fetchAll(PDO::FETCH_ASSOC);
	}	
	
	public static function getUserPaymentMethods()
	{
		$sql = "SELECT name,id FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name != :name";
	
		$db = static::getDB();
		$paymentMethods = $db->prepare($sql);
        $paymentMethods->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $paymentMethods->bindValue(':name', 'Inne', PDO::PARAM_STR);
		$paymentMethods->execute();

		return $paymentMethods->fetchAll(PDO::FETCH_ASSOC);
	}

    public function save()
    {
		$this->amount = $this->validateAndConvertPriceFormat();
        $this->validate();

        if (empty($this->errors)) {

			$sql = "INSERT INTO expenses VALUES (NULL,:user_id, :idOfIncomeCategoryAssignedToUser, :idOfPaymentMethodAssignedToUser, :convertedPrice, :date, :comment)";

            $db = static::getDB();
            $stmt = $db->prepare($sql);
		
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':idOfIncomeCategoryAssignedToUser', $this->getIdOfExpenseCategoryAssignedToUser(), PDO::PARAM_INT);
            $stmt->bindValue(':idOfPaymentMethodAssignedToUser', $this->getIdOfPaymentMethodAssignedToUser(), PDO::PARAM_INT);
            $stmt->bindValue(':convertedPrice', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->expenseDate, PDO::PARAM_STR);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }
	
	public function update() 
	{	
		$this->amount = $this->validateAndConvertPriceFormat();
        $this->validate();

        if (empty($this->errors)) {
			$sql = "UPDATE expenses SET expense_category_assigned_to_user_id = :idOfExpenseCategoryAssignedToUser, payment_method_assigned_to_user_id = :payment_method_assigned_to_user_id, date_of_expense = :date, expense_comment = :comment WHERE id = $this->expenseId";
			
			$db = static::getDB();
            $stmt = $db->prepare($sql);
			
			$stmt->bindValue(':idOfExpenseCategoryAssignedToUser', $this->getIdOfExpenseCategoryAssignedToUser(), PDO::PARAM_INT);
			$stmt->bindValue(':payment_method_assigned_to_user_id', $this->getIdOfPaymentMethodAssignedToUser(), PDO::PARAM_INT);
            $stmt->bindValue(':date', $this->expenseDate, PDO::PARAM_STR);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

            return $stmt->execute();
		}
		return false;
	}
	
	public function delete() 
	{
		$sql = "DELETE FROM expenses WHERE id = $this->expenseId";
								
		$db = static::getDB();
		
		return $db->query($sql);

	}
	
	public static function deleteAllUserExpenses()
	{
		$sql = "DELETE FROM expenses WHERE user_id = {$_SESSION['user_id']}";
								
		$db = static::getDB();
		
		return $db->query($sql);
	}
	
	protected function getIdOfExpenseCategoryAssignedToUser()
	{		
		$sql = "SELECT id FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name= :expenseName";

		$db = static::getDB();
		
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(':expenseName', $this->expenseCategory, PDO::PARAM_STR);
		$stmt->execute();	
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $result['id'];
	}	
	
	protected function getIdOfPaymentMethodAssignedToUser()
	{		
		$sql = "SELECT id FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name= :paymentMethod";

		$db = static::getDB();
		
		
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(':paymentMethod', $this->paymentMethod, PDO::PARAM_STR);
		
		$stmt->execute();
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $result['id'];
	
	}
	
	public function validate()
    {

		
       if($this->expenseDate < '2000-01-01' || $this->expenseDate > Dates::getDateOfLastDayOfNextMonth()) {

			$this->errors['date'] = "Data musi mieścić się w przedziale od 2000-01-01 do ".Dates::getDateOfLastDayOfNextMonth().".";
				
		}
		
		if(strlen($this->comment) > 100) {
			$this->errors['comment'] = "Komentarz jest zbyt długi.";
		}	
		
    }

	protected function validateAndConvertPriceFormat() 
	{
		if(preg_match("/^\-?[0-9]*\.?[0-9]+\z/", $this->amount)) {
		
			$this->amount = str_replace(['-', ',', '$', ' '], '', $this->amount);

			if(strpos($this->amount, '.') !== false) {
				$dollarExplode = explode('.', $this->amount);
				$dollar = $dollarExplode[0];
				$cents = $dollarExplode[1];
				if(strlen($cents) === 0) {
					$cents = '00';
				} elseif(strlen($cents) === 1) {
					$cents = $cents.'0';
				} elseif(strlen($cents) > 2) {
					$cents = substr($cents, 0, 2);
				}
				$this->amount = $dollar.'.'.$cents;
			} else {
				$cents = '00';
				$this->amount = $this->amount.'.'.$cents;
			}	


			if($this->amount <0 || $this->amount >=1000000) {
				$this->errors['amount'] = 'Podana kwota musi mieścić się w przedziale od 0 do 1 miliona.';
			}
			
			return $this->amount;
		
		} else {
			$this->errors['amount'] = 'Podana kwota musi być liczbą w poprawnym formacie i być mniejsza niż 1 milion.';
		}
		
		return false;
	}

	public function updateCategory() 
	{	
        if(strlen($this->expenseCategory)<1 || strlen($this->expenseCategory)>40) {
		 return false;
		}
		
		$sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :expenseName AND id <> :id";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(':id', $this->expenseCategoryId, PDO::PARAM_INT);
		$stmt->bindValue(':expenseName', $this->expenseCategory, PDO::PARAM_STR);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($result)==1){
			return false;
		}
		
		$sql = "UPDATE expenses_category_assigned_to_users SET name = :name, categoryLimit = :limit WHERE id = :id";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$this->amount = $this->validateAndConvertPriceFormat();
		
		if($this->amount == "") {
			$stmt->bindValue(':limit', NULL, PDO::PARAM_STR);
		} else {
			$stmt->bindValue(':limit',	$this->amount, PDO::PARAM_STR);
		}
		$stmt->bindValue(':id', $this->expenseCategoryId, PDO::PARAM_INT);
		$stmt->bindValue(':name', $this->expenseCategory, PDO::PARAM_STR);
		

		return $stmt->execute();
	}
	
	public function updatePaymentMethod() 
	{	
        if(strlen($this->paymentCategory)<1 || strlen($this->paymentCategory)>30) {
			return false;
		}

		$sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :paymentName AND id <> :id";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(':id', $this->paymentId, PDO::PARAM_INT);
		$stmt->bindValue(':paymentName', $this->paymentCategory, PDO::PARAM_STR);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($result)==1){
			return false;
		}

		$sql = "UPDATE payment_methods_assigned_to_users SET name = :name WHERE id = :id";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);	
		
		$stmt->bindValue(':id', $this->paymentId, PDO::PARAM_INT);
		$stmt->bindValue(':name', $this->paymentCategory, PDO::PARAM_STR);

		return $stmt->execute();

	}	

	public function deleteCategory()
	{		
	
			$this->updateCategoryToOther();
			
			$sql = "DELETE FROM expenses_category_assigned_to_users WHERE id = :id";
									
			$db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->expenseCategoryId, PDO::PARAM_INT);

            return $stmt->execute();		
	}	
	
	protected function updateCategoryToOther()
	{
		$sql = "UPDATE expenses
				SET expense_category_assigned_to_user_id = :otherCategoryId 
				WHERE expense_category_assigned_to_user_id = :categoryId";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
						
		$stmt->bindValue(':categoryId', $this->expenseCategoryId, PDO::PARAM_INT);
		$stmt->bindValue(':otherCategoryId', $this->getOtherCategoryId(), PDO::PARAM_INT);
		
		return	$stmt->execute();			
	}
	
	protected function getOtherCategoryId() 
	{
		
		$sql = "SELECT id FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :name";
		
		$db = static::getDB();
		
		$stmt = $db->prepare($sql);
		
		
		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(':name', 'Inne', PDO::PARAM_STR);
		
		$stmt->execute();
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $result['id'];	
	}	
	
	public function deletePaymentMethod()
	{		
			$this->updateMethodToOther();
	
			$sql = "DELETE FROM payment_methods_assigned_to_users WHERE id = :id";
									
			$db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->paymentId, PDO::PARAM_INT);

            return $stmt->execute();		
	}	
	
	protected function updateMethodToOther()
	{
		$sql = "UPDATE expenses
				SET payment_method_assigned_to_user_id = :otherMethodId 
				WHERE payment_method_assigned_to_user_id = :methodId";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
						
		$stmt->bindValue(':methodId', $this->paymentId, PDO::PARAM_INT);
		$stmt->bindValue(':otherMethodId', $this->getOtherMethodId(), PDO::PARAM_INT);
		
		return	$stmt->execute();			
	}
	
	protected function getOtherMethodId() 
	{
		
		$sql = "SELECT id FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :name";
		
		$db = static::getDB();
		
		$stmt = $db->prepare($sql);
		
		
		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(':name', 'Inne', PDO::PARAM_STR);
		
		$stmt->execute();
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $result['id'];	
	}	
	
	public function addExpenseCategory()
	{		
		if ($this->validateNewCategoryName()) {
			
			$sql = "INSERT INTO expenses_category_assigned_to_users VALUES (NULL, :user_id, :name, NULL)";
									
			$db = static::getDB();
            $stmt = $db->prepare($sql);
	
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->newExpenseCategory, PDO::PARAM_STR);

            return $stmt->execute();		
			
		}
		return false;
	}	
	
	public function addPaymentMethod()
	{	
		if ($this->validateNewPaymentMethodName()) {
			
			$sql = "INSERT INTO payment_methods_assigned_to_users VALUES (NULL, :user_id, :name)";
									
			$db = static::getDB();
            $stmt = $db->prepare($sql);
	
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->paymentCategory, PDO::PARAM_STR);

            return $stmt->execute();		
			
		}
		return false;
	}		
	
	protected function validateNewCategoryName()
	{
		if(strlen($this->newExpenseCategory)<1 || strlen($this->newExpenseCategory)>40) {
			return false;
		}

		$sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :expenseName";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(':expenseName', $this->newExpenseCategory, PDO::PARAM_STR);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($result)==1){
			return false;
		}
		return true;
	}		
	
	protected function validateNewPaymentMethodName()
	{
		if(strlen($this->paymentCategory)<1 || strlen($this->paymentCategory)>30) {
			return false;	
		}

		$sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :paymentCategoryName";
		
		$db = static::getDB();

		$stmt = $db->prepare($sql);


		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(':paymentCategoryName', $this->paymentCategory, PDO::PARAM_STR);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($result)==1){
			return false;
		}
		
		return true;
			
	}
	
	public function showExpenseLimit() 
	{
		$limit = $this->getLimitOfIncomeCategory();
		$spentAmount = $this->getCategorySpentAmount();
		$diff = $limit - $spentAmount;
		$this->amount = $this->validateAndConvertPriceFormat();
		if($this->amount != "") {
			$possibleSpentAmount = $spentAmount + $this->amount;
		}
		if($limit != null) {
			echo "Miesięczny limit: ".$limit."<br>";
			if($spentAmount != "") {
				echo "Wydatki w tym miesiącu: ".$spentAmount."<br>";
			} else {
				echo "Wydatki w tym miesiącu: 0<br>";
			}
			echo "Różnica: ".$diff."<br>";
			if($this->amount != "") {
				echo "Wydatki + wpisana kwota: ".$possibleSpentAmount;
			}
		}
	}	
	
	public function getFinalValue() 
	{

		if($this->amount != "") {
			$limit = $this->getLimitOfIncomeCategory();
			$spentAmount = $this->getCategorySpentAmount();
			$possibleSpentAmount = $spentAmount + $this->amount;
			if($possibleSpentAmount <= $limit) {
				echo false;
			} else {
				echo true;
			}
			
		}
	}

	protected function getLimitOfIncomeCategory()
	{		
		$sql = "SELECT categoryLimit FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name= :expenseName";

		$db = static::getDB();
		
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt->bindValue(':expenseName', $this->expenseCategory, PDO::PARAM_STR);
		$stmt->execute();	
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $result['categoryLimit'];

	}	
	
	protected function getCategorySpentAmount()
	{	
			
		$sql = "SELECT SUM(amount) as amount FROM expenses WHERE expense_category_assigned_to_user_id = :id AND date_of_expense BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW()";

		$db = static::getDB();	
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':id', $this->getIdOfExpenseCategoryAssignedToUser(), PDO::PARAM_INT);
		$stmt->execute();
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $result['amount'];

	}
	
	public static function setDefaultExpensesCategory($user_id) 
	{
		$sql1 = 'SELECT name FROM expenses_category_default';
		
		$db = static::getDB();
		
		$stmt = $db->prepare($sql1);
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		
		$stmt->execute();
		
		$categories = $stmt->fetchAll();
		
		foreach ($categories as $category) {
			$sql2 = 'INSERT INTO expenses_category_assigned_to_users VALUES (NULL, :id, :category)';
			$UserCategoryQuery = $db->prepare($sql2);
			$UserCategoryQuery->bindValue(':id', $user_id, PDO::PARAM_INT);
			$UserCategoryQuery->bindValue(':category', $category->name, PDO::PARAM_STR);
			
			$UserCategoryQuery->execute();
		}
	}
		
		public static function setDefaultPaymentMethods($user_id) 
	{
		$sql1 = 'SELECT name FROM payment_methods_default';
		
		$db = static::getDB();
		
		$stmt = $db->prepare($sql1);
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		
		$stmt->execute();
		
		$categories = $stmt->fetchAll();
		
		foreach ($categories as $category) {
			$sql2 = 'INSERT INTO payment_methods_assigned_to_users VALUES (NULL, :id, :category)';
			$UserCategoryQuery = $db->prepare($sql2);
			$UserCategoryQuery->bindValue(':id', $user_id, PDO::PARAM_INT);
			$UserCategoryQuery->bindValue(':category', $category->name, PDO::PARAM_STR);
			
			$UserCategoryQuery->execute();
		}
	}
}
