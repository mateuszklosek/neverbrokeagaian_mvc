<?php

namespace App\Models;

use PDO;
use \App\Auth;
use \App\Dates;
use \Core\View;

class Balances extends \Core\Model
{
	
	private static function getStartDate()
	{
		
		if(isset($_POST['periodOfTime'])) {
			$periodOfTime = $_POST['periodOfTime'];

			if($periodOfTime == "currentMonth")
			{
				$startDate = "DATE_FORMAT(NOW() ,'%Y-%m-01')";
			} 
			else if ($periodOfTime == "previousMonth") {
				$startDate = "last_day(curdate() - interval 2 month) + interval 1 day";
			}	
			else if ($periodOfTime == "currentYear") {
				$startDate = "DATE_FORMAT(NOW() ,'%Y-01-01')";
			}	
			else if ($periodOfTime == "customPeriod") { 
			
				$firstDate = date("Ymd", strtotime($_POST['startDate']));      
				$secondDate = date("Ymd", strtotime($_POST['endDate']));  
				
				if($firstDate < $secondDate) {
					$startDate = "'".$_POST['startDate']."'";
				} else if($firstDate > $secondDate) {
					$startDate = "'".$_POST['endDate']."'";
				}
			}
		} else {
			$startDate = "DATE_FORMAT(NOW() ,'%Y-%m-01')";
		}
		return $startDate;
	}	
	
	private static function getEndDate()
	{
		if(isset($_POST['periodOfTime'])) {
			
			$periodOfTime = $_POST['periodOfTime'];

			if($periodOfTime == "currentMonth")
			{
				$endDate = "NOW()";
			} 
			else if ($periodOfTime == "previousMonth") {
				$endDate = "last_day(curdate() - interval 1 month)";
			}	
			else if ($periodOfTime == "currentYear") {
					$endDate = "NOW()";
			}	
			else if ($periodOfTime == "customPeriod") { 
			
				$firstDate = date("Ymd", strtotime($_POST['startDate']));      
				$secondDate = date("Ymd", strtotime($_POST['endDate']));  
				
				if($firstDate < $secondDate) {
					$endDate = "'".$_POST['endDate']."'";
				} else if($firstDate > $secondDate) {
					$endDate = "'".$_POST['startDate']."'";
				}
			}
		} else {
			$endDate = "NOW()";
		}
		return $endDate;
	}
		
	public static function getIncomeCategoriesAmount()
	{			
		$db = static::getDB();
		$startDate = static::getStartDate();
		$endDate = static::getEndDate();

		
		$incomeCategoriesAmount = $db->query("SELECT SUM(i.amount) AS amount, ica.name FROM incomes AS i, incomes_category_assigned_to_users as ica WHERE i.user_id = {$_SESSION['user_id']} AND i.income_category_assigned_to_user_id = ica.id AND i.date_of_income BETWEEN $startDate AND $endDate GROUP BY i.income_category_assigned_to_user_id")->fetchAll(PDO::FETCH_ASSOC);
		

		return $incomeCategoriesAmount;
		
	}	
	
	public static function getIncomeCategoriesAmuntInDetail()
	{
		$db = static::getDB();
		
		$startDate = static::getStartDate();
		$endDate = static::getEndDate();
		
		$incomeCategoriesAmountInDetail = $db->query("SELECT i.id, i.amount, i.date_of_income, i.income_comment, ica.name FROM incomes AS i, incomes_category_assigned_to_users as ica WHERE i.user_id={$_SESSION['user_id']} AND i.income_category_assigned_to_user_id = ica.id AND i.date_of_income BETWEEN $startDate AND $endDate ORDER BY i.date_of_income DESC")->fetchAll(PDO::FETCH_ASSOC);
		
		return $incomeCategoriesAmountInDetail;
	}
	
	public static function getExpenseCategoriesAmount()
	{
		$db = static::getDB();
		
		$startDate = static::getStartDate();
		$endDate = static::getEndDate();
		
		$expenseCategoriesAmount = $db->query("SELECT SUM(e.amount)  AS amount, eca.name FROM expenses AS e, expenses_category_assigned_to_users as eca WHERE e.user_id={$_SESSION['user_id']} AND e.expense_category_assigned_to_user_id = eca.id AND e.date_of_expense BETWEEN $startDate AND $endDate GROUP BY e.expense_category_assigned_to_user_id")->fetchAll(PDO::FETCH_ASSOC);
		
		return $expenseCategoriesAmount;
	}

