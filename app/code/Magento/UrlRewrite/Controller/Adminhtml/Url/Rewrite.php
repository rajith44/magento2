<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\UrlRewrite\Controller\Adminhtml\Url;

use Magento\Backend\App\Action;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;

/**
 * URL rewrite adminhtml controller
 */
class Rewrite extends Action
{
    /**#@+
     * Entity types
     */
    const ENTITY_TYPE_CUSTOM = 'custom';
    const ENTITY_TYPE_PRODUCT = 'product';
    const ENTITY_TYPE_CATEGORY = 'category';
    const ENTITY_TYPE_CMS_PAGE = 'cms-page';
    /**#@-*/

    /**
     * @var Product
     */
    protected $_product;

    /**
     * @var Category
     */
    protected $_category;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_cmsPage;

    /**
     * @var \Magento\UrlRewrite\Model\UrlRewrite
     */
    protected $_urlRewrite;

    /**
     * Check whether this contoller is allowed in admin permissions
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_UrlRewrite::urlrewrite');
    }

    /**
     * Get Category from request
     *
     * @return Category
     */
    protected function _getCategory()
    {
        if (!$this->_category) {
            $this->_category = $this->_objectManager->create('Magento\Catalog\Model\Category');
            $categoryId = (int)$this->getRequest()->getParam('category', 0);
            $urlRewrite = $this->_getUrlRewrite();
            if (!$categoryId && $urlRewrite->getId()) {
                $metaData = $urlRewrite->getMetadata();
                if ($urlRewrite->getEntityType() === self::ENTITY_TYPE_CATEGORY) {
                    $categoryId = $urlRewrite->getEntityId();
                } elseif (!empty($metaData['category_id'])) {
                    $categoryId = $metaData['category_id'];
                }
            }
            if ($categoryId) {
                $this->_category->load($categoryId);
            }
        }
        return $this->_category;
    }

    /**
     * Get Product from request
     *
     * @return Product
     */
    protected function _getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_objectManager->create('Magento\Catalog\Model\Product');
            $productId = (int)$this->getRequest()->getParam('product', 0);
            $urlRewrite = $this->_getUrlRewrite();
            if (!$productId && $urlRewrite->getId() && $urlRewrite->getEntityType() === self::ENTITY_TYPE_PRODUCT) {
                $productId = $this->_getUrlRewrite()->getEntityId();
            }
            if ($productId) {
                $this->_product->load($productId);
            }
        }
        return $this->_product;
    }

    /**
     * Get CMS page from request
     *
     * @return \Magento\Cms\Model\Page
     */
    protected function _getCmsPage()
    {
        if (!$this->_cmsPage) {
            $this->_cmsPage = $this->_objectManager->create('Magento\Cms\Model\Page');
            $cmsPageId = (int)$this->getRequest()->getParam('cms_page', 0);
            $urlRewrite = $this->_getUrlRewrite();
            if (!$cmsPageId && $urlRewrite->getId() && $urlRewrite->getEntityType() === self::ENTITY_TYPE_CMS_PAGE) {
                $cmsPageId = $this->_getUrlRewrite()->getEntityId();
            }
            if ($cmsPageId) {
                $this->_cmsPage->load($cmsPageId);
            }
        }
        return $this->_cmsPage;
    }

    /**
     * Get URL rewrite from request
     *
     * @return \Magento\UrlRewrite\Model\UrlRewrite
     */
    protected function _getUrlRewrite()
    {
        if (!$this->_urlRewrite) {
            $this->_urlRewrite = $this->_objectManager->create('Magento\UrlRewrite\Model\UrlRewrite');
            $urlRewriteId = (int)$this->getRequest()->getParam('id', 0);
            if ($urlRewriteId) {
                $this->_urlRewrite->load($urlRewriteId);
            }
        }
        return $this->_urlRewrite;
    }
}