FireGento ExtendedImport Extension
=====================
Extended Import Features (ported from AvS_FastSimpleImport)

Facts
-----
- version: 0.0.1
- extension key: FireGento_ExtendedImport
- [extension on GitHub](https://github.com/magento-hackathon/FireGento_ExtendedImport2)

Description
-----------
Adds missing features to Magento CSV import, to be used with FastSimpleImport

Requirements
------------
- ...

Compatibility
-------------
- Magento >= 2.0

Installation Instructions
-------------------------
1. create a directory `app/code/FireGento/ExtendedImport`
2. extract all files of the module there
3. enable the module with

        bin/magento module:enable FireGento/ExtendedImport
        bin/magento setup:upgrade

Uninstallation
--------------
1. remove the directory `app/code/FireGento/ExtendedImport`

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/magento-hackathon/FireGento_ExtendedImport/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Tests
-----
Unit tests are integrated in the Magento unit test suite. To run them separately, simply call

    phpunit
    
in the module directory (it must be installed in a Magento instance to use the bootstrap file)

Developer
---------
Fabian Schmengler
[@fschmengler](https://twitter.com/fschmengler)

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2016 FireGento