<?php
/**
 * Ebizmarts_Abandonedcart Magento JS component
 *
 * @category    Ebizmarts
 * @package     Ebizmarts_Abandonedcart
 * @author      Ebizmarts Team <info@ebizmarts.com>
 * @copyright   Ebizmarts (http://ebizmarts.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Ktpl\AbandonedCart\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();        
        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'ktpl_abandonedcart_flag',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                'unsigned' => true,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Abandoned Cart Flag'
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'ktpl_abandonedcart_token',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length'    => 128,
                'unsigned' => true,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Abandoned Cart Flag'
            ]
        );
        $installer->endSetup();
    }
}