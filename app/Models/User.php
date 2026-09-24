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
        'is_main_admin',
        'is_writer',
        'avatar',
        'bio',
        'password',
    ];

    protected $casts = [
        'is_main_admin' => 'boolean',
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

    public function isMainAdmin(): bool
    {
        return $this->isAdmin() && $this->is_main_admin;
    }

    public function authorAccountUserId(): int
    {
        if ($this->isMainAdmin()) {
            return $this->id;
        }

        $mainAdminId = User::query()->where('is_main_admin', true)->value('id');

        abort_unless($mainAdminId, 500, 'Le compte administrateur principal n’est pas configuré.');

        return $mainAdminId;
    }

    public function adminRoles()
    {
        return $this->belongsToMany(Role::class, 'user_admin_role');
    }

    public function hasAdminPermission(string $permission): bool
    {
        if (!$this->isAdmin()) {
            return false;
        }

        return $this->adminRoles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    public function adminLandingRoute(): ?string
{
    if (!$this->isAdmin()) {
        return null;
    }

    if ($this->hasAdminPermission('dashboard.view')) {
        return route('admin.dashboard');
    }

    if ($this->hasAdminPermission('books.view')) {
        return route('admin.books.all');
    }

    if ($this->hasAdminPermission('editorial.view')) {
        return route('admin.books.editorial.queue');
    }

    if ($this->hasAdminPermission('categories.view')) {
        return route('admin.categories.index');
    }

    if ($this->hasAdminPermission('users.view')) {
        return route('admin.users');
    }

    if ($this->hasAdminPermission('author_books.view')) {
        return route('admin.books.index');
    }

    if ($this->hasAdminPermission('sponsorship_plans.view')) {
        return route('admin.sponsorship-plans.index');
    }

    if ($this->hasAdminPermission('sponsorships.view')) {
        return route('admin.sponsorships.index');
    }

    if ($this->hasAdminPermission('notifications.view')) {
        return route('admin.author.activities');
    }

    if ($this->hasAdminPermission('platform_wallet.view')) {
        return route('admin.platform-wallet');
    }

    if ($this->hasAdminPermission('withdrawals.view')) {
        return route('admin.withdrawals.index');
    }

    if (
        $this->hasAdminPermission('settings.commerce.view') ||
        $this->hasAdminPermission('settings.mobile_money.view') ||
        $this->hasAdminPermission('settings.profile.view') ||
        $this->hasAdminPermission('settings.security.view')
    ) {
        return route('admin.settings');
    }

    if ($this->hasAdminPermission('roles.view')) {
        return route('admin.roles.index');
    }

    return null;
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
                    'currency'=>'EUR'
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
