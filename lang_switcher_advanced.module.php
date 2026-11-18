<?php
/**
 * @file
 * Provides the functions for the advanced language switcher.
 */

/**
 * Implements hook_help().
 *
 * Displays help and module information.
 *
 * @param path
 *   Which path of the site we're using to display help
 * @param arg
 *   Array that holds the current path as returned from arg() function
 */
function lang_switcher_advanced_help($path, $arg) {
  switch ($path) {
    case "admin/help#lang_switcher_advanced":
      return '<p>' . t("Displays links to nodes created on this date") . '</p>';
      break;
  }
} 

/**
 * Implements hook_block_info().
 */
function lang_switcher_advanced_block_info() {
  $blocks['lang_switcher_advanced'] = array(
    // The name that will appear in the block list.
    'info' => t('Language Switcher (Adv)'),
    // Default setting.
    'cache' => DRUPAL_CACHE_PER_ROLE,
  );
  return $blocks;
}


/**
 * Implements hook_block_view().
 *
 * Prepares the contents of the block.
 */
function lang_switcher_advanced_block_view($delta = '') {
  // @see https://www.drupal.org/node/1104498
  switch ($delta) {
    case 'lang_switcher_advanced':
      $block['subject'] = t('Languages');
      $block['content'] = _langswitcheradvanced_list(_langswitcheradvanced_core());
    return $block;
  }
}


/**
 * Returns the HTML <ul> block of the language switcher.
 *
 * @return string
 *    From <ul> to </ul>.
 */
function _langswitcheradvanced_core() {
  
  global $language;

  $flag = array('isNode' => TRUE);

  if (! isset($node)) {
    $node = menu_get_object();
	// Still, no node for Views etc.
  }
  if (empty($node)) {
    // Probably it is not on a node, like views or term or admin interface.
    $lang_page = $language->language;
    $flag['isNode'] = FALSE;
  }
  else {
    $lang_page = $node->language;
  }

  $path = drupal_is_front_page() ? '<front>' : $_GET['q'];	# "node/1364" etc
  $hstpath = translation_path_get_translations($path);
  array_walk($hstpath, function(&$v, $k){$v = drupal_get_path_alias($v,$k);});
  $languages = language_list('enabled');
  $links = array();	// Associated array with keys of Language-code
  foreach ($languages[1] as $each_lang) {
    $lcode = $each_lang->language;	// 'ja' etc
    $arattr = array(
      'id'    => "language-switcher-" . $lcode,
      'class' => array('language-link'),
      'lang'  => $lcode,
      'title' => t('Change the language to @language', array('@language' => $each_lang->native), array('langcode' => $lcode)),
    );
    $linkinfo = new stdClass();

    if (( $lang_page == $lcode) ||
    (($lang_page == LANGUAGE_NONE) && ($language->language == $lcode))) {	// $language: Global
      // The language link is consistent with the node or environment.

      array_push($arattr['class'], 'active');
      $linkpath = $path;

      if (explode('/', request_path())[0] == $lcode) {
        // The current link contains the path prefix, hence no link.
        // Note if the prefix for the default language is undefined, this never happens.
        $linkinfo->link = $each_lang->native;	// Plain text
      } else {
        // The current link does not contain the path prefix.
        $linkinfo->link = t(
            '<a href="@url"!attr>@linkstr</a>',
            array(
              // '@url' => preg_replace('@^(/)?@', '/' . $each_lang->prefix . '/', url($linkpath, array('language' => $lcode))),
              // '@url' => _get_path_with_lang_prefix($each_lang->prefix, $linkpath, $each_lang),
              '@url' => url($linkpath, array('language' => $each_lang)),
              '!attr' => drupal_attributes($arattr),
              '@linkstr' => $each_lang->native,
            ),
            array('langcode' => $lcode)
        );
      }

      $linkinfo->active = 'active';
      $links[$lcode] = $linkinfo;
    }
    elseif ((array_key_exists($lcode, $hstpath)) ||
            (drupal_is_front_page()))  {		// Translation (or Frontpage)

      // Defines the path to be transformed to the other language.
      if (array_key_exists($lcode, $hstpath)) {
        // Translated path exists.
        $linkpath = $hstpath[$lcode];
      }
      else {
        // Translated path doesn't exist - must be Frontpage.
        $linkpath = $path;
      }

      $linkinfo->link = sprintf(
        '%s %s',
        t(
          '<a href="@url"!attr>@linkstr</a>',
          array(
            //'@url' => _get_path_with_lang_prefix($each_lang->prefix, $linkpath, $each_lang),
            // '@url' => _get_path_with_lang_prefix($each_lang->prefix, $linkpath, $each_lang),
            '@url' => url($linkpath, array('language' => $each_lang)),
            '!attr' => drupal_attributes($arattr),
            '@linkstr' => $each_lang->native,
          ),
          array('langcode' => $lcode)
        ),
        t(
          'Version',
          array(),
          array('langcode' => $lcode)
        )
      );
    }
    else {
      if (($lang_page == LANGUAGE_NONE) ||
          (! $flag['isNode'])) {	// Language neutral.
        $cssstyle = '';
        $linkstr_en = 'Menu';
        $linkpath = $path;
      }
      else {	// No translation.
        $cssstyle = 'text-decoration: line-through;';
        $linkstr_en = 'Home';
        $linkpath = '<front>';
      }

      $linkinfo->link = sprintf(
        '<span class="notranslation" style="%s">%s</span> (%s)',
        $cssstyle,
        $each_lang->native,
        t(
          '<a href="@url"!attr>@linkstr</a>',
          array(
            // '@url' => _get_path_with_lang_prefix($each_lang->prefix, $linkpath, $each_lang),
            '@url' => url($linkpath, array('language' => $each_lang)),
            '!attr' => drupal_attributes($arattr),
            '@linkstr' => t($linkstr_en, array(), array('langcode' => $lcode)),
          ),
          array('langcode' => $lcode)
        )
      );
    }
    $links[$lcode] = $linkinfo;
  }

  return($links);
}


/**
 * Returns the HTML <ul> block of the language switcher.
 *
 * @return string
 *    From <ul> to </ul>.
 */
function _langswitcheradvanced_list($links) {
  $retstr = '<ul class="language-switcher-locale-url">' . "\n";
  $n_enable = count($links);
  $i = 0;
  foreach (array_keys($links) as $lcode) {
    $i++;
    // $lcode = $each_lang->language;	// 'ja' etc
    $curdata = $links[$lcode];

    // Composes the array for the CSS class for the <li> element.
    $liclass = array($lcode);
    switch ($i) {
    case 1:
      array_push($liclass, 'first');
      break;
    case $n_enable:
      array_push($liclass, 'last');
      break;
    }
    if (! empty($curdata->active)) {
      array_push($liclass, $curdata->active);
    }

    $lihtml = sprintf("<li%s>", drupal_attributes(array('class' => $liclass)));

    $retstr .= $lihtml . $curdata->link . "</li>\n";
  }
  $retstr .= "</ul>";

  return($retstr);
}


