# Email Archive Manager for Zen Cart
## DESCRIPTION

Zen Cart has a great email archiving feature.  When enabled, every email sent through your cart is stored in a database table. Unfortunately, the Zen Cart Admin does not offer the possibility to review the archived emails.

This plugin adds this functionality.

I hope you find it useful, enjoy!

--
Frank

By Frank Koehl

Additional coding support by DrByte

Additional coding support by That Software Guy (www.thatsoftwareguy.com)

Code development sponsored by Destination ImagiNation, Inc.
www.destinationimagination.org

This script is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE.

Released under the General Public License (see LICENSE.md).

## FEATURES

Search for emails using any combination of date-range, embedded text, or per email module.

Printer-friendly display of email serch results, including header information.

Resend messages to the original recipient, with original headers.

One-click link to compose/send an email to the recipient of the selected email, both through the Zen web mail interface and a standard mailto link.

Trim archive database (to control size) at increments of 1, 6, or 12 months.

Per message delete.

## INSTALLATION

Always test ANY plugin on a development copy of your shop FIRST: NEVER trust any third-party to be 100% compatible "out of the box" with YOUR shop.

Always backup your shop and database before making changes.

1. Download the package and unzip to a temp directory.
2. Copy the contents of entire "ADMIN_FOLDER" directory to your (renamed) admin folder.
The files are already arranged in the appropriate structure, and there are *no* overwrites of core files!
3. You'll find "Email Archive Manager" under "Tools" in the Admin. You may need to assign it to admin user profiles, if you use them.
4. By default, email archiving is turned off; to start archiving emails, you must turn it on under
   admin->configuration->email options->Email Archiving Active?


Tested & compatible with Zen Cart 1.5.x

## Find a bug?  Have a feature idea?

LET ME KNOW!
All questions, comments, concerns, and wisecracks are welcome.

Zen Forum PM: BlindSide

Forum Thread:

https://www.zen-cart.com/showthread.php?46730-Email-Archive-Manager

## History:
develop: 22/04/2021 - minor bugfixes (torvista)

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

