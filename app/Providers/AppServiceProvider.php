<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('money', function ($amount) {
            $currency=get_currency_symbol(session('store_currency'));
            return "<?php echo '$currency'.number_format($amount, 2); ?>";
        });

        Blade::directive('money_decimal', function ($amount) {
            $currency=get_currency_symbol(session('store_currency'));
            return "<?php echo '$currency'.number_format($amount); ?>";
        });

        Blade::directive('money_decimal_one', function ($amount) {
            $currency=get_currency_symbol(session('store_currency'));
            return "<?php echo '$currency'.number_format($amount,1); ?>";
        });

        Blade::directive('number', function ($amount) {
            return "<?php echo number_format($amount, 0); ?>";
        });

        Blade::directive('number_decimal', function ($amount) {
            return "<?php echo number_format($amount, 1); ?>";
        });

         Blade::directive('number_decimal_2', function ($amount) {
            return "<?php echo number_format($amount, 2); ?>";
        });

        Blade::directive('perchanges', function ($amount) {

            return "<?php echo ($amount > 0) ? '<span class=green>+'.round($amount).'%</span>' : '<span class=red>'.round($amount).'%</span>'; ?>";
        });
        
        Blade::directive('perchanges_avg', function ($amount) {

            return "<?php echo ($amount > 0) ? '<span class=green>+'.round($amount,1).'%</span>' :  ((($amount < 0)) ? '<span class=red>'.round($amount,1).'%</span>' : '<span>'.round($amount,1).'%</span>'); ?>";
        });

        Blade::directive('perchanges_avg_revenue', function ($amount) {

            return "<?php ($amount == 0) ? '<span class=>+'.round($amount,1).'%</span>' : (($amount > 0) ? '<span class=postive>+'.round($amount,1).'%</span>' : '<span class=negative>'.round($amount,1).'%</span>'); ?>";
        });

        Blade::directive('datetime', function ($date) {
            return "<?php echo date('F j, Y \a\\t H:i A', strtotime($date)); ?>";
        });


    }
}
