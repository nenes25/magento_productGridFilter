<?php

/**
 * Enter File description
 *
 * @author Europe-internet <dev@europe-internet.net>
 * @version 0.1 | $Revision$
 * Last-Modified : $Date$
 * Id : $Id$
 */
class Hhennes_ProductGridFilter_Model_Source_Attributes {
    
    public function toOptionArray() {
    	$attributes = Mage::getSingleton('catalog/convert_parser_product')->getExternalAttributes();
        $attributeList = array();
        
        foreach( $attributes as $attribute)
            $attributeList[] = array ('label' => $attribute, 'value' => $attribute);
        
        return $attributeList;        
    }
    
}

?>
