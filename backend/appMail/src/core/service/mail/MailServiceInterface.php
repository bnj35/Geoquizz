<?php

namespace geoquizz\core\service\mail;

interface MailServiceInterface {
    public function sendEmail($recipientEmail, $subject, $details);
}
