<?php

class Hhennes_ProductGridFilter_Model_Observer {

    /**
     * Affichage la grille Magento 
     * On affiche une nouvelle colonne
     * @param Varien_Event_Observer $observer
     * @return type
     */
    public function onBlockHtmlBefore(Varien_Event_Observer $observer) {

        $block = $observer->getEvent()->getBlock();
        if (!isset($block))
            return;
        
        if ( $block->getType() == 'adminhtml/catalog_category_tab_product' && Mage::getStoreConfig('system/hhennes_productgridfilter/category_status_filter') == 1) {
                        
                //On ajoute les nouveaux champs après le champ SKU
                $block->addColumnAfter('status', array(
                    'header' => Mage::helper('hhennes_productgridfilter')->__('status'),
                    'align' => 'left',
                    'index' => 'status',
                    'type' => 'options',
                    'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
                    'width' => '70'
                    ),
                    'sku');
                $block->addColumnAfter('visibility', array(
                    'header' => Mage::helper('hhennes_productgridfilter')->__('visibility'),
                    'align' => 'left',
                    'index' => 'visibility',
                    'type' => 'options',
                    'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
                    'width' => '70'
                    ),
                    'sku');
                $block->addColumnAfter('type_id', array(
                    'header' => Mage::helper('hhennes_productgridfilter')->__('Type'),
                    'align' => 'left',
                    'index' => 'type_id',
                    'type' => 'options',
                    'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
                    'width' => '70'
                    ),
                    'sku');
        }

        if (!Mage::getStoreConfig('system/hhennes_productgridfilter/attributes_to_display'))
            return;

        //Si le block correspond à la grid d'affichage des produits  
        if ($block->getType() == 'adminhtml/catalog_product_grid' ) {

            $fields = explode(',', Mage::getStoreConfig('system/hhennes_productgridfilter/attributes_to_display'));
            
            foreach ($fields as $field) {
                
                //On ajoute les nouveaux champs après le champ SKU
                $block->addColumnAfter($field, array(
                    'header' => Mage::helper('hhennes_productgridfilter')->__($field),
                    'align' => 'left',
                    'index' => $field,
                    'width' => '70'), 'sku'); //sku peut etre remplacé par n'importe quel élément de votre grid
            }
        }
    }

    /**
     * Récupération de la référence produit au chargement de la collection
     * @param Varien_Event_Observer $observer
     * @return type
     */
    public function onEavLoadBefore(Varien_Event_Observer $observer) {

        $collection = $observer->getCollection();
        
        if (!isset($collection))
            return;
        
        //Affichage des filtres "visibilité" et "statut" dans le listing des produits des catégories
        if (   Mage::getStoreConfig('system/hhennes_productgridfilter/category_status_filter') == 1
                && is_a($collection, 'Mage_Catalog_Model_Resource_Product_Collection') 
                && Mage::app()->getRequest()->getControllerName() == 'catalog_category') {

            $collection->joinAttribute(
                    'status', 'catalog_product/status', 'entity_id', null, 'inner', Mage::app()->getRequest()->getParam('store')
            );
            $collection->joinAttribute(
                    'visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', Mage::app()->getRequest()->getParam('store')
            );
        }
        
        if (!Mage::getStoreConfig('system/hhennes_productgridfilter/attributes_to_display'))
            return;

        //Si la collection est une collection Mage_Catalog_Model_Resource_Product_Collection on ajoute les valeurs de ces champs à la collection
        if (is_a($collection, 'Mage_Catalog_Model_Resource_Product_Collection')) {

            $fields = explode(',', Mage::getStoreConfig('system/hhennes_productgridfilter/attributes_to_display'));

            foreach ($fields as $field)
                $collection->addAttributeToSelect($field); //Le champ reference peu être remplacé par l'attribut existant de votre choix 
        }
    }

}
