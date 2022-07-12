# Lock account webhook

This repository is a webhook to listen for a breached password event on login. If a user has compromised credentials, the system will lock their account.

You can read the blog post here: https://fusionauth.io/blog/2020/08/13/locking-an-account-with-breached-password

## To use

Note that breached password detection is a feature available to FusionAuth installations with valid license keys. However you can modify this code to listen for any [webhook event](https://fusionauth.io/docs/v1/tech/events-webhooks/events) to allow for external processing of user data changes.

### Prerequisites

* A modern PHP
* FusionAuth installed
* This repo

### Setup

* Clone this repo and change directory into it.
* Run `composer install`.
* Create an API key for locking the user account.
* Update `config.php` with the API key, your authorization header value, and your FusionAuth instance URL, if needed.
* Start a webserver: `php -S 0.0.0.0:8000` . This should not be used for production.
* Log into the administrative user interface.
* Go to the *Reactor* tab.
* Enable breached password detection by entering a valid license key in the *Reactor* tab.
* Navigate to the *Tenant* section and edit the tenant for which you want to enable webhooks.
* Go to the *Webhooks* tab and enable the `user.password.breach` webhook. Change the transaction setting to 'All the webhooks must succeed'.
* Navigate to the *Password* tab and to the *Breached password detection settings* section. 
* Click the 'Enabled' checkbox to enable this functionality.
* Change the 'On Login' option to be 'Only record the result'.
* Navigate to the *Settings* section and then to *Webhooks* 
* Create a new webhook.
  * Set the value of the URL to: "http://localhost:8000/webhook.php"
  * Uncheck all the events except for `user.password.breach`
  * Set the appropriate authorization header value under the 'Headers' tab.
  * Save it
* You should be able to test it out. 
  * If the event type is anything other than `user.password.breach` the webhook will just log a message.

### To use

* Create a user with an insecure password such as `password` via the *Users* section.
** You may need to disable the breached password detection first and then add the user and then re-enable it.
* In an incognito window, login as this user.
* You should not be able to login.

Look at the user in the administrative interface and you should see the account is now locked.