	public static function getExpenseCategoriesAmuntInDetail()
	{
		$db = static::getDB();
		
		$startDate = static::getStartDate();
		$endDate = static::getEndDate();
	
		$expenseCategoriesAmountInDetail = $db->query("SELECT e.id, pa.name as payment, e.amount, e.date_of_expense, e.expense_comment, eca.name FROM expenses AS e, expenses_category_assigned_to_users as eca, payment_methods_assigned_to_users AS pa WHERE e.user_id={$_SESSION['user_id']} AND e.expense_category_assigned_to_user_id = eca.id AND e.payment_method_assigned_to_user_id = pa.id AND e.date_of_expense BETWEEN $startDate AND $endDate ORDER BY e.date_of_expense DESC")->fetchAll(PDO::FETCH_ASSOC);
		
		return $expenseCategoriesAmountInDetail;
	}	
	
	public static function getFirstEchoDate()
	{
		
		if(isset($_POST['periodOfTime'])) {
			$periodOfTime = $_POST['periodOfTime'];

			if($periodOfTime == "currentMonth")
			{
				$firstEchoDate = new \DateTime('first day of this month '.date('Y'));
			} 
			else if ($periodOfTime == "previousMonth") {
				$firstEchoDate = new \DateTime('first day of previous month '.date('Y'));
			}	
			else if ($periodOfTime == "currentYear") {
				$firstEchoDate = new \DateTime('first day of January '.date('Y'));
			}	
			else if ($periodOfTime == "customPeriod") { 
			
				$firstDate = date("Ymd", strtotime($_POST['startDate']));      
				$secondDate = date("Ymd", strtotime($_POST['endDate']));  
				
				if($firstDate < $secondDate) {
						$firstEchoDate = $_POST['startDate'];
				} else if($firstDate > $secondDate) {
					$firstEchoDate = $_POST['endDate'];
				}  
			}
		} else {
			$firstEchoDate = new \DateTime('first day of this month '.date('Y'));
		}
		return $firstEchoDate;
	}	
	
	public static function getSecondEchoDate()
	{
		if(isset($_POST['periodOfTime'])) {
			
			$periodOfTime = $_POST['periodOfTime'];

			if($periodOfTime == "currentMonth")
			{
				$secondEchoDate = Dates::getTodaysDate();
			} 
			else if ($periodOfTime == "previousMonth") {
				$secondEchoDate = new \DateTime('last day of previous month '.date('Y'));
			}	
			else if ($periodOfTime == "currentYear") {
					$secondEchoDate = Dates::getTodaysDate();
			}	
			else if ($periodOfTime == "customPeriod") { 
			
				$firstDate = date("Ymd", strtotime($_POST['startDate']));      
				$secondDate = date("Ymd", strtotime($_POST['endDate']));  
				
				if($firstDate < $secondDate) {
					$secondEchoDate = $_POST['endDate'];
				} else if($firstDate > $secondDate) {
					$secondEchoDate = $_POST['startDate'];
				}	
			}
		} else {
			$secondEchoDate = Dates::getTodaysDate();
		}
		return $secondEchoDate;
	}
	
	
	/*
	foreach ($eQuery as $eBalance) {$expenses_balance = $expenses_balance + $eBalance['amount'];};
		foreach ($iQuery as $iBalance) {$incomes_balance = $incomes_balance + $iBalance['amount'];};
		$balance = $incomes_balance - $expenses_balance;
		$balance_formated = number_format($balance, 2);	
		
	*/
	
	public static function getBalance()
	{
		
			$expenses_balance  = 0;
			$incomes_balance  = 0;

			$startDate = static::getStartDate();
			$endDate = static::getEndDate();
			$incomeCategoriesAmount = static::getIncomeCategoriesAmount();
			$expenseCategoriesAmount = static::getExpenseCategoriesAmount();
			
			foreach ($expenseCategoriesAmount as $eBalance) {$expenses_balance = $expenses_balance + $eBalance['amount'];};
			foreach ($incomeCategoriesAmount as $iBalance) {$incomes_balance = $incomes_balance + $iBalance['amount'];};
			$balance_total = $incomes_balance - $expenses_balance;
			$balance_formated = number_format($balance_total, 2);	
			$balance = array($balance_formated, $incomes_balance, $expenses_balance);
			

			return $balance;

	}
	
	public static function balanceNote()
	{
		$note = array("","");
		$balance = static::getBalance();
		

		if ($balance[0] > 0){
			$note= array("Gratulacje! Realizacja marzeń jest w zasięgu twoich rąk!","text-success");
			}
		else if($balance[0] < 0)
			{
				$note =array("Uwaga! Oddalasz się od osiągnięcia zamierzonych celów.", "text-danger");
			}
		else
			{
				$note=array("","");
			}
		return $note;
	}
 
}
