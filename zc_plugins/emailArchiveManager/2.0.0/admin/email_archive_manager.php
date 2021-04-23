<?php
/**
 *
 * EMAIL ARCHIVE SEARCH
 * @version Version 2.0
 *
 * @author Erik Kerkhoven (Zen4All) <info@zen4all.nl>
 * @author Steve (TorVista)
 * @author Frank Koehl (PM: BlindSide)
 * Support by DrByte
 * Delete button by That Software Guy
 *
 * Powered by Zen Cart (www.zen-cart.com)
 * @copyright Portions Copyright (c) 2010 The Zen Cart Team
 *
 * @license name Released under the GNU General Public License available at www.zen-cart.com/license/2_0.txt or see "license.txt" in the downloaded zip
 *
 */
define('SUBJECT_SIZE_LIMIT', 0); // restrict the subject line length. 0 means no restriction
define('MESSAGE_SIZE_LIMIT', 600); // length of the message excerpt shown in the infoBox

require 'includes/application_top.php';

$action = $_GET['action'] ?? '';
$action = (!empty($_POST['action']) ? $_POST['action'] : $action);

switch ($action) {
  case 'resend':
    // collect the e-mail data
    $email_sql = $db->Execute("SELECT *
                               FROM " . TABLE_EMAIL_ARCHIVE . "
                               WHERE archive_id = " . (int)$_GET['archive_id']);
    $email = new objectInfo($email_sql->fields);
    // resend the message
    // we use 'cc_middle_digs' as the module because that is not archived (don't want to achive the same message twice)
    zen_mail($email->email_to_name, $email->email_to_address, $email->email_subject, $email->email_text, $email->email_from_name, $email->email_from_address, $email->email_html, 'cc_middle_digs');
    $messageStack->add_session(sprintf(SUCCESS_EMAIL_RESENT, $email->archive_id, $email->email_to_address), 'success');
    zen_redirect(zen_href_link(FILENAME_EMAIL_HISTORY));
    break;

  case 'delete':
    $db->Execute("DELETE FROM " . TABLE_EMAIL_ARCHIVE . "
                  WHERE archive_id = " . (int)$_GET['archive_id']);
    zen_redirect(zen_href_link(FILENAME_EMAIL_HISTORY));
    break;

  case 'trim_confirm':
    $age = !empty($_POST['email_age']) ? $_POST['email_age'] : '';
    switch ($age) {
      case ('1_months') :
        $cutoff_date = '1 MONTH';
        break;
      case ('6_months') :
        $cutoff_date = '6 MONTH';
        break;
      case ('1_year') :
        $cutoff_date = '12 MONTH';
        break;
      default:
        $age = '';
    }
    if ($age !== '') {
      $db->Execute("DELETE FROM " . TABLE_EMAIL_ARCHIVE . "
                    WHERE date_sent <= DATE_SUB(NOW(), INTERVAL " . $cutoff_date . ")");
      $db->Execute("OPTIMIZE TABLE " . TABLE_EMAIL_ARCHIVE);
      $messageStack->add_session(sprintf(SUCCESS_TRIM_ARCHIVE, $cutoff_date), 'success');
    }
    zen_redirect(zen_href_link(FILENAME_EMAIL_HISTORY));
    break;

  case 'print_format':
    break;
}
$email_module = $db->Execute("SELECT DISTINCT module
                              FROM " . TABLE_EMAIL_ARCHIVE . "
                              ORDER BY module ASC");
$email_module_array[] = [
  'id' => 1,
  'text' => TEXT_ALL_MODULES
];
foreach ($email_module as $item) {
  $email_module_array[] = [
    'id' => $item['module'],
    'text' => $item['module']
  ];
}
$search_sd = isset($_POST['start_date']) && zen_not_null($_POST['start_date']);
$search_ed = isset($_POST['end_date']) && zen_not_null($_POST['end_date']);
$search_text = isset($_POST['text']) && zen_not_null($_POST['text']);
$search_module = isset($_POST['module']) && zen_not_null($_POST['module']) && $_POST['module'] !== '1';
$sd_raw = isset($_POST['start_date']) ? zen_date_raw($_POST['start_date']) : '';
$ed_raw = isset($_POST['end_date']) ? zen_date_raw($_POST['end_date']) : '';
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
  </head>
  <body>
    <?php ($action !== 'print_format' ? require DIR_WS_INCLUDES . 'header.php' : ''); ?>
    <div class="container-fluid">
      <?php
      switch ($action) {
        case 'delete_confirm':
        case 'resend_confirm':
        case 'prev_text':
        case 'prev_html':
          $this_email = $db->Execute("SELECT *
                                      FROM " . TABLE_EMAIL_ARCHIVE . "
                                      WHERE archive_id = " . (int)$_GET['archive_id']);

          if ($action === 'prev_html') {
            $html_content = $this_email->fields['email_html'];
            $html_content = str_replace('__', '><', $html_content);
            $html_content = str_replace('_html', '<html', $html_content);
            $html_content = str_replace('_/html', '</html', $html_content);
            $html_content = str_replace('html_', 'html>', $html_content);
            $html_content = str_replace('_head', '<head', $html_content);
            $html_content = str_replace('_/head', '</head', $html_content);
            $html_content = str_replace('head_', 'head>', $html_content);
            $html_content = str_replace('_body', '<body', $html_content);
            $html_content = str_replace('_/body', '</body', $html_content);
            $html_content = str_replace('body_', 'body>', $html_content);
            $html_content = str_replace('_meta', '<meta', $html_content);
            $html_content = str_replace('_base', '<base', $html_content);
            $html_content = str_replace('_table_', '<table>', $html_content);
            $html_content = str_replace('_table ', '<table ', $html_content);
            $html_content = str_replace('_/table', '</table', $html_content);
            $html_content = str_replace(['_tr_', '_tr>'], '<tr>', $html_content);
            $html_content = str_replace(['_/tr_', '_/tr>'], '</tr>', $html_content);
            $html_content = str_replace(['_td_', '<td_'], '<td>', $html_content);
            $html_content = str_replace('_td ', '<td ', $html_content);
            $html_content = str_replace(['_/td_', '_/td>', '</td_'], '</td>', $html_content);
            $html_content = str_replace('"_', '">', $html_content);
            $html_content = str_replace('_ ', '> ', $html_content);
            $html_content = str_replace('_li>', '<li>', $html_content);
            $html_content = str_replace('_div', '<div', $html_content);
            $html_content = str_replace('_/div', '</div', $html_content);
            $html_content = str_replace('div_', 'div>', $html_content);
            $html_content = str_replace('_strong_', '<strong>', $html_content);
            $html_content = str_replace('_/strong_', '</strong>', $html_content);
            $html_content = str_replace('strong_', 'strong>', $html_content);
            $html_content = str_replace('_/strong', '</strong', $html_content);
            $html_content = str_replace('_!', '<!', $html_content);
            $html_content = str_replace('--_', '-->', $html_content);
            $html_content = str_replace(['_br_', '_br /_', '_br />'], '<br />', $html_content);
            $html_content = str_replace('_style', '<style', $html_content);
            $html_content = str_replace('_/style', '</style', $html_content);
            $html_content = str_replace('style_', 'style>', $html_content);
            $html_content = str_replace('em_', 'em>', $html_content);
            $html_content = str_replace('_/em', '</em', $html_content);
            $html_content = str_replace('_img ', '<img ', $html_content);
            $html_content = str_replace('_a href', '<a href', $html_content);
            $html_content = str_replace(['_/a_', '_/a>'], '</a>', $html_content);
            $html_content = str_replace('/_', '/>', $html_content);
            $html_content = str_replace('_', '>', $html_content);
            $html_content = str_replace(['<html>', '</html>'], '', $html_content);
            $html_content = str_replace(['<head>', '</head>'], '', $html_content);
            $html_content = str_replace(['<body>', '</body>'], '', $html_content);
            $html_content = str_replace('&quot;_', '">', $html_content);
            $html_content = str_replace('_nobr', '<nobr', $html_content);
            $html_content = str_replace(';nbsp;', '&nbsp;', $html_content);
            $html_content = str_replace('&amp;', '&', $html_content);
            $html_content = str_replace('&amp&', '&&', $html_content);
            $html_content = str_replace('&&nbsp;', '&nbsp;', $html_content);
            $html_content = str_replace('&quot;', '"', $html_content);
          }
          ?>
          <h1 class="pageHeading"><a href="><?php echo zen_href_link(FILENAME_EMAIL_HISTORY); ?>"></a><?php echo zen_image(DIR_WS_IMAGES . HEADER_LOGO_IMAGE, HEADER_ALT_TEXT); ?> <?php echo TEXT_EMAIL_NUMBER . $this_email->fields['archive_id']; ?></h1>
          <div class="row"><?php echo zen_draw_separator('pixel_trans.gif', 1, 5); ?></div>
          <table class="table">
            <tr>
              <td class="main"><b><?php echo TEXT_EMAIL_FROM; ?></b></td>
              <td class="main"><?php echo $this_email->fields['email_from_name'] . ' [' . $this_email->fields['email_from_address'] . ']'; ?></td>
            </tr>
            <tr>
              <td class="main"><b><?php echo TEXT_EMAIL_TO; ?></b></td>
              <td class="main"><?php echo $this_email->fields['email_to_name'] . ' [' . $this_email->fields['email_to_address'] . ']'; ?></td>
            </tr>
            <tr>
              <td class="main"><b><?php echo TEXT_EMAIL_DATE_SENT; ?></b></td>
              <td class="main"><?php echo zen_datetime_short($this_email->fields['date_sent']); ?></td>
            </tr>
            <tr>
              <td class="main"><b><?php echo TEXT_EMAIL_SUBJECT; ?></b></td>
              <td class="main"><?php echo $this_email->fields['email_subject']; ?></td>
            </tr>
            <?php if ($action === 'resend_confirm') { ?>
              <tr>
                <td class="main"><b><?php echo POPUP_CONFIRM_RESEND; ?></b></td>
                <td class="main">
                  <a href="<?php echo zen_href_link(FILENAME_EMAIL_HISTORY, 'action=resend' . '&archive_id=' . $this_email->fields['archive_id'] . (isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '')); ?>" class="btn btn-warning" role="button"><?php echo BUTTON_RESEND_EMAIL; ?></a>
                  <a href="<?php echo zen_href_link(FILENAME_EMAIL_HISTORY, 'archive_id=' . $this_email->fields['archive_id'] . (isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '')); ?>" class="btn btn-default" role="button"><?php echo IMAGE_BACK ?></a>
                </td>
              </tr>
            <?php } ?>
            <?php if ($action === 'delete_confirm') { ?>
              <tr>
                <td class="main"><b><?php echo POPUP_CONFIRM_DELETE; ?></b></td>
                <td class="main">
                  <a href="<?php echo zen_href_link(FILENAME_EMAIL_HISTORY, zen_get_all_get_params(['action']) . 'action=delete'); ?>" class="btn btn-warning" role="button"><?php echo BUTTON_DELETE_EMAIL; ?></a>
                  <a href="<?php echo zen_href_link(FILENAME_EMAIL_HISTORY, 'archive_id=' . $this_email->fields['archive_id'] . (isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '')); ?>" class="btn btn-default" role="button"><?php echo IMAGE_BACK ?></a>
                </td>
              </tr>
            <?php } ?>
          </table>
          <div class="row">
            <?php echo zen_draw_separator('pixel_black.gif', '100%', 1); ?>
            <?php echo zen_draw_separator('pixel_trans.gif', '100%', 5); ?>
          </div>
          <div class="well">
            <?php
            if ($action === 'prev_html') {
              echo $html_content;
            } else {
              echo nl2br($this_email->fields['email_text']);
            }
            ?>
          </div>
          <?php
          break;
        case 'trim':
          ?>
          <h1 class="pageHeading"><?php echo TEXT_TRIM_ARCHIVE; ?></h1>
          <?php echo zen_draw_form('trim_timeframe', FILENAME_EMAIL_HISTORY, 'action=trim_confirm', 'post', 'class="form-horizontal"'); ?>
          <div class="form-group">
            <div class="control-label col-sm-3"><?php echo HEADING_TRIM_INSTRUCT; ?></div>
            <div id="email_age_group" class="col-sm-9 col-md-6">
              <div class="radio">
                <label><?php echo zen_draw_radio_field('email_age', '1_months', true) . ' ' . RADIO_1_MONTH . ' (' . date("m/d/Y", mktime(0, 0, 0, date("m") - 1, (int)date("d"), (int)date("Y"))) . ')'; ?></label>
              </div>
              <div class="radio">
                <label><?php echo zen_draw_radio_field('email_age', '6_months') . ' ' . RADIO_6_MONTHS . ' (' . date("m/d/Y", mktime(0, 0, 0, date("m") - 6, (int)date("d"), (int)date("Y"))) . ')'; ?></label>
              </div>
              <div class="radio">
                <label><?php echo zen_draw_radio_field('email_age', '1_year') . ' ' . RADIO_1_YEAR . ' (' . date("m/d/Y", mktime(0, 0, 0, (int)date("m"), (int)date("d"), (int)date("Y") - 1)) . ')'; ?></label>
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <p class="bg-danger"><?php echo TRIM_CONFIRM_WARNING; ?></p>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-3 col-sm-9 col-md-6">
              <button type="submit" class="btn btn-primary"><?php echo BUTTON_TRIM_CONFIRM; ?></button> <a href="<?php echo zen_href_link(FILENAME_EMAIL_HISTORY); ?>" class="btn btn-default" role="button"><?php echo BUTTON_CANCEL; ?></a>
            </div>
          </div>
          <?php echo '</form>'; ?>
          <?php
          break;
        case'print_format':
        default:
          ?>
          <?php if ($action === 'print_format') { ?>
            <h1 class="pageHeading"><a href="<?php echo zen_href_link(FILENAME_EMAIL_HISTORY); ?>"><?php echo HEADING_TITLE; ?></a>: <?php echo date('l M d, Y'); ?></h1>
          <?php } else { ?>
            <h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
            <div class="col-sm-12">
              <p><?php echo HEADING_SEARCH_INSTRUCT; ?></p>
            </div>
            <div class="col-sm-12 text-right">
              <a href="<?php echo zen_href_link(FILENAME_EMAIL_HISTORY, 'action=trim'); ?>" class="btn btn-primary" role="button"><?php echo TEXT_TRIM_ARCHIVE; ?></a>
            </div>
            <?php echo zen_draw_form('search', FILENAME_EMAIL_HISTORY, '', 'post', 'class="form-horizontal"'); ?>
            <div class="col-sm-4">
              <div class="form-group">
                <?php echo zen_draw_label(HEADING_START_DATE, 'start_date', 'class="control-label col-sm-3"'); ?>
                <div class="col-sm-9">
                  <div class="date input-group" id="datepicker">
                    <span class="input-group-addon datepicker_icon">
                      <i class="fa fa-calendar fa-lg">&nbsp;</i>
                    </span>
                    <?php echo zen_draw_input_field('start_date', '', 'class="form-control" id="start_date" autocomplete="off"'); ?>
                  </div>
                  <span class="help-block errorText">(<?php echo zen_datepicker_format_full(); ?>)</span>
                </div>
              </div>
              <div class="form-group">
                <?php echo zen_draw_label(HEADING_END_DATE, 'end_date', 'class="control-label col-sm-3"'); ?>
                <div class="col-sm-9">
                  <div class="date input-group" id="datepicker">
                    <span class="input-group-addon datepicker_icon">
                      <i class="fa fa-calendar fa-lg">&nbsp;</i>
                    </span>
                    <?php echo zen_draw_input_field('end_date', '', 'class="form-control" id="end_date" autocomplete="off"'); ?>
                  </div>
                  <span class="help-block errorText">(<?php echo zen_datepicker_format_full(); ?>)</span>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <?php echo zen_draw_label(HEADING_SEARCH_TEXT, 'text', 'class="control-label col-sm-3"'); ?>
                <div class="col-sm-9">
                  <?php echo zen_draw_input_field('text', '', 'class="form-control" id="text"'); ?>
                </div>
              </div>
              <?php
              if (!empty($_POST['text'])) {
                $keywords = zen_db_input(zen_db_prepare_input($_POST['text']));
                ?>
                <div class="form-group">
                  <div class="col-sm-12"><?php echo HEADING_SEARCH_TEXT_FILTER . $keywords; ?> </div>
                </div>
              <?php } ?>
              <div class="form-group">
                <?php echo zen_draw_label(HEADING_MODULE_SELECT, 'module', 'class="control-label col-sm-3"'); ?>
                <div class="col-sm-9">
                  <?php echo zen_draw_pull_down_menu('module', $email_module_array, (empty($_POST['module']) ? '' : $_POST['module']), 'class="form-control" id="module"');
                  ?>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <div class="col-sm-12">
                  <div class="checkbox">
                    <label><?php echo zen_draw_checkbox_field('action', 'print_format') . ' ' . HEADING_PRINT_FORMAT; ?></label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <button type="submit" class="btn btn-primary"><?php echo BUTTON_SEARCH; ?></button>
                </div>
              </div>
            </div>
            <?php echo '</form>'; ?>
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 configurationColumnLeft">
              <?php } ?>
              <table class="table table-hover">
                <thead>
                  <tr class="dataTableHeadingRow">
                    <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL_DATE; ?></th>
                    <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS_NAME; ?></th>
                    <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS_EMAIL; ?></th>
                    <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL_SUBJECT; ?></th>
                    <th class="dataTableHeadingContent text-right"><?php echo TABLE_HEADING_EMAIL_FORMAT; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // build search query

                  $archive_search = "SELECT *
                                     FROM " . TABLE_EMAIL_ARCHIVE . PHP_EOL;
                  if ($search_sd || $search_ed || $search_text || $search_module) {
                    $archive_search .= "WHERE ";
                  }

                  if ($search_sd) {
                    $archive_search .= "date_sent >= '" . $sd_raw . "'" . PHP_EOL;
                  }

                  if ($search_ed) {
                    if ($search_sd) {
                      $archive_search .= "AND ";
                    }
                    $archive_search .= "date_sent <= DATE_ADD('" . $ed_raw . "', INTERVAL 1 DAY) " . PHP_EOL;
                  }

                  if ($search_text) {
                    if ($search_sd || $search_ed) {
                      $archive_search .= "AND ";
                    }

                    $keywords = zen_db_input(zen_db_prepare_input($_POST['text']));
                    $archive_search .= "(email_to_address LIKE '%" . $keywords . "%' OR email_subject LIKE '%" . $keywords . "%' OR email_html LIKE '%" . $keywords . "%' OR email_text LIKE '%" . $keywords . "%' OR email_to_name LIKE '%" . $keywords . "%') " . PHP_EOL;
                  }

                  if ($search_module) {
                    if ($search_sd || $search_ed || $search_text) {
                      $archive_search .= "AND ";
                    }
                    $archive_search .= "module = '" . zen_db_prepare_input($_POST['module']) . "'" . PHP_EOL;
                  }

                  $archive_search .= "ORDER BY archive_id DESC";

                  $email_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ORDERS, $archive_search, $email_query_numrows);

                  $email_archive = $db->Execute($archive_search);

                  foreach ($email_archive as $item) {
                    if ((!isset($_GET['archive_id']) || (isset($_GET['archive_id']) && ($_GET['archive_id'] === $item['archive_id']))) && !isset($archive)) {
                      $archive = new objectInfo($item);
                    }

                    if ($action === 'print_format') {
                      ?>
                      <tr class="dataTableRow">
                      <?php } elseif (isset($archive) && is_object($archive) && ($item['archive_id'] === $archive->archive_id)) { ?>
                      <tr id="defaultSelected" class="dataTableRowSelected" onclick="document.location.href = '<?php echo zen_href_link(FILENAME_EMAIL_HISTORY, zen_get_all_get_params(['archive_id', 'action']) . 'archive_id=' . $archive->archive_id . '&action=view'); ?>'">
                      <?php } else { ?>
                      <tr class="dataTableRow" onclick="document.location.href = '<?php echo zen_href_link(FILENAME_EMAIL_HISTORY, zen_get_all_get_params(['archive_id']) . 'archive_id=' . $item['archive_id']); ?>'">
                      <?php } ?>
                      <td class="dataTableContent"><?php echo zen_datetime_short($item['date_sent']); ?></td>
                      <td class="dataTableContent"><?php echo $item['email_to_name']; ?></td>
                      <td class="dataTableContent"><?php echo $item['email_to_address']; ?></td>
                      <td class="dataTableContent">
                        <?php
                        if (SUBJECT_SIZE_LIMIT === 0) {
                          echo $item['email_subject'];
                        } elseif (strlen($item['email_subject']) > SUBJECT_SIZE_LIMIT) {
                          echo substr($item['email_subject'], 0, SUBJECT_SIZE_LIMIT + 3) . '&hellip;';
                        }
                        ?>
                      </td>
                      <td class="dataTableContent text-right">
                        <?php
                        if (isset($archive) && is_object($archive) && ($item['archive_id'] === $archive->archive_id) && $action !== 'print_format') {
                          echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
                        } else {
                          if ($item['email_html'] !== '') {
                            echo TABLE_FORMAT_HTML;
                          } else {
                            echo TABLE_FORMAT_TEXT;
                          }
                        }
                        ?>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              <?php if ($action !== 'print_format') { ?>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 configurationColumnRight">
                <?php
                // create sidebox
                $heading = [];
                $contents = [];

                if (isset($archive) && is_object($archive)) {

                  // get the customer ID
                  $customer = $db->Execute("SELECT customers_id
                                            FROM " . TABLE_CUSTOMERS . "
                                            WHERE customers_email_address
                                            LIKE '" . $archive->email_to_address . "'");
                  if ($customer->RecordCount() === 1) {
                    $mail_button = '<a href="' . zen_href_link(FILENAME_MAIL, 'origin=' . FILENAME_EMAIL_HISTORY . '&customer=' . $archive->email_to_address . '&cID=' . (int)$customer->fields['customers_id']) . '" class="btn btn-primary" role="button">' . IMAGE_EMAIL . '</a>';
                  } else {
                    $mail_button = '<a href="mailto:' . $archive->email_to_address . '" class="btn btn-primary" role="button">' . IMAGE_EMAIL . '</a>';
                  }

                  $heading[] = [
                    'text' => '<h4>' . TEXT_ARCHIVE_ID . $archive->archive_id . '&nbsp; - &nbsp;' . zen_datetime_short($archive->date_sent) . '</h4>'
                  ];
                  $contents[] = [
                    'align' => 'text-center',
                    'text' => $mail_button . '&nbsp;<a href="' . zen_href_link(FILENAME_EMAIL_HISTORY, 'archive_id=' . $archive->archive_id . '&action=resend_confirm' . (isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '')) . '" class="btn btn-primary" role="button">' . IMAGE_ICON_RESEND . '</a>'
                  ];
                  // Delete button
                  $contents[] = [
                    'align' => 'text-center',
                    'text' => '<a href="' . zen_href_link(FILENAME_EMAIL_HISTORY, 'archive_id=' . $archive->archive_id . '&action=delete_confirm' . (isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '')) . '" class="btn btn-warning" role="button">' . IMAGE_ICON_DELETE . '</a>'];
                  $contents[] = [
                    'align' => 'text-center',
                    'text' => '<a href="' . zen_href_link(FILENAME_EMAIL_HISTORY, 'archive_id=' . $archive->archive_id . '&action=prev_text' . (isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '')) . '" target="_blank" class="btn btn-primary" role="button">' . IMAGE_ICON_TEXT . '</a>'
                  ];
                  if ($archive->email_html !== '') {
                    $contents[] = [
                      'align' => 'text-center',
                      'text' => '<a href="' . zen_href_link(FILENAME_EMAIL_HISTORY, 'archive_id=' . $archive->archive_id . '&action=prev_html' . (isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '')) . '" target="_blank" class="btn btn-primary" role="button">' . IMAGE_ICON_HTML . '</a>'
                    ];
                  }
                  $contents[] = [
                    'text' => zen_draw_separator()
                  ];
                  $contents[] = [
                    'text' => '<b>' . TEXT_EMAIL_MODULE . '</b>' . $archive->module
                  ];
                  $contents[] = [
                    'text' => '<b>' . TEXT_EMAIL_FROM . '</b>' . $archive->email_from_name . ' [' . $archive->email_from_address . ']'
                  ];
                  $contents[] = [
                    'text' => '<b>' . TEXT_EMAIL_TO . '</b>' . $archive->email_to_name . ' [' . $archive->email_to_address . ']'
                  ];
                  $contents[] = [
                    'text' => '<b>' . TEXT_EMAIL_DATE_SENT . '</b>' . $archive->date_sent
                  ];
                  $contents[] = [
                    'text' => '<b>' . TEXT_EMAIL_SUBJECT . '</b>' . $archive->email_subject
                  ];
                  $contents[] = [
                    'text' => '<b>' . TEXT_EMAIL_EXCERPT . '</b>'
                  ];
                  $contents[] = [
                    'text' => nl2br(substr($archive->email_text, 0, MESSAGE_SIZE_LIMIT)) . '&hellip;'
                  ];
                }

                // display sidebox
                if (zen_not_null($heading) && zen_not_null($contents)) {
                  $box = new box();
                  echo $box->infoBox($heading, $contents);
                }
                ?>
              </div>
            </div>
            <div class="row">
              <table class="table">
                <tr>
                  <td><?php echo $email_split->display_count($email_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ORDERS, (int)$_GET['page'], TEXT_DISPLAY_NUMBER_OF_EMAILS); ?></td>
                  <td class="text-right"><?php echo $email_split->display_links($email_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ORDERS, MAX_DISPLAY_PAGE_LINKS, (int)$_GET['page'], zen_get_all_get_params(['archive_id', 'page'])); ?></td>
                </tr>
              </table>
            </div>
            <?php
          }
          break;
      }
      ?>
    </div>
    <?php if ($action !== 'print_format') { ?>
      <!-- script for datepicker -->
      <script>
        $(function () {
          $('input[name="start_date"]').datepicker();
          $('input[name="end_date"]').datepicker();
        })
      </script>
      <?php
      require DIR_WS_INCLUDES . 'footer.php';
    }
    ?>
  </body>
</html>
<?php
require DIR_WS_INCLUDES . 'application_bottom.php';
