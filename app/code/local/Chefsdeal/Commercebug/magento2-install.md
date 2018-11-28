Magento 2 Installation Guide
==================================================
Since Magento 2 is in a pre-beta state, Commerce Bug's Magento 2 support should be considered equally beta.  If you run into a problem we're **very** interested in hearing about it and getting you a fix, [so let us know](http://www.pulsestorm.net/blog/). 

Commerce Bug 2 for Magento 2 requires a different extension package, which you'll find in 

    beta-magento2/Packagename_Commercebug-2.x-x.tar
    
This package is **not** a Magento Connect package, as it's unclear what Magento Connect for Magento 2 will be.  Instead, it's a simple `tar` archive.  Installation is as simple un-taring the archive into your Magento folder. 

After un-taring the archive.

1. Copy/Upload the contents of the "app" folder to the corresponding "app" folder of your Magento 2 system

2. Copy/Upload the contents of the "pub" folder to the corresponding "pub" folder of your Magento 2 system

3. Ensure the uploaded files and folders have *nix permissions, ownership and groups identical to the rest of your Magento files and folder

4. Clear the Magento Cache (including javascript/CSS cache).  This may be done under [System `->` Cache Management]

5. Log out of the Magento Admin Application to clear you admin session.  This is necessary to grant admin super users the correct access permissions to view Commerce Bug's system configuration.

6. If you're using compilation, re-compile your classes.

7. Configure Commerce Bug (update notifications, output, etc.) at System `->` Configuration `->` Advanced `->` Commerce Bug