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

        if (!Mage::getStoreConfig('system/hhennes_productgridfilter/attributes_to_display'))
            return;

        //Si le block correspond à la grid d'affichage des produits  
        if ($block->getType() == 'adminhtml/catalog_product_grid') {

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
        
        if (!Mage::getStoreConfig('system/hhennes_productgridfilter/attributes_to_display'))
            return;

        //Si la collection est une collection Mage_Catalog_Model_Resource_Product_Collection on ajoute les valeurs de ces champs à la collection
        if (is_a($collection, 'Mage_Catalog_Model_Resource_Product_Collection')) {
            
            $fields = explode(',',Mage::getStoreConfig('system/hhennes_productgridfilter/attributes_to_display'));
            
            foreach ( $fields as $field)
                $collection->addAttributeToSelect($field); //Le champ reference peu être remplacé par l'attribut existant de votre choix 
        }
    }

}
