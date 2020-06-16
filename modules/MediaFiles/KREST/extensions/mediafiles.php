<?php

use SpiceCRM\modules\MediaFiles\KREST\controllers\MediaFilesController;

$app->group( '/module/MediaFiles', function() use ( $app ) {

    # $app->post( '/fileupload', [ new MediaFilesController(), 'fileUpload' ]);
    $app->get( '/{mediaId}/file', [ new MediaFilesController(), 'getMediaFile' ]);
    $app->get( '/{mediaId}/base64', [ new MediaFilesController(), 'getMediaFileBase64' ]);
    $app->get( '/{mediaId}/file/th/{thumbSize}', [ new MediaFilesController(), 'getThumbnail' ]);
    $app->get( '/{mediaId}/file/mw/{maxWidth}', [ new MediaFilesController(), 'getImageWithMaxWidth' ]);
    $app->get( '/{mediaId}/file/mwh/{maxWidth}/{maxHeight}', [ new MediaFilesController(), 'getImageWithMaxWidthAndHeight' ]);

} );
