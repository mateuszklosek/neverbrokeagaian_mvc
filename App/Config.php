<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME =  'name';

    /**
     * Database user
     * @var string
     */
    const DB_USER ='user';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'password';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;
	
	/**
     * Secret key for hash 
     * @var string
     */
    const SECRET_KEY = 'secret key';
	
	/**
     * mailgun api key
     * @var strin
     */
    const MAILGUN_API_KEY = 'your api key';
	
	/**
     * mailgun domain
     * @var string
     */
    const MAILGUN_DOMAIN = 'your domainl';
	/**
     * cpatcha secret key
     * @var string
     */
	const CAPTCHA_SECRET = 'captcha secret';
}
