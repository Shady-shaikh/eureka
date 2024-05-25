<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
// use Request;
use Illuminate\Http\Request;

class CheckForMaintenanceMode
{
    protected $app;

    public function __construct(Application $app)
    {
        // Artisan::call('up');
        // dd($app);
        $this->app = $app;
    }

    public function handle($request, Closure $next)
    {
        // dd($request);
        // dd($this->app->isDownForMaintenance());
        // dd($this->isBackendRequest($request));
        if ($this->app->isDownForMaintenance() && !$this->isBackendRequest($request)) {
            $data = json_decode(file_get_contents($this->app->storagePath() . '/framework/down'), true);
			// dd($data);
            throw new MaintenanceModeException(5, 5, "under construction");
            // throw new MaintenanceModeException($data['time'], $data['retry'], $data['message']);
        }

        return $next($request);
    }

    private function isBackendRequest($request)
    {
// 		dd($request);
		// dd(Request::is('admin/*'));
// 		dd($request->is('admin/*'));
        return ($request->is('admin') or $request->is('admin/*') );
    }
}