<?php

namespace App\Providers;

use App\TicketCodeGenerator;
use App\Billing\PaymentGateway;
use App\InvitationCodeGenerator;
use App\HashIdsTicketCodeGenerator;
use App\Billing\StripePaymentGateway;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\OrderConfirmationNumberGenerator;
use App\RandomOrderConfirmationNumberGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) 
        {
            $this->app->register(DuskServiceProvider::class);
        }

        $this->app->bind(StripePaymentGateway::class, function() {
            return new StripePaymentGateway(config('services.stripe.secret'));
        });

        $this->app->bind(HashIdsTicketCodeGenerator::class, function() {
            return new HashIdsTicketCodeGenerator(config('app.ticket_code_salt'));
        });

        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
        $this->app->bind(OrderConfirmationNumberGenerator::class, RandomOrderConfirmationNumberGenerator::class);
        $this->app->bind(TicketCodeGenerator::class, HashIdsTicketCodeGenerator::class);
        $this->app->bind(InvitationCodeGenerator::class, RandomOrderConfirmationNumberGenerator::class);
    }
}
