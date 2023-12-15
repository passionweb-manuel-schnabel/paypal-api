# Use PayPal API for payments

Shows the integration of the PayPal API for payments. (TYPO3 CMS)

## What does it do?

Adds two plugins to show the process for handling payments with the PayPal API.

## Installation

Add via composer:

    composer require "passionweb/paypal-api"

* Install the extension via composer
* Flush TYPO3 and PHP Cache

## Requirements

This example uses the Paypal Rest API.

## Frontend configuration "enforceValidation"

If this setting is active you need to add the parameters `success`, `custom_param`, `paymentId`, `PayerID` and `token` to the `excludedParameters`
if you want to use exactly the code snippets from this example repository.

    'cacheHash' => [
        'enforceValidation' => true,
        'excludedParameters' => [
            'success',
            'custom_param',
            'paymentId',
            'PayerID',
            'token',
        ],
    ],

## Extension settings

There are the following extension settings available.

### `payPalMode`

    # cat=PayPal; type=string; label=PayPal mode (sandbox/live)
    payPalMode = sandbox

### `payPalUrl`

    # cat=PayPal; type=string; label=PayPal api url
    payPalUrl = https://api-m.sandbox.paypal.com/v1

### `payPalSandboxClientId`

    # cat=PayPal; type=string; label=SANDBOX PayPal client id
    payPalSandboxClientId =

### `payPalSandboxClientSecret`

    # cat=PayPal; type=string; label=SANDBOX PayPal client secret
    payPalSandboxClientSecret =

### `payPalLiveClientId`

    # cat=PayPal; type=string; label=LIVE PayPal client id
    payPalLiveClientId =

### `payPalLiveClientSecret`

    # cat=PayPal; type=string; label=LIVE PayPal client secret
    payPalLiveClientSecret =

### `paypalRedirectPageUid`

    # cat=PayPal; type=int+; label=Uid of the page that should be redirected to if PayPal payment was successful
    paypalRedirectPageUid =


## Troubleshooting and logging

If something does not work as expected take a look at the log file.
Every problem is logged to the TYPO3 log (normally found in `var/log/typo3_*.log`)

## Achieving more together or Feedback, Feedback, Feedback

I'm grateful for any feedback! Be it suggestions for improvement, requests or just a (constructive) feedback on how good or crappy this snippet/repo is.

Feel free to send me your feedback to [service@passionweb.de](mailto:service@passionweb.de "Send Feedback") or [contact me on Slack](https://typo3.slack.com/team/U02FG49J4TG "Contact me on Slack")
