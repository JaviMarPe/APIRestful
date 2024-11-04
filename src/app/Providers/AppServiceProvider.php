<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        //Este sera el tamaño por defecto de los string debido a la cantida maxima que permite la bbdd
        Schema::defaultStringLength(191);

        //Eventos que saltan cuando se crea un usuario: mandar un email de verificacion, ...
        User::created(function($user){
            Log::info('Salta el boot para crear email para el usuario = '.json_encode($user));
            retry(5, function() use ($user){
                Mail::to($user)->send(new UserCreated($user));
            }, 100);
        });

        //Eventos que saltan cuando se actualiza un usuario: mandar un email de verificacion, ...
        User::updated(function($user){
            //En el caso de que se modifique el email
            if($user->isDirty('email') && $user->verified == User::USUARIO_NO_VERIFICADO){
                retry(5, function() use ($user){
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 100);
            }
        });

        Product::updated(function($product){
            if($product->quantity == 0 && $product->estaDisponible()){
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;
                $product->save();
            }
        });
    }
}
