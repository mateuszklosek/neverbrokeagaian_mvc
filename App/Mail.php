<?php

namespace App;

use Mailgun\Mailgun;
use App\Config;
/**
 * Mail
 *
 * PHP version 7.0
 */
 
class Mail
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

	public static function send($to, $subject, $text, $html)
		{
			$mg = Mailgun::create('Config::MAILGUN_API_KEY', 'https://api.eu.mailgun.net');

			$domain = 'Config::MAILGUN_DOMAIN';
			$mg->messages()->send($domain, array('from'    => 'YOUR ADDRESS',
									   'to'      => $to,
									   'subject' => $subject,
									   'text'    => $text,
									   'html'    => $html));
		}

}