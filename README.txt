Add a sophisticated language-switcher block.

Overview
--------
In Drupal the language of a page has at least 2 meanings, that is,
the language for the interface like menu and the that of the main
content.  For example, in the configuration page for administrators,
to switch the language does not always mean to switch
the configuration for the language.  In "Contents" configuration page,
yes it does and the contents you see are limited to those in that
language you are viewing the page with, whereas in "Site Information"
configuration page, the language you are viewing it with merely means
the language of the interface, such as "FRONT PAGE"(en) and "フロント
ページ"(ja).  In the latter, if you want to edit the multilingual
variable of your choice, such as the site slogan, you have to
switch it at the (text) language link shown at the top of the page.

With the default language switcher, the links to the other language(s)
are active when either tha translation is available or the language
of the page is nuetral.  In the latter case, the default language
switcher merely changes the interface of the language, such as the menu
(though the menu structure can be heavily language-dependent), and it
does nothing with the language of the main content, except for some
intrinsically multilingual variables, like the date.  If the viewer
expects the translation of the content, s/he will be disappointed.

This situation is pretty confusing.

This Language Switcher Advanced module provides a block of a language
swithcer, which takes into account of the languages of the menu and
contents, and shows visitors more clearly which the available
languages are and for what for the current page.  In summary:
 (1) If the translation of the language exists, the link shows the
  text "Version" (or the corresponding word to the language),
 (2) If the translation of the language does not exist, yet if it is
  possible to change the interface to it, the link shows "Menu".
 (3) If there is no translation, and if a visitor can not even change
  the interface to the language, the link is to "Home" and shows it so.

In short, the hyperlinks to all the enabled languages are always
given, but the destination depends, and it is shown.

Install
-------
Basically, follow the standard procedure.
Make sure the following modules this module depends on are
(installed and) enabled beforehand.

They are in short,
- i18n (Internationalization, Field translation, Translation redirect)
- locale

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
- When the site is not in the root, the path given by the language swithcer
  is not correct.
- For paths of non-node type pages except for the frontpage,
  such as Views etc, the language tags never indicate there is
  a translation even if there is, but instead show the link as "Menu".
- The UI or link to translate the title messages is not provided
  (though it is possible to translate them via the built-in i18n interface).

Future developments
-------------------
- Output can be configurable, apart from the default <ul>.
- It can be configurable to use the theme() interface to output.
- The order of the languages can be configurable.

Disclaimer
----------
Please use it at your own risk.

Acknowledgements 
----------------
I thank the Drupal community!

Authors
-------
Masa Sakano - http://www.drupal.org/user/3022767

