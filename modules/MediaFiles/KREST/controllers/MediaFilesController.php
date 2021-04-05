<?php
namespace SpiceCRM\modules\MediaFiles\KREST\controllers;

use SpiceCRM\modules\MediaFiles\MediaFile;
use SpiceCRM\data\BeanFactory;
use SpiceCRM\includes\database\DBManagerFactory;
use SpiceCRM\includes\ErrorHandlers\NotFoundException;
use SpiceCRM\KREST\handlers\ModuleHandler;

class MediaFilesController
{
    public function __construct() { }

    public function saveMediaFile( $req, $res, $args ) {
        $db = DBManagerFactory::getInstance();
        $moduleHandler = new ModuleHandler();

        $params = $req->getQueryParams();

        # if a category is provided
        if ( $params['category'][0] ) {
            # if the category is provided as guid
            if ( preg_match('#^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$#i', $params['category'] )) {
                # if the category doesn´t exist: set it to null
                if ( ! $db->getOne( sprintf(  'SELECT count(*) FROM mediacategories WHERE deleted = 0 AND id = "%s"', $db->quote( $params['category'] )))) {
                    $params['category'] = null;
                }
            } else {
                # if the category is provided as path like 'continents\europe\austria':
                # determine the id for the category and use it instead of the path.
                # if the path is wrong (one ore more categories doesn´t exist) set the category to null.
                $parentCategory = null;
                foreach ( explode('\\', $params['category'] ) as $v ) {
                    if ( $category = $db->fetchOne( sprintf(  'SELECT * FROM mediacategories WHERE deleted = 0 AND name = "%s" AND parent_id '.( !isset( $parentCategory[0]) ? 'IS NULL':'= "'.$parentCategory.'"' ), $db->quote( $v )))) {
                        $parentCategory = $category['id'];
                    } else break;
                }
                $params['category'] = $category ? $category['id']:null;
            }
        }

        $thisBean = $moduleHandler->add_bean( 'MediaFiles', $args['mediaId'], $params );

        return $res->withJson( $thisBean );
    }

    public function getMediaFile( $req, $res, $args ) {
        $seed = BeanFactory::getBean( 'MediaFiles', $args['mediaId'] );
        if ( $seed === false ) {
            throw ( new NotFoundException('Media file not found.'))->setLookedFor([ 'id' => $args['mediaId'], 'module' => 'MediaFiles' ]);
        }
        $seed->deliverOriginal();
    }
    public function getMediaFileBase64( $req, $res, $args ) {
        $seed = BeanFactory::getBean( 'MediaFiles', $args['mediaId'] );
        if ( $seed === false ) {
            throw ( new NotFoundException('Media file not found.'))->setLookedFor([ 'id' => $args['mediaId'], 'module' => 'MediaFiles' ]);
        }
        $base64 = $seed->deliverOriginalBase64();
        return $res->withJson(['img' => $base64, 'filetype' => $seed->filetype ]);
    }

    public function getThumbnail( $req, $res, $args ) {
        $thumbSize = $args['thumbSize'];
        $seed = BeanFactory::getBean( 'MediaFiles', $args['mediaId'] );
        if ( $seed === false ) {
            throw ( new NotFoundException('Media file not found.'))->setLookedFor([ 'id' => $args['mediaId'], 'module' => 'MediaFiles' ]);
        }
        if ( !isset( $seed->width[0]) or !isset( $seed->height[0]))
            list( $seed->width, $seed->height ) = getimagesize( MediaFile::getMediaPath( $seed->id ));
        if ( $thumbSize > $seed->width ) $thumbSize = $seed->width;
        $targetSize = $thumbSize;
        if ( ! MediaFile::widthExists( $targetSize, $seed->id ) ) {
            $bestSize = MediaFile::getBestThumbSize( $targetSize );
            if ( $targetSize != $bestSize ) {
                # Kepp this line! It might be needed later when caching will be implemented.
                # $app->redirectTo( 'th', array( 'id' => $mediaId, 'maxSize' => $bestSize ), $status = 302 );
                $seed->generateThumb( $bestSize );
                $seed->deliverThumb( $bestSize );
            } else {
                $seed->generateThumb( $targetSize );
                $seed->deliverThumb( $targetSize );
            }
        }
        $seed->deliverThumb( $targetSize );
    }

    public function getImageWithMaxWidth( $req, $res, $args ) {
        $seed = BeanFactory::getBean( 'MediaFiles', $args['mediaId'] );
        if ( $seed === false ) {
            throw ( new NotFoundException('Media file not found.'))->setLookedFor([ 'id' => $args['mediaId'], 'module' => 'MediaFiles' ]);
        }
        if ( !isset( $seed->width[0]) or !isset( $seed->height[0]))
            list( $seed->width, $seed->height ) = getimagesize( MediaFile::getMediaPath( $seed->id ));
        if ( $args['maxWidth'] >= $seed->width ) {
            $seed->deliverOriginal();
        } else {
            $targetWidth = $args['maxWidth'];
            if ( ! MediaFile::widthExists( $targetWidth, $seed->id )) {
                $bestWidth = MediaFile::getBestWidth( $targetWidth );
                if ( $bestWidth >= $seed->width ) {
                    $seed->deliverOriginal();
                } elseif ( $targetWidth != $bestWidth ) {
                    # Kepp this line! It might be needed later when caching will be implemented.
                    # $app->redirectTo( 'mw', array( 'id' => $mediaId, 'maxWidth' => $bestWidth ), $status = 302 );
                    #echo $bestWidth;
                    $seed->generateWidth( $bestWidth );
                    $seed->deliverSize( $bestWidth );
                } else {
                    $seed->generateWidth( $targetWidth );
                    $seed->deliverSize( $targetWidth );
                }
            } else {
                $seed->deliverSize( $targetWidth );
            }
        }
    }

    public function getImageWithMaxWidthAndHeight( $req, $res, $args ) {
        $seed = BeanFactory::getBean( 'MediaFiles', $args['mediaId'] );
        if ( $seed === false ) {
            throw ( new NotFoundException('Media file not found.'))->setLookedFor([ 'id' => $args['mediaId'], 'module' => 'MediaFiles' ]);
        }
        if ( !isset( $seed->width[0]) or !isset( $seed->height[0]))
            list( $seed->width, $seed->height ) = getimagesize( MediaFile::getMediaPath( $seed->id ));
        if ( $args['maxWidth'] >= $seed->width and $args['maxHeight'] >= $seed->height ) {
            $seed->deliverOriginal();
        } else {
            $widthRatio = $args['maxWidth']/$seed->width;
            $heightRatio = $args['maxHeight']/$seed->height;
            $ratio = $widthRatio < $heightRatio ? $widthRatio : $heightRatio;
            $targetWidth = round( $seed->width*$ratio );
            if ( ! MediaFile::widthExists( $targetWidth, $seed->id )) {
                $bestWidth = MediaFile::getBestWidth( $targetWidth );
                if ( $bestWidth >= $seed->width ) {
                    $seed->deliverOriginal();
                } elseif ( $targetWidth != $bestWidth ) {
                    # Kepp this line! It might be needed later when caching will be implemented.
                    # $app->redirectTo( 'mw', array( 'id' => $mediaId, 'maxWidth' => $bestWidth ), $status = 302 );
                    $seed->generateWidth( $bestWidth );
                    $seed->deliverSize( $bestWidth );
                } else {
                    $seed->generateWidth( $targetWidth );
                    $seed->deliverSize( $targetWidth );
                }
            } else {
                $seed->deliverSize( $targetWidth );
            }
        }
    }

}
