<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\ResetPasswordNotification;
use App\Models\Book;
use App\Models\Country;
use App\Models\Wallet;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, CanResetPassword;

    /**
     * Champs autorisés en mass assignment
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'country_id',
        'city',
        'phone',
        'gender',
        'role_id',
        'is_writer',
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

    public function country()
    {
        return $this->belongsTo(Country::class);
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

    /**
     * Accès à l’espace auteur (écrivain ou admin publiant ses propres livres).
     */
    public function canAccessWriterSpace(): bool
    {
        return $this->isWriter() || $this->isAdmin();
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistBooks()
    {
        return $this->belongsToMany(Book::class, 'wishlists')->withTimestamps();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

}
