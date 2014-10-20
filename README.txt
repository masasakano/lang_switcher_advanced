Add a sophisticated language-switcher block.

Overview
--------
Better language-switcher, taking into account of
the languages of the menu and contents.

Install
-------
Basically, follow the standard procedure.
Make sure the following modules this module depends on are
(installed and) enabled beforehand.

They are in short,
- i18n (Internationalization, Field translation, Translation redirect)

Place this directory (migrate_goo) into your user module directory, usually:
    /YOUR_DRUPAL_ROOT/sites/all/modules/
preserving the directory structure.
Make sure to rename the filenames so the suffix '.php' is deleted.

Then enable the module via /admin/modules or drush
    % drush en lang_switcher_advanced

Usage
-----
This module defines a block.  Add it in your block configuration.

Known issues
------------
- This has once caused a memory crash, despite 512MB is given for PHP.
  Something must be wrong...

Disclaimer
----------
Please use it at your own risk.

Acknowledgements 
----------------
Drupal community.

Authors
-------
Masa Sakano - http://www.drupal.org/user/3022767

