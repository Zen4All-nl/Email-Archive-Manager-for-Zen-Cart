<?php

use Zencart\PluginSupport\ScriptedInstaller as ScriptedInstallBase;

class ScriptedInstaller extends ScriptedInstallBase {

  protected $module_name = 'Email Archive Manager';

  protected function executeInstall()
  {
    global $db;

    $configuration_group_id = '';
    zen_deregister_admin_pages(['emailArchive', 'toolsEmailArchive']);
    $db->Execute("INSERT IGNORE INTO " . TABLE_CONFIGURATION_GROUP . " (configuration_group_title, configuration_group_description, sort_order, visible)
                  VALUES ('" . $this->module_name . "', '" . $this->module_name . " Settings', '1', '0');");
    $configuration_group_id = $db->Insert_ID();

    $db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . "
                  SET sort_order = " . (int)$configuration_group_id . "
                  WHERE configuration_group_id = " . (int)$configuration_group_id . ";");
    zen_register_admin_page('toolsEmailArchive', 'BOX_TOOLS_EMAIL_ARCHIVE_MANAGER', 'FILENAME_EMAIL_HISTORY', '', 'tools', 'Y', (int)$configuration_group_id);

    $this->executeInstallerSql($sql);
  }

  protected function executeUninstall()
  {
    zen_deregister_admin_pages(['toolsEmailArchive']);

    $deleteMap = $this->module_name;

    $sql = "DELETE FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title IN (" . $deleteMap . ")";

    $this->executeInstallerSql($sql);
  }

}
