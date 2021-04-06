<?php
    namespace App\Providers;
    use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Cookie;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\RateLimiter;
    use Request;

    class RouteServiceProvider extends ServiceProvider {
        protected $namespace = 'App\Application';

        public function boot() {
            App::setLocale(Cookie::get('lang', 'zh-CN'));
            parent::boot();
            // $this->configureRateLimiting();
        }

        public function map() {
            $this->mapWebRoutes();
            $this->mapApiRoutes();
        }

        protected function mapWebRoutes() {
            Route::middleware('web')
            ->namespace($this->namespace . '\Web')
            ->group(base_path('routes/web.php'));
        }

        protected function mapApiRoutes() {
            Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace . '\Api\Controllers')
            ->group(base_path('routes/api.php'));
        }

        /**
         * Configure the rate limiters for the application.
         *
         * @return void
         */
        protected function configureRateLimiting()
        {
            RateLimiter::for('api', function (Request $request) {
                return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
            });
        }
    }
?>