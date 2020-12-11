<?php

namespace SpiceCRM\includes\SpiceAttachments\KREST\controllers;

use SpiceCRM\includes\ErrorHandlers\ForbiddenException;

class SpiceAttachmentsKRESTController
{
    const UPLOAD_DESTINATION = 'upload://';

    /**
     * returns the list of attachments
     *
     * @param $req
     * @param $res
     * @param $args
     */
    public function getAttachments($req, $res, $args)
    {
        // try to load the seed and check if we have access.
        // It might happen that seed does not yet exists when atatchments are managed on new beans
        // so no exlicit check if the bean exists
        $seed = \BeanFactory::getBean($args['beanName'], $args['beanId']); //set encode to false to avoid things like ' being translated to &#039;
        if ($seed && !$seed->ACLAccess('view')) {
            throw (new ForbiddenException("not allowed to view this record"))->setErrorCode('noModuleView');
        }

        return $res->withJson(\SpiceCRM\includes\SpiceAttachments\SpiceAttachments::getAttachmentsForBean($args['beanName'], $args['beanId'], 100, false));
    }

    /**
     * returns the list of attachments
     *
     * @param $req
     * @param $res
     * @param $args
     */
    public function getAttachmentsCount($req, $res, $args)
    {
        // try to load the seed and check if we have access.
        // It might happen that seed does not yet exists when atatchments are managed on new beans
        // so no exlicit check if the bean exists
        $seed = \BeanFactory::getBean($args['beanName'], $args['beanId']); //set encode to false to avoid things like ' being translated to &#039;
        if ($seed && !$seed->ACLAccess('view')) {
            throw (new ForbiddenException("not allowed to view this record"))->setErrorCode('noModuleView');
        }

        return $res->withJson(['count' => \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::getAttachmentsCount($args['beanName'], $args['beanId'])]);
    }

    /**
     * saves an attachment
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function saveAttachment($req, $res, $args)
    {

        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        return $res->withJson(\SpiceCRM\includes\SpiceAttachments\SpiceAttachments::saveAttachmentHashFiles($args['beanName'], $args['beanId'], array_merge($postBody, $postParams)));
    }


    /**
     * saves attachments
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function saveAttachments($req, $res, $args)
    {
        // try to load the seed and check if we have access.
        // It might happen that seed does not yet exists when atatchments are managed on new beans
        // so no exlicit check if the bean exists
        $seed = \BeanFactory::getBean($args['beanName'], $args['beanId']); //set encode to false to avoid things like ' being translated to &#039;
        if ($seed && !$seed->ACLAccess('edit')) {
            throw (new ForbiddenException("not allowed to edit this record"))->setErrorCode('noModuleView');
        }

        $postBody = $body = $req->getParsedBody();
        $postParams = $_GET;
        return $res->withJson(\SpiceCRM\includes\SpiceAttachments\SpiceAttachments::saveAttachmentHashFiles($args['beanName'], $args['beanId'], array_merge($postBody, $postParams)));
    }


    /**
     * deletes an attachment
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function deleteAttachment($req, $res, $args)
    {
        // try to load the seed and check if we have access.
        // It might happen that seed does not yet exists when atatchments are managed on new beans
        // so no exlicit check if the bean exists
        $seed = \BeanFactory::getBean($args['beanName'], $args['beanId']); //set encode to false to avoid things like ' being translated to &#039;
        if ($seed && !$seed->ACLAccess('edit')) {
            throw (new ForbiddenException("not allowed to edit this record"))->setErrorCode('noModuleView');
        }

        return $res->withJson(\SpiceCRM\includes\SpiceAttachments\SpiceAttachments::deleteAttachment($args['attachmentId']));
    }


    /**
     * retrievs an attachment
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function getAttachment($req, $res, $args)
    {
        // try to load the seed and check if we have access.
        // It might happen that seed does not yet exists when atatchments are managed on new beans
        // so no exlicit check if the bean exists
        $seed = \BeanFactory::getBean($args['beanName'], $args['beanId']); //set encode to false to avoid things like ' being translated to &#039;
        if ($seed && !$seed->ACLAccess('view')) {
            throw (new ForbiddenException("not allowed to view this record"))->setErrorCode('noModuleView');
        }

        echo \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::getAttachment($args['attachmentId']);
    }

    /**
     * retrievs an attachment based ona  bean and a fieldname
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    public function getAttachmentForField($req, $res, $args)
    {
        // try to load the seed and check if we have access.
        // It might happen that seed does not yet exists when atatchments are managed on new beans
        // so no exlicit check if the bean exists
        $seed = \BeanFactory::getBean($args['beanName'], $args['beanId']); //set encode to false to avoid things like ' being translated to &#039;
        if ($seed && !$seed->ACLAccess('view')) {
            throw (new ForbiddenException("not allowed to view this record"))->setErrorCode('noModuleView');
        }

        if (!empty($seed->{$args['fieldprefix'] . '_md5'})) {
            if(file_exists(self::UPLOAD_DESTINATION . $seed->{$args['fieldprefix'] . '_md5'})) {
                $file = base64_encode(file_get_contents(self::UPLOAD_DESTINATION . $seed->{$args['fieldprefix'] . '_md5'}));
            } else {
                throw new \SpiceCRM\includes\ErrorHandlers\NotFoundException('attachment not found');
            }
        } else if (file_exists(self::UPLOAD_DESTINATION . $args['beanId'])) {
            $file = base64_encode(file_get_contents(self::UPLOAD_DESTINATION . $args['beanId']));
        } else {
            throw new \SpiceCRM\includes\ErrorHandlers\NotFoundException('attachment not found');
        }
        $attachment = [
            'filename' => $seed->{$args['fieldprefix'] . '_name'} ?: $seed->filename,
            'filesize' => $seed->{$args['fieldprefix'] . '_size'},
            'file_mime_type' => $seed->{$args['fieldprefix'] . '_mime_type'},
            'file' => $file,
            'filemd5' => $seed->{$args['fieldprefix'] . '_md5'}
        ];

        return $res->withJson($attachment);
    }

    /**
     * clones the attachments from one bean to another one
     *
     * @param $req
     * @param $res
     * @param $args
     */
    public function cloneAttachments($req, $res, $args)
    {
        $seed = \BeanFactory::getBean($args['fromBeanName'], $args['fromBeanId']); //set encode to false to avoid things like ' being translated to &#039;
        if (!$seed->ACLAccess('view')) {
            throw (new ForbiddenException("not allowed to edit this record"))->setErrorCode('noModuleView');
        }

        $clonedAttachments = \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::cloneAtatchmentsForBean($args['beanName'], $args['beanId'], $args['fromBeanName'], $args['fromBeanId']);
        return $res->withJson($clonedAttachments);
    }

    /**
     * analyses the entreis
     *
     * @param $req
     * @param $res
     * @param $args
     */
    public function getAnalysis($req, $res, $args)
    {
        return $res->withJson(\SpiceCRM\includes\SpiceAttachments\SpiceAttachments::getAnalysis());
    }

    /**
     * analyses the entreis
     *
     * @param $req
     * @param $res
     * @param $args
     */
    public function cleanErroneous($req, $res, $args)
    {
        return $res->withJson(['success' => \SpiceCRM\includes\SpiceAttachments\SpiceAttachments::cleanErroneous()]);
    }
}
