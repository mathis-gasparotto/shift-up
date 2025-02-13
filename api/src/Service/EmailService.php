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
        $this->sendEmail($to, $type, EmailHelper::EMAIL_SUBJECTS[$type], $data);
    }

    /**
     * @param string $to
     * @param string $type
     * @param string $subject
     * @param array $data
     * @return void
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    private function sendEmail(string $to, string $type, string $subject, array $data = []): void
    {
        if (!$to) {
            $this->loggerEmail->error('[EmailService] Missing email');
            throw new Exception('Failed to send email (ES#01): Missing email');
        }

        $email = (new Email())
            ->from(new Address(EmailHelper::DEFAULT_EMAIL_ADDRESS, EmailHelper::DEFAULT_EMAIL_ADDRESS_NAME))
            ->replyTo(new Address(EmailHelper::DEFAULT_EMAIL_ADDRESS, EmailHelper::DEFAULT_EMAIL_ADDRESS_NAME))
            ->to($to)
            ->subject($subject)
            ->html(EmailHelper::getEmailContent($type, $data));

        try {
            $this->mailer->send($email);
        } catch (TransportException $exception) {
            throw new Exception('Failed to send email (ES#02): ' . $exception->getMessage(), 500);
        }
    }
}