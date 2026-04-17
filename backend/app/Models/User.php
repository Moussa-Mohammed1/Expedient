<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role_id',
        'localisation',
        'avatar',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

   
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function coach(): HasOne
    {
        return $this->hasOne(Coach::class);
    }

    public function trainee(): HasOne
    {
        return $this->hasOne(Trainee::class);
    }

    public function favoriteSalles(): BelongsToMany
    {
        return $this->belongsToMany(Salle::class, 'favorites', 'user_id', 'salle_id')
            ->withTimestamps();
    }

    public function scopeFilterByLocalisation(Builder $query, ?string $localisation): Builder
    {
        $localisation = strtolower(trim((string) $localisation));
        $words = array_filter(explode(' ', $localisation));

        return $query->when($words, function (Builder $query) use ($words) {
            $query->where(function (Builder $q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhereRaw('LOWER(localisation) LIKE ?', ["%$word%"]);
                }
            });
        });
    }

    public function scopeWithCoachData(Builder $query): Builder
    {
        return $query->with([
            'coach' => fn($coachQuery) => $coachQuery->withCount([
                'opinions as reviews_count' => fn($opinionQuery) => $opinionQuery->where('isApproved', true),
            ]),
            'coach.specialities',
            'role',
        ]);
    }

    public function scopeSearchWords(Builder $query, array $words): Builder
    {
        $words = array_values(array_filter($words, fn($word) => $word !== ''));

        return $query->when($words !== [], fn(Builder $q) => $q->where(
            fn(Builder $group) => collect($words)->each(
                fn(string $word) => $group
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$word}%"])
                    ->orWhereRaw('LOWER(localisation) LIKE ?', ["%{$word}%"])
                    ->orWhereHas('coach.salles', fn(Builder $salleQuery) => $salleQuery->whereRaw('LOWER(name) LIKE ?', ["%{$word}%"]))
            )
        ));
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function isAdmin() : bool
    {
        return $this->role->title === "admin";
    }

    public function isCoach()
    {
        return $this->coach;
    }
}
