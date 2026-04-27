<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    public function test_application_uses_systex_locale_and_timezone_defaults(): void
    {
        $this->assertSame('America/Sao_Paulo', config('app.timezone'));
        $this->assertSame('pt_BR', config('app.locale'));
        $this->assertSame('pt_BR', config('app.fallback_locale'));
    }

    public function test_environment_example_exposes_required_operational_flags(): void
    {
        $envExample = file_get_contents(base_path('.env.example'));

        $this->assertStringContainsString('APP_TIMEZONE=America/Sao_Paulo', $envExample);
        $this->assertStringContainsString('APP_PREVIOUS_KEYS=', $envExample);
        $this->assertStringContainsString('SESSION_SECURE_COOKIE=false', $envExample);
        $this->assertStringContainsString('SESSION_HTTP_ONLY=true', $envExample);
        $this->assertStringContainsString('SESSION_SAME_SITE=lax', $envExample);
    }

    public function test_production_checklist_documents_go_live_controls(): void
    {
        $checklist = file_get_contents(base_path('docs/production-checklist.md'));

        $this->assertStringContainsString('APP_DEBUG=false', $checklist);
        $this->assertStringContainsString('php artisan migrate --force', $checklist);
        $this->assertStringContainsString('php artisan test', $checklist);
        $this->assertStringContainsString('SESSION_SECURE_COOKIE=true', $checklist);
        $this->assertStringContainsString('backup', strtolower($checklist));
    }

    public function test_release_readiness_documents_internal_acceptance(): void
    {
        $readiness = file_get_contents(base_path('docs/release-readiness.md'));

        $this->assertStringContainsString('pronto para homologacao interna', $readiness);
        $this->assertStringContainsString('Admin com gestao de usuarios', $readiness);
        $this->assertStringContainsString('php artisan test', $readiness);
        $this->assertStringContainsString('demo.admin@systex.com.br', $readiness);
        $this->assertStringContainsString('Go para homologacao interna', $readiness);
    }
}
