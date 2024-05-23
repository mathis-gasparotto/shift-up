<?php

namespace App\Service;

use App\Helper\EmailHelper;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * class EmailService
 * package App\Service
 */
class EmailService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $loggerEmail
    ) {
    }

    /**
     * @param string $to
     * @param string $type
     * @param array $data
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendEmailService(string $to, string $type, array $data = []): void
    {
        $templateId = EmailHelper::MAILJET_EMAILS[$type];
        $this->sendEmail($to, $templateId, EmailHelper::EMAIL_SUBJECTS[$type], $data);
    }

    /**
     * @param string $to
     * @param int $templateId
     * @param string $subject
     * @param array $data
     * @return void
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    private function sendEmail(string $to, int $templateId, string $subject, array $data = []): void
    {
        if (!$to) {
            $this->loggerEmail->error('[EmailService] Missing email');
            throw new Exception('Failed to send email (ES#01)');
        }

        // Create base Email
        $email = (new Email())
            ->from(new Address(EmailHelper::DEFAULT_EMAIL_ADDRESS, EmailHelper::DEFAULT_EMAIL_ADDRESS_NAME))
            ->replyTo(new Address(EmailHelper::DEFAULT_EMAIL_ADDRESS, EmailHelper::DEFAULT_EMAIL_ADDRESS_NAME))
            ->to($to)
            ->subject($subject)
            ->text('')
        ;

        // Add headers to the Email
        $email
            ->getHeaders()
            ->addTextHeader('X-MJ-TemplateID', $templateId) // teId
            ->addTextHeader('X-MJ-TemplateLanguage', true)
            ->addTextHeader('X-MJ-Vars', json_encode($data, JSON_UNESCAPED_UNICODE))
        ;

        // Send the email
        try {
            $this->mailer->send($email);
        } catch (TransportException $exception) {
            throw new Exception('Failed to send email (ES#02)', 500);
        }
    }
}
