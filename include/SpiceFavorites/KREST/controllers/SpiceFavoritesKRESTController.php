<?php

namespace SpiceCRM\includes\SpiceFavorites\KREST\controllers;

class SpiceFavoritesKRESTController{

    static function getFavorites($req, $res, $args) {
        return $res->write(json_encode(\SpiceCRM\includes\SpiceFavorites\SpiceFavorites::getFavoritesRaw('', 0)));
    }

    static function addFavorite($req, $res, $args){
        \SpiceCRM\includes\SpiceFavorites\SpiceFavorites::set_favorite($args['module'], $args['id']);
        $bean = \BeanFactory::getBean($args['module'], $args['id']);
        return $res->write(json_encode(array(
            'module' => $args['module'],
            'id' => $args['id'],
            'summary_text' => $bean->get_summary_text()
        )));
    }

     static function deleteFavorite($req, $res, $args) {
        \SpiceCRM\includes\SpiceFavorites\SpiceFavorites::delete_favorite($args['module'], $args['id']);
        return $res->write(json_encode(array('status' => 'success')));
    }
}