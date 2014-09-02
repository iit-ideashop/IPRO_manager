<?php
##rename this file to .env.local.php 
return array(

    'DATABASE_HOST' => '',
    'DATABASE_USER' => '',
    'DATABASE_PASSWORD' => '',
    'DEBUG' => 'FALSE',
    ## Default App URL is http://localhost for dev
    'APP_URL' => 'http://localhost',
    ## Mail driver setup, mandrill is mandrill 
    'MAIL_DRIVER' => 'mandrill',
    'MAIL_SMTP' => 'smtp.mandrillapp.com',
    'MAIL_PORT' => '587',
    'MAIL_FROM' => array('address' => 'ipro@iit.edu', 'name' => 'IPRO Manager'),
    'MAIL_SMTP_USER' => 'ipro@iit.edu',
    'MAIL_SMTP_PASS' => '',
    'MAIL_MANDRILL_APIKEY' => '',
    ## oauth config
    'GOOGLE_CLIENT_ID' => '',
    'GOOGLE_CLIENT_SECRET' => ''
);

