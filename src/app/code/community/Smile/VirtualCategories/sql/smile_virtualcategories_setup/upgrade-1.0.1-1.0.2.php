<?php
/**
 * Append Mview index (EE feature) integration for custom products positions in virtual categories
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Smile Searchandising Suite to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile_VirtualCategories
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2015 Smile
 * @license   Apache License Version 2.0
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

if (Mage::helper("smile_elasticsearch")->isEnterpriseSupportEnabled()) {

    Mage::getModel('enterprise_mview/metadata')
        ->setViewName(Smile_VirtualCategories_Model_Indexer_VirtualCategories_Product_Position::METADATA_VIEW_NAME)
        ->setTableName(Smile_VirtualCategories_Model_Indexer_VirtualCategories_Product_Position::TABLE_NAME)
        ->setKeyColumn("product_id")
        ->setGroupCode(Smile_VirtualCategories_Model_Indexer_VirtualCategories_Product_Position::METADATA_GROUP_CODE)
        ->setStatus(Enterprise_Mview_Model_Metadata::STATUS_INVALID)
        ->save();

    $client = Mage::getModel('enterprise_mview/client');

    $table  = $installer->getTable('smile_virtualcategories/category_product_position');

    /* @var $client Enterprise_Mview_Model_Client */
    $client->init($table);

    $client->execute(
        'enterprise_mview/action_changelog_create',
        array(
            'table_name' => $table
        )
    );

    $client->execute(
        'enterprise_mview/action_changelog_subscription_create',
        array(
           'target_table'  => $table,
           'target_column' => "product_id"
        )
    );
}