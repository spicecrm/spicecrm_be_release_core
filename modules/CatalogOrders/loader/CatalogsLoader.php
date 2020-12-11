<?php

namespace SpiceCRM\modules\CatalogOrders\loader;

class CatalogsLoader
{

    public function loadCatalogs()
    {

        global $sugar_config;

        if(!$sugar_config['catalogorders']['productgroup_id']) return [];

        $group = \BeanFactory::getBean('ProductGroups', $sugar_config['catalogorders']['productgroup_id']);

        $products = [];
        $group->load_relationship('products');
        $relatedProducts = $group->get_linked_beans('products', 'Products', [], 0, 100);
        foreach ($relatedProducts as $relatedProduct) {
            $products[] = [
                'id' => $relatedProduct->id,
                'name' => html_entity_decode($relatedProduct->name, ENT_QUOTES),
                'product_status' => $relatedProduct->product_status,
                'external_id' => $relatedProduct->external_id
            ];
        }
        return $products;
    }
}
