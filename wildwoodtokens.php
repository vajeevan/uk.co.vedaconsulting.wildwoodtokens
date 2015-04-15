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
      $whereClause = "civicrm_contact.id IN ($contactIDString)";

      $query = "SELECT contact_id,start_date,membership_expiry_date_8 FROM  civicrm_membership
      LEFT JOIN civicrm_value_membership_expiry_date_5 ON(civicrm_value_membership_expiry_date_5.entity_id=civicrm_membership.id)
      LEFT JOIN civicrm_contact ON(civicrm_contact.id=civicrm_membership.contact_id)
      WHERE $whereClause";

      $dao = CRM_Core_DAO::executeQuery($query);

      while ($dao->fetch()) {
        $start_date = $dao->start_date;
        $exp_date = $dao->membership_expiry_date_8;

        $date1 = new DateTime($start_date);
        $date2 = new DateTime($exp_date);

        $intervals = $date1->diff($date2);

        $date = "$intervals->d";
        $month = "$intervals->m";
        $year = "$intervals->y";

        if ($date < 42) {
          $exp_date = date('Y-m-d', strtotime($exp_date . "+1 year"));
        }
        $values[$cid]['token_name.date'] = $exp_date;
      }
    }
  }
}
