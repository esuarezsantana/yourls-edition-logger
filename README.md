yourls-edition-logger
=====================

This [yourls](http://yourls.org/) plugin logs to a file every url insertion, deletion, or modification. So that, it provides traceability of users' actions allowing an open edition policy.


Installation
------------

 * Download and extract this zip.
 * Create a new folder in `user/plugins` named `edition-logger`.
 * Upload contents of zipped folder to `edition-logger` folder.
 * Download and extract zip for [KLogger](https://github.com/katzgrau/KLogger).
 * Create a new folder named `kloger` in `edition-logger` folder.
 * Upload contents of ziped folder to `kloger` folder.
 * Open `user/config.php` to edit.
 * Add the following to the end of the file:

    ```php
    /** Set location of klogger code **/
    define( 'EDITIONLOGGER_KLOGGER_PATH', 'klogger' );
    /** Set location of logs folder **/
    define( 'EDITIONLOGGER_LOGFILE', '../logs' ); //this will create a new folder called logs in the root directory

For more information, follow me at [http://e.suarezsantana.com/](http://e.suarezsantana.com/).

