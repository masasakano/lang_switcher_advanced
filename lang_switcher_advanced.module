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
  switch ($delta) {
    case 'lang_switcher_advanced':
      $block['subject'] = t('Languages Switch');
      $block['content'] = lang_switcher_advanced_list(lang_switcher_advanced_core());
	  //// https://www.drupal.org/node/1104498
      // if (user_access('access content')) {
      //   // Use our custom function to retrieve data.
      //   $result = current_posts_contents();
      //   // Array to contain items for the block to render.
      //   $items = array();
      //   // Iterate over the resultset and format as links.
      //   foreach ($result as $node) {
      //     $items[] = array(
      //       'data' => l($node->title, 'node/' . $node->nid),
      //     );
      //   }
      //  // No content in the last week.
      //   if (empty($items)) {
      //     $block['content'] = t('No posts available.');
      //   }
      //   else {
      //     // Pass data through theme function.
      //     $block['content'] = theme('item_list', array('items' => $items));
      //   }
      // }
    return $block;
  }
}


/**
 * Returns the path with the language prefix.
 *
 * @param $prefix  Language code path-prefix (eg., "ja").
 * @param $orgabspath  Absolute path (eg., info/site/un ).
 * @param $lcode  Language code (eg., "ja").
 * @return string  with a preceding forward-slash.
 *    "/ja/info/site/un"
 */
function get_path_with_lang_prefix($prefix, $orgabspath, $lang_obj) {
//drush_print("get_path_with_lang_prefix: " . $orgabspath);
  $lcode = $lang_obj->language;	// 'ja' etc
  $ret = preg_replace('@^(/)?@', '/' . $prefix . '/', url($orgabspath, array('language' => $lang_obj)));

  $ret = preg_replace('@^/+@', '/', $ret);	// Eliminate duplicated slashes (when the language prefix is null, perhaps the default language).
  $ret = preg_replace("@^(/$prefix){2}@", "/$prefix", $ret);	// Eliminate duplicated language prefixes.
  if ($ret === "/$lcode/") {
    // Adjustment for the front page (chopping the trailing forward-slash).
    $ret = "/$lcode";
//drush_print("Home page adjustment");
  }

  return($ret);
}


/**
 * Returns the HTML <ul> block of the language switcher.
 *
 * @return string
 *    From <ul> to </ul>.
 */
function lang_switcher_advanced_core() {
  // $node=node_load(6);
  // $uri = 'blog/required-strength-belay-anchors';
  // // $uri = '/';
  // $uri = 'lang/index.html';
  // $uri = 'article_misc/siding-spring-suisei-to-tikyu-to-zinrui-to';
  // $uri = 'article_misc/noberu-buturigaku-shou';
  // // $node = menu_get_object("node", 1, drupal_lookup_path("source", $uri));
  // // $node=node_load(1365);
  
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
//drush_print("No node language: " . $lang_page);
  }
  else {
    $lang_page = $node->language;
//drush_print(sprintf("Language of the node (%s), Current (%s)", $lang_page, $language->language));
  }
global $language_content;
//drush_print("language_content: " . $language_content->language);
//drush_print("_GET['q']: " . $_GET['q']);

  $path = drupal_is_front_page() ? '<front>' : $_GET['q'];	# "node/1364" etc
  $hstpath = translation_path_get_translations($path);
  array_walk($hstpath, function(&$v, $k){$v = drupal_get_path_alias($v,$k);});
//print_r($hstpath);
//drush_print("path: " . $path . "  Translated: " . count($hstpath));
  $languages = language_list('enabled');
  $links = array();	// Associated array with keys of Language-code
  $attrs = array();
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
print("1. Identical languages, with the prefix: " . $lcode);
//drush_print("1. Identical languages, with the prefix: " . $lcode);
      } else {
//drush_print("2. Identical languages, with no prefix: " . $lcode);
print("2. Identical languages, with no prefix: " . $lcode);
        // The current link does not contain the path prefix.
        $linkinfo->link = t(
            '<a href="@url"!attr>@linkstr</a>',
            array(
              // '@url' => preg_replace('@^(/)?@', '/' . $each_lang->prefix . '/', url($linkpath, array('language' => $lcode))),
              '@url' => get_path_with_lang_prefix($each_lang->prefix, $linkpath, $each_lang),
              '@tolang'  => $each_lang->native,
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
//drush_print("3. Translation: " . $lcode);
print("3. Translation: " . $lcode);

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
        '%s%s',
        t(
          // '<a href="@url" title="Change the language to @tolang"!attr>@linkstr</a>',
          '<a href="@url"!attr>@linkstr</a>',
          array(
            //'@url' => get_path_with_lang_prefix($each_lang->prefix, $linkpath, $each_lang),
            '@url' => get_path_with_lang_prefix($each_lang->prefix, $linkpath, $each_lang),
            '@tolang'  => $each_lang->native,
            '!attr' => drupal_attributes($arattr),
            '@linkstr' => $each_lang->native,
          ),
          array('langcode' => $lcode)
        ),
        t(
          ' Ver.',
          array(),
          array('langcode' => $lcode)
        )
      );
    }
    else {
printf(" / valid?=(%s) ", drupal_valid_path('/blog'));
      if (($lang_page == LANGUAGE_NONE) ||
          (! $flag['isNode'])) {	// Language neutral.
//drush_print("4. Language Neutral, different lang in interface: " . $lcode);
print("4. Language Neutral, different lang in interface: " . $lcode);
        $cssstyle = '';
        $linkstr_en = 'Menu';
        $linkpath = $path;
      }
      else {	// No translation.
//drush_print("5. No translation: " . $lcode);
print("5. No translation: " . $lcode);
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
            '@url' => get_path_with_lang_prefix($each_lang->prefix, $linkpath, $each_lang),
            '@tolang' => $each_lang->native,
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
function lang_switcher_advanced_list($links) {
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
  $retstr .= "<li>Something</li>";
  $retstr .= "</ul>";
  // $retstr .= print_r($links);
  $retstr .= "<p>Test.....</p>";

  return($retstr);
}

/**
 * Returns the HTML <ul> block of the language switcher.
 *
 * @return string
 *    From <ul> to </ul>.
 */
function lang_switcher_advanced_theme($links) {
  $arlink = array ();
  foreach ($links as $eachc) {	// language-code
    $arlink[] = $eachc->link;
  }
  return theme('item_list', array('items' => $arlink), array('class' => array("language-switcher-locale-url")));
  // title => '', type => 'ul'
  // https://api.drupal.org/api/drupal/includes!theme.inc/function/theme_item_list/7
}

//drush_print(lang_switcher_advanced_list(lang_switcher_advanced_core()));


