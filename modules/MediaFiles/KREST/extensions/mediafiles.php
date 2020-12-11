<?php
use SpiceCRM\modules\MediaFiles\KREST\controllers\MediaFilesController;
use SpiceCRM\includes\RESTManager;
$RESTManager = RESTManager::getInstance();

$RESTManager->app->group( '/module/MediaFiles', function() {

    # $app->post( '/fileupload', [ new MediaFilesController(), 'fileUpload' ]);
    $this->get( '/{mediaId}/file', [ new MediaFilesController(), 'getMediaFile' ]);
    $this->get( '/{mediaId}/base64', [ new MediaFilesController(), 'getMediaFileBase64' ]);
    $this->get( '/{mediaId}/file/th/{thumbSize}', [ new MediaFilesController(), 'getThumbnail' ]);
    $this->get( '/{mediaId}/file/mw/{maxWidth}', [ new MediaFilesController(), 'getImageWithMaxWidth' ]);
    $this->get( '/{mediaId}/file/mwh/{maxWidth}/{maxHeight}', [ new MediaFilesController(), 'getImageWithMaxWidthAndHeight' ]);
} );
