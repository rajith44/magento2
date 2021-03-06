<?php
/**
 * Test for \Magento\Framework\Model\ResourceModel
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Model;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_model;

    protected function setUp()
    {
        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(\Magento\Framework\App\ResourceConnection::class);
    }

    public function testGetTableName()
    {
        $tablePrefix = 'prefix_';
        $tableSuffix = 'suffix';
        $tableNameOrig = 'store_website';

        $this->_model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Framework\App\ResourceConnection::class,
            ['tablePrefix' => 'prefix_']
        );

        $tableName = $this->_model->getTableName([$tableNameOrig, $tableSuffix]);
        $this->assertContains($tablePrefix, $tableName);
        $this->assertContains($tableSuffix, $tableName);
        $this->assertContains($tableNameOrig, $tableName);
    }

    /**
     * Init profiler during creation of DB connect
     * @return void
     */
    public function testProfilerInit()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\Framework\DB\Adapter\Pdo\Mysql $connection */
        $connection = $objectManager->create(
            \Magento\TestFramework\Db\Adapter\Mysql::class,
            [
                'config' => [
                    'profiler' => [
                        'class' => \Magento\Framework\Model\ResourceModel\Db\Profiler::class,
                        'enabled' => 'true',
                    ],
                    'username' => 'username',
                    'password' => 'password',
                    'host' => 'host',
                    'type' => 'type',
                    'dbname' => 'dbname',
                ]
            ]
        );

        /** @var \Magento\Framework\Model\ResourceModel\Db\Profiler $profiler */
        $profiler = $connection->getProfiler();

        $this->assertInstanceOf(\Magento\Framework\Model\ResourceModel\Db\Profiler::class, $profiler);
        $this->assertTrue($profiler->getEnabled());
    }
}
