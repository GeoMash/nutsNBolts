Implementing Paid Subscription management in Nuts n Bolts
=========================================================

The target is to implement 2 main plugins for handling the Subscription
manipulation operations in Nuts n Bolts.

NOTE: The main application will communicate with the Subscription
Plugin, but will not communicate with the Payment Plugin directly,
except for the case of handleSilentPost in the Payment Plugin where the
application should be able to receive the POST request and pass it to
that method. Reading the Payment Plugin first then the Subscription
Plugin will be a bottom up approach.

The Payment Plugin:
===================

This plugin contains the functions that enable:

-   Issuing Payment Transactions of the 4 kinds:

    -   Authorize
    -   Capture
    -   Authorize and Capture
    -   Void

-   Creating, Tracking, and Cancelling Recurring Billing Profiles

**The Authorize transaction** is the transaction that verifies that the
customer has the amount of credits required for the payment and reserve
that amount for transfer, but DO NOT do the transfer.

**The Capture transaction** is the transaction that deduct the amount from
the Available Balance of the buyer account and add the same amount to
the balance of the merchant account. In this kind, the funds have not
been marked deducted from the buyer and deposited at the merchant, but
no real funds are transferred between the banks.

**The Settlement**, is the background process that is done by Authorize.Net
in which the funds captured by the mechant from buyers make their way in
real form from the buyer to the merchant bank accounts. This process is
handled entirely by Authorize.Net and requires no merchent intervention.
In Authroize.Net the merchant, however, and through the Authorize.Net
Web Interface, can choose when during the day this operation must be
carried.

**The Void transaction** is the transaction that voids (cancels) a
previously done Authorization, Capture, or Authorization and Capture
transaction that has not yet been settled. Settled transactions CAN NOT
be undone by voiding, rather, the transferred fund should be returned to
the buyer with a “Refund” transaction that can be done from
Authorize.Net Merchant Web Interface. Issuing Refund transactions is NOT
supported in the plugin.

Authorize.Net, at the time of writing this document, provides multiple
APIs to utilize their services. The most advanced of them all is named
Advanced Integration Method (AIM) and which will be used in our plugin.

To create the plugin, we will first create an “AbstractFactory” plugin
for Nuts n Bolts and create a handler for it with the name
“AuthorizeNet”.

We will import the Authorize.Net SDK into the plugin directory. The
class files of the the SDK that are of our concern are:

-   All files in the “shared” directory of the SDK
-   AuthorizeNetAIM.php
-   AuthorizeNetARB.php

Since we are not using the Nutshell autoloader for the Authorize.Net SDK
classes we need do few steps first.

For those files, we will need to apply a “namespace” directive of the
form:

namespace
application\\nutsNBolts\\plugin\\payment\\handler\\authorizeNet;

And for the files in the “shared” directory, we use:

namespace
application\\nutsNBolts\\plugin\\payment\\handler\\authorizeNet\\shared;

Since we applied namespaces to the classes, we need to “use” those
namespaces. For each of the files:

-   AuthorizeNetAIM:

    -   use
        application\\nutsNBolts\\plugin\\payment\\handler\\authorizeNet\\shared\\AuthorizeNetRequest;
    -   use
        application\\nutsNBolts\\plugin\\payment\\handler\\authorizeNet\\shared\\AuthorizeNetRequest;
    -   use
        application\\nutsNBolts\\plugin\\payment\\handler\\authorizeNet\\shared\\AuthorizeNetResponse;

-   AuthorizeNetARB
    
    -   use
        application\\nutsNBolts\\plugin\\payment\\handler\\authorizeNet\\shared\\AuthorizeNetRequest;
    -   use
        application\\nutsNBolts\\plugin\\payment\\handler\\authorizeNet\\shared\\AuthorizeNetXMLResponse;

For the “shared/AuthorizeNetException.php” file, we will need to replace
“extends Exception” with “extends \\Exception”.

### The init method:

This method will be called at the Plugin Initialization. In this method,
all the necessary Authorize.Net files will be loaded.

### The chargeCard method:

This method charge a card holder with a certain amount of money. This
method requires the credit cards information (Card No., Security Code,
Amount to charge, Transaction type). The transaction type is an
indicator for the type of transaction meant to be carried, whether
Authorize Only, Authorize and Capture, or Capture only. This method will
use the AuthorizeNetAIM class to issue the required transaction.

### The void method:

This method is required to do Void transactions that will void a
previously done transaction. It requires the ID of the previously done
transaction and It will use the AuthorizeNetAIM class to issue the
required transaction.

### The createRecurringSubscription method:

This method create a Recurring Subscription, which is a billing scheme
that charge a card with a certain amount of money every certain amount
of time for a certain amount of recurrences. The method requires the
billing first and last name of the creditor, card information, and
interval duration at which the recurring billing will trigger.

The core element of a Recurring Subscription is the Authorize.Net
Automated Recurring Billing (ARB) Profile.

Because ARB charges the first amount of cash for the first bill NOT at
the moment of creation of the ARB Profile, rather, at a later time
depending on Authorize.Net internals. The method will have to do the
first billing as a normal transaction, and then start the billing of the
ARB Profile later at the due time of the second bill. By that we ensure
that the user has been billed once at the time of the subscription
creation and can being consuming the service immediately.

So the first bill will be claimed with a normal transaction, and the
second bill and on will be claimed automatically with the ARB Profile.

The method uses AuthorizeNetARB class.

