yourls-edition-logger
=====================

This is a clone of the original plugin, which is not maintained anymore.

This [yourls](http://yourls.org/) plugin logs to a file every url insertion, deletion, or modification. So that, it provides traceability of users' actions allowing an open edition policy.


Installation
------------

 * Download and extract this zip.
 * Create a new folder in `user/plugins` named `edition-logger`.
 * Upload contents of unzipped folder to `edition-logger` folder.
 * Install [KLogger](https://github.com/katzgrau/KLogger) with Composer : `composer require katzgrau/klogger:dev-master`
 * Open `user/config.php` to edit.
 * Add the following to the end of the file:

    ```php
    /** Set location of logs folder **/
    define( 'EDITIONLOGGER_LOGFILE', dirname( __DIR__ ).'/logs' ); // This will create a new folder called logs in the root directory
    ```

For more information, follow me at [http://e.suarezsantana.com/](http://e.suarezsantana.com/).

