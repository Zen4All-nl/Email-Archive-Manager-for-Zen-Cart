<?php
if (function_exists('zen_register_admin_page')) {
  if (!zen_page_key_exists('emailArchive')) {
    zen_register_admin_page('emailArchive', 'BOX_TOOLS_EMAIL_ARCHIVE_MANAGER','FILENAME_EMAIL_HISTORY', '', 'tools', 'Y', (int)$configuration_group_id);
  }
}