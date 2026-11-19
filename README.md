# Language Switcher Advanced (Drupal 10.3+ / 11)

Branch `11.x`. The Drupal 7 version is on `master`.

Drupal's language switcher links every language to "this page in that language", whether or not the
content has been translated — the visitor cannot tell if a link leads to a translation or only switches
the menus. This module changes the links of core's language switcher block so that it is clear:

| Situation | Link shown (e.g. on an English page) | Goes to |
|---|---|---|
| Current language | **English** (plain text) | — |
| A translation exists (and is viewable), or the front page | 日本語版 | the Japanese translation |
| No Japanese version of this content | ~~日本語~~ (メニュー) | the same page, menus in Japanese |
| Language-neutral content, or a non-node page (views, user pages…) | 日本語 (メニュー) | the same page, menus in Japanese |

Unlike the D7 version, a page without a translation now links to the same page with the other interface
language (D7 linked to the front page). This is useful when the menu structure differs between languages.

## Installation

1. Copy the module to `web/modules/custom/lang_switcher_advanced` and enable it
   (`drush pm:install lang_switcher_advanced`).
2. Place core's **Language switcher** block (interface language) in your theme.
3. URL-prefix language detection (`/en/…`, `/ja/…`) is assumed, as on saferclimbing.org.

English and Japanese wording is built in; other languages use translatable English strings
(context `lang_switcher_advanced`).

CSS classes on each `<li>`: `is-current`, `is-translation`, `is-unavailable`, `is-interface`.

## Note for saferclimbing.org

The same code currently lives in the theme `masa11` (`masa11_preprocess_links__language_block()`).
Enabling this module there and removing that theme function keeps the behaviour identical; do not keep
both, or the theme function would process the links a second time.

## Licence

See LICENSE.txt.
