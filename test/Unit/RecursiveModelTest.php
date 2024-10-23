<?php

namespace Juanparati\InMobile\Test\Unit;

use Juanparati\InMobile\InMobile;
use Juanparati\InMobile\Models\EmailMessage;
use Juanparati\InMobile\Models\EmailRecipient;
use Juanparati\InMobile\Models\Recipient;
use PHPUnit\Framework\TestCase;

class RecursiveModelTest extends TestCase
{
    public function testRecipientModel()
    {
        $recipient = (Recipient::make('45', '1234678'))
            ->setCreatedAt('2024-01-01 00:00:00Z');

        $recipientAsArray = $recipient->toArray();

        $this->assertEquals($recipient->getPhone(), $recipientAsArray['numberInfo']['phoneNumber']);
        $this->assertEquals($recipient->getCode(), $recipientAsArray['numberInfo']['countryCode']);

        $this->assertEquals(
            $recipient->getCreatedAt()->format(InMobile::DEFAULT_DATE_FORMAT),
            $recipientAsArray['externalCreated']
        );
    }

    public function testEmailMessageModel()
    {
        $msg = EmailMessage::make(
            EmailRecipient::make('test@example.net', 'test'),
            [EmailRecipient::make('dest@example.net', '')],
            'test',
            'testhtml'
        );

        $msgAsArray = $msg->toArray();

        $this->assertEquals($msg->getFrom()->getEmailAddress(), $msgAsArray['from']['emailAddress']);
        $this->assertEquals($msg->getTo()[0]->toArray(), $msgAsArray['to'][0]);
        $this->assertEquals($msg->getSendTime()->format(InMobile::DEFAULT_DATE_FORMAT), $msgAsArray['sendTime']);
    }

}
