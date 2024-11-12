<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Transformers\UserTransformer;
use Laravel\Passport\HasApiTokens as PassportHasApiTokens;

class User extends Authenticatable
{
    use PassportHasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMIN = 'true';
    const USUARIO_REGULAR = 'false';

    protected $table = 'users';

    public $transformer = UserTransformer::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        //'verification_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* Obligamos al atributo name, en el caso de set, siempre en minusculas y al usar get, solo con la primera en mayusculas*/
    protected function name(): Attribute{
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    protected function email(): Attribute{
        return Attribute::make(
            set: fn (string $value) => strtolower($value),
        );
    }

    public function esVerificado(){
        Log::info("Function esVerificado: User Verified ".$this->verified);
        return $this->verified == User::USUARIO_VERIFICADO;
    }

    public function esAdministrador(){
        return $this->admin == User::USUARIO_ADMIN;
    }

    public static function generarVerificationToken(){
        return Str::random(40);
    }
}
