<?php

namespace Juanparati\InMobile\Test\Unit;

use Juanparati\InMobile\Services\BlacklistService;
use Juanparati\InMobile\Services\EmailService;
use Juanparati\InMobile\Services\EmailTemplateService;
use Juanparati\InMobile\Services\GdprService;
use Juanparati\InMobile\Services\ListService;
use Juanparati\InMobile\Services\RecipientService;
use Juanparati\InMobile\Services\SmsService;
use Juanparati\InMobile\Services\TemplateService;
use Juanparati\InMobile\Services\ToolService;

class FacadesTest extends InMobileTestBase
{
    public function testAllFacades()
    {
        $this->assertInstanceOf(ListService::class, \InMobile::lists());
        $this->assertInstanceOf(RecipientService::class, \InMobile::recipients());
        $this->assertInstanceOf(SmsService::class, \InMobile::sms());
        $this->assertInstanceOf(BlacklistService::class, \InMobile::blacklist());
        $this->assertInstanceOf(GdprService::class, \InMobile::gdpr());
        $this->assertInstanceOf(EmailService::class, \InMobile::email());
        $this->assertInstanceOf(ToolService::class, \InMobile::tool());
        $this->assertInstanceOf(TemplateService::class, \InMobile::templates());
        $this->assertInstanceOf(EmailTemplateService::class, \InMobile::emailTemplates());
    }
}
