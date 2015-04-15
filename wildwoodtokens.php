<?php

require_once 'wildwoodtokens.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function wildwoodtokens_civicrm_config(&$config) {
  _wildwoodtokens_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function wildwoodtokens_civicrm_xmlMenu(&$files) {
  _wildwoodtokens_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function wildwoodtokens_civicrm_install() {
  _wildwoodtokens_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function wildwoodtokens_civicrm_uninstall() {
  _wildwoodtokens_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function wildwoodtokens_civicrm_enable() {
  _wildwoodtokens_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function wildwoodtokens_civicrm_disable() {
  _wildwoodtokens_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function wildwoodtokens_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _wildwoodtokens_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function wildwoodtokens_civicrm_managed(&$entities) {
  _wildwoodtokens_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function wildwoodtokens_civicrm_caseTypes(&$caseTypes) {
  _wildwoodtokens_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function wildwoodtokens_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _wildwoodtokens_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

function wildwoodtokens_civicrm_tokens(&$tokens) {
  $tokens['token_name'] = array(
    'token_name.date' => 'Renewal date: Date',
  );
}

function wildwoodtokens_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  if (!empty($tokens['token_name'])) {

    foreach ($cids as $cid) {

      $contactIDString = implode(',', array_values($cids));
      $whereClause = "cm.contact_id IN ($contactIDString)";

      $query = "SELECT cm.contact_id, cv.membership_expiry_date_10 as renewal_date FROM civicrm_membership cm
      JOIN civicrm_value_wildwood_memberships_7 cv ON cm.id = cv.entity_id WHERE $whereClause";

      $dao = CRM_Core_DAO::executeQuery($query);
      while ($dao->fetch()) {
        $current_date = date('Y-m-d');
        $exp_date = $dao->renewal_date;

        $days = _wildwoodtokens_get_date_difference($exp_date, $current_date);

        if ($days < 42) {
          $exp_date = date('Y-m-d', strtotime($exp_date . "+1 year"));
        }
        $values[$cid]['token_name.date'] = $exp_date;
      }
    }
  }
}

/*
 * Function to get number of days difference between 2 dates
 */

function _wildwoodtokens_get_date_difference($date1, $date2) {
  return floor((strtotime($date1) - strtotime($date2)) / (60 * 60 * 24));
}