To create the subscription:

-   Authorize the amount of cash from the card.
-   Create an ARB Profile that start at the time of the next bill.
-   Capture the Authorized amount from the card.

If creating the ARB failed, the Authorization is Voided using the void
method. If it succeed but the Capture failed, the ARB is cancelled with
the deleteRecurringSubscription method (explained later) and the
Authorization transaction is voided with the void method as well.

Upon success, the method return the ID of the ARB Profile created and
the Transaction ID of the Capture Transaction.

### The deleteRecurringSubscription method:

This method deletes a Recurring Subscription by canceling the associated
ARB Profile. It requires the ARB Profile ID.

### The handleSilentPost method:

In Authorize.Net SDK, Transaction operations are synchronous, meaing the
transaction result is known directly after the transaction is issued.
For ARB, when billing occurs and the card is charged with the amount of
cash, this all happen inside the borders of Authorize.Net, thus, the
result of the charging Transaction will need to be delivered to the
merchant server. For that reason, Authorize.Net allows merchents to
recieve the Transactions resposes of the ARB billings as a POST request
to a specific URL with a feature named “Silent Post”.

This method handles the POST request, and pass ARB transactions
information to the “addTransaction” method at the Subscription plugin
which will later use this information to do the required manipulation
the user subscription accordingly.

The merchant will have to set the Silent Post URL in the Authroize.Net
Account through the Web Interface.

The Subscription Plugin:
========================

This plugin is responsible for creating, and suspending subscriptions.
In the normal case, deleting a subscription isn’t supported through the
plugin, as cancelled and expired subscriptions need to remain in storage
for accounting purposes.

The subscription schema is as following:

-   Different subscription packages exist. Each of them is either an OTP
    (One Time Payment) package for which the user pay one time. Or a
    Recurring Subscription Package for which the user will be billed
    repeatedly every specific duration of time.
-   A Transaction is a recording of the Payment plugin transaction
    response, whether successful or not. It also includes a dump of the
    Payment Gateway response.
-   An Invoice is a recording of a “successful” Transaction.
-   A User Subscription is the subscription of a user in one of the
    packages.

When a User Subscription is valid, it has the state “Active”. When the
subscription is deactivated, it’s either set to the state “Canceled
Manually” if it was canceled by the Administrator of the system, or
“Canceled Automatically” for the subscriptions deactivated by the
Scheduled task (see “The Automated Tasks”).

### The subscribe method:

The method subscribe a user to one of the available packages. This
method requires the Package ID, the User ID, and the Card Information.
The method will create the User Subscription by either charging the card
with the Package price in the case of an OTP package with the chargeCard
method of the Payment Plugin, or create a Recurring Billing using the
Payment Plugin createRecurringSubscription method. Transaction and
Invoices will be recorded consequently. And in the case of a Recurring
Billing Subscription, the ID of the Payment Gateway Recurring Billing
Profile (e.g. ARB for Authorize.Net) will be recorded.

For each each user subscription there is an expiry date. For OTP
Packages subscriptions, the expiry date is set once the subscription is
created and calculated from the current date + the package duration.
Whereas, for the Recurring Billing Packages, the expiry date is updated
with each billing transaction made and set to later date activating
further service time for the user.

### The suspend method:

This method suspend an active User Subscription. It requires the User
Subscription ID. If the subscription package is a Recurring Billing
Package, the method will call the Payment Plugin method
“deleteRecurringSubscription” passing to it the Payment Gateway
Recurring Billing Profile ID.

### The addTransaction method:

This method is responsable for tracking the Recurring Subscription
billing. Each time the Payment Gateway charge a card for one of the
Recurring Billing Profiles, a notification is sent to the merchant
server and handled by the Payment Plugin. After extracting and
formatting the required data, the Payment Plugin (in this case the
“handleSilentPost” method) pass the transaction information to the this
method.

To insure that the expiry date isn’t very strict and allow for
Authorize.Net billing automation to act within a margin of time around
the expiry date, the plugin uses a Relaxation period, set for default to
“3 Days”.

After receiving the required transaction information, the execution will
come to one of 2 states:

-   The transaction arrived before the expiration date, or after the
    expiration date but before the end of the Relaxation period. In such
    case, the expiration date is postponed using the Subscription
    Package duration (e.g. 1 month for monthly Packages).
-   The transaction arrived after the Relaxation period of the expiry,
    thus, the subscription is probably by new set to Canceled by the
    Scheduled Tasks (see “The Automated Tasks”). This case can occur for
    Recurring Subscriptions whom billing was interrupted for one or more
    bills but resumed again. The expiry date is set to one Package
    duration from the current date, and the subscription state is set to
    “Active” once again.

The Automated Tasks:
====================

At least one scheduled task should be implemented, which responsibility
will be to validate the activity of the different kinds of
subscriptions.

For the OTP Subscriptions, and after the duration of the package has
elapsed, the task will mark the subscription “Canceled Automatically”.
The task will use the expiry date of the subscription to know when the
duration has elapsed.

For the Recurring Billing Subscriptions, and should one of the bills
fail to be claimed from the card, the subscription will be marked as
canceled as well.

If the expiry date of a subscription is reached, the task will only mark
the subscription canceled after the Relaxation period of that date
elapse, during which (i.e. the Relaxation Period) the Payment Gateway
should be able bill the card and the Subscription Plugin will update the
expiry date for that subscription to a later time.

The End.

