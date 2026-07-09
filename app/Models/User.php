<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;
use App\Models\Book;
use App\Models\Wallet;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Champs autorisés en mass assignment
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'country',
        'phone',
        'gender',
        'role_id',
        'avatar',
        'bio',
        'password',
    ];

    /**
     * Casts automatiques
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Helper: check role
     */
    public function isReader(): bool
    {
        return $this->role?->name === 'reader';
    }

    public function isWriter(): bool
    {
        return $this->role?->name === 'writer';
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function socialProfile()
    {
        return $this->hasOne(SocialProfile::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            if(
                $user->role->name == 'writer' ||
                $user->role->name == 'admin'
            ){
                Wallet::create([
                    'user_id'=>$user->id,
                    'balance'=>0,
                    'currency'=>'USD'
                ]);

            }

        });
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
