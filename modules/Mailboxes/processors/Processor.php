<?php
namespace SpiceCRM\modules\Mailboxes\processors;

abstract class Processor {
    public $email;

    public function __construct(\Email $email) {
        $this->email = $email;
    }
}