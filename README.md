# Email Archive Manager for Zen Cart
By Frank Koehl
Additional coding support by DrByte
Additional coding support by That Software Guy (www.thatsoftwareguy.com)

Code development sponsored by Destination ImagiNation, Inc.
www.destinationimagination.org

This script is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE.

Released under the General Public License (see LICENSE.txt)

Always backup your shop and database before making changes.

Tested & compatible with Zen Cart 1.5.x

## History:
1.8e 25/02/2019 - Update includes/functions/functions_email.php (BPL)
1.8d         10/26/2018 - Update includes/functions/functions_email.php. (swguy)
1.8c         02/2018 - Update readme. (swguy)
1.8b_zc1.55f 02/2018 - Correct define to support admin profile registration restrictions properly (Twitch)
1.8_zc1.5 07/2017 - Changed order of From and To to match an email client
1.7_zc1.5 07/2012 - Updated for easier install to ZC v1.5
1.6_zc1.5 07/27/2011 - Updated for ZC 1.5.
1.6 09/16/2010 - Fixed date format issues.
1.5 09/14/2010 - Removed short tags, improved support for intl date formats (Blindside)
1.4 05/07/2007 - Fixed bug in displaying html mail (That Software Guy)
1.3 04/27/2007 - Fixed monthly delete (That Software Guy)
1.2 02/18/2007 - Added per message delete button (That Software Guy)
1.1 11/26/2006 - Fixed purge logic (Blindside/Dr. Byte)
1.0 07/07/2006 - First Release (Blindside)


==================================
## Find a bug?  Have a feature idea?
==================================
LET ME KNOW!
All questions, comments, concerns, and wisecracks are welcome.

Zen Forum PM: BlindSide

Forum Thread:

https://www.zen-cart.com/showthread.php?46730-Email-Archive-Manager

====================
## DESCRIPTION
====================
Zen Cart has a great email archiving feature.  When enabled, every email
sent through your cart is stored in a database table.  Unfortunately,
Zen Cart doesn't include an admin utility to look up emails in this table.
Until now, any lookup had to be done directly from the database using a
utility like phpMyAdmin.  This contribution is intended to fill the gap
and complete the e-mail archiving system.

I hope you find it useful, enjoy!

--
Frank


====================
## INSTALLATION
====================
1. Download the package and unzip to a temp directory.
2. Copy the contents of entire "admin" folder to the (renamed) admin folder of your shop, using the existing folder structure as a guide for where to put the new files. The files are already arranged in the appropriate structure, and there are *no* overwrites!
3. You'll find "E-mail Archive Manager" under "Tools" in the Admin. You may need to assign it to additional user profiles, depending on who you wish to allow permission to use it.
4. By default, email archiving is turned off; to start archiving emails, you must turn it on under
   admin->configuration->email options->Email Archiving Active?


====================
## FEATURES
====================
Search for e-mails using any combination of date range, embodied text, or
e-mail module.

Printer-friendly display of who email, including header information.

Resend messages to the original recipient, with complete original headers

One-click link to begin composing email to the recipient of the selected
e-mail, both through the Zen web mail interface and a standard mailto link

Trim archive database (to control size) at increments of 1, 6, or 12 months.

Per message delete.
