<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Models\Documento;
use App\Policies\DocumentoPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Documento::class, DocumentoPolicy::class);

        $this->events();
        $this->setupLogViewer();
        $this->configModels();
        $this->configCommands();
        $this->configUrls();
    }

    private function events(): void
    {
        //
    }

    /**
     * Sets up the LogViewer authentication to restrict access
     * based on whether the authenticated user is an
     */
    private function setupLogViewer(): void
    {
        LogViewer::auth(fn ($request) => $request->user()?->hasRole('Master'));
    }

    /**
     * Configura modelos Eloquent desabilitando o requisito de definição
     * da propriedade preenchível e aplicando uma verificação rigorosa para garantir que
     * todas as propriedades acessadas existam no modelo.
     */
    private function configModels(): void
    {
        // --
        // Remova a necessidade da propriedade fillable em cada modelo
        Model::unguard();

        // --
        // Certifique-se de que todas as propriedades chamadas existam no modelo
        Model::shouldBeStrict();

        Model::preventLazyLoading(! $this->app->isProduction());
    }

    /**
     * Configura comandos de banco de dados para proibir a execução de instruções destrutivas
     * quando o aplicativo estiver sendo executado em um ambiente de produção.
     */
    private function configCommands(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    /**
     * Configura os URLs do aplicativo para impor o protocolo HTTPS para todas as rotas.
     */
    private function configUrls(): void
    {
        URL::forceHttps($this->app->isProduction());
    }

    /**
     * Configura o aplicativo para usar CarbonImmutable para manipulação de data e hora.
     */
    private function configDate(): void
    {
        Date::use(CarbonImmutable::class);
    }
}
