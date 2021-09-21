<?php

namespace App;


/**
 * Flash notificaton messages for one-time display using the session
 * for storage between requests.
 *
 * PHP version 7.0
 */
class Flash
{
    /**	
	* Succes meesage type
	* @var string
	*/
	const SUCCESS = 'success';
	 /**	
	* warning meesage type
	* @var string
	*/
	const INFO = 'info';
	 /**	
	* Unsucces meesage type
	* @var string
	*/
	const WARNING = 'warning';
	/**
	* Add the message
	*
	* @param string $message the message content
	*
	* @return void
	*/
    public static function addMessage($message, $type = 'success')
    {
		//Create array in the session if it dosent already exist
		if (! isset($_SESSION['flash_notifications'])) {
			$_SESSION['flash_notifications'] = [];
		}
		
		// Append the message to the array
        $_SESSION['flash_notifications'][] = [
            'body' => $message,
            'type' => $type
		];
    }	
	
	/**
     * Get all the message
     *
     * @return mixed An array with all the messages or null if none set
     */
    public static function getMessages()
    {
		if (isset($_SESSION['flash_notifications'])) {
            $messages = $_SESSION['flash_notifications'];
            unset($_SESSION['flash_notifications']);

            return $messages;
		}
    }	
	
	
}