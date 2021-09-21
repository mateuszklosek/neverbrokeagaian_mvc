<?php

namespace App;

/**
 * Mail
 *
 * PHP version 7.0
 */
class Dates
{

    /**
     * Send a message
     *
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */
    public static function getDateOfLastDayOfNextMonth()
    {
        return date("Y-m-t", strtotime("+1 month"));
    }
	
	public static function getTodaysDate()
	{
			$todaysDate = new \DateTime();
			$todaysDateFormat = $todaysDate->format('Y-m-d');
			return $todaysDateFormat;
	}	
	
	public static function getYesterdaysDate()
	{
			return date('Y-m-d',strtotime("-1 days"));
	}	
	public static function getLastDayOfNextMonth()
	{
			$date = new \DateTime('last day of next month '.date('Y'));
			return $date->format('Y-m-d');
	}
}
