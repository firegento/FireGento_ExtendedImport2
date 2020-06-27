<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->
[![All Contributors](https://img.shields.io/badge/all_contributors-4-orange.svg?style=flat-square)](#contributors-)
<!-- ALL-CONTRIBUTORS-BADGE:END -->

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

Features
-----------
1. Add missing product-attribute-options on the fly
2. Allow using category-ids on product import instead of category-path


Requirements
------------
- None

Compatibility
-------------
- Magento >= 2.0

Installation Instructions "Manual" Installation
---------------------------------------------
1. create a directory `app/code/FireGento/ExtendedImport`
2. extract all files of the module there
3. enable the module with

        bin/magento module:enable FireGento/ExtendedImport
        bin/magento setup:upgrade
        
Installation Instructions with Composer
---------------------------------------------

        composer config repositories.firegento_extendedimport2 vcs https://github.com/firegento/FireGento_ExtendedImport2
        composer require firegento/extendedimport dev-master
        bin/magento module:enable FireGento_ExtendedImport
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

## Contributors ✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tr>
    <td align="center"><a href="https://frostblog.net/"><img src="https://avatars3.githubusercontent.com/u/19548641?v=4" width="100px;" alt=""/><br /><sub><b>Jens Richter</b></sub></a><br /><a href="https://github.com/firegento/FireGento_ExtendedImport2/commits?author=frostblogNet" title="Code">💻</a></td>
    <td align="center"><a href="https://github.com/EliasKotlyar"><img src="https://avatars0.githubusercontent.com/u/9529505?v=4" width="100px;" alt=""/><br /><sub><b>Elias Kotlyar</b></sub></a><br /><a href="https://github.com/firegento/FireGento_ExtendedImport2/commits?author=EliasKotlyar" title="Code">💻</a></td>
    <td align="center"><a href="https://www.schmengler-se.de/"><img src="https://avatars1.githubusercontent.com/u/367320?v=4" width="100px;" alt=""/><br /><sub><b>Fabian Schmengler /></b></sub></a><br /><a href="https://github.com/firegento/FireGento_ExtendedImport2/commits?author=schmengler" title="Code">💻</a></td>
    <td align="center"><a href="https://blog.timpack.org/"><img src="https://avatars2.githubusercontent.com/u/1165302?v=4" width="100px;" alt=""/><br /><sub><b>Timon de Groot</b></sub></a><br /><a href="https://github.com/firegento/FireGento_ExtendedImport2/commits?author=tdgroot" title="Code">💻</a></td>
  </tr>
</table>

<!-- markdownlint-enable -->
<!-- prettier-ignore-end -->
<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!

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