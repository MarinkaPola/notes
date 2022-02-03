<?php

namespace App\Models;

use App\Notifications\GetAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Note
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property int $user_id
 * @property int $visibility
 * @property string|null $uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attachment[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $noteUsers
 * @property-read int|null $note_users_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\NoteFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Query\Builder|Note onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereVisibility($value)
 * @method static \Illuminate\Database\Query\Builder|Note withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Note withoutTrashed()
 * @mixin \Eloquent
 */
class Note extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'text',
        'user_id',
        'visibility',
        'uuid'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function note_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->visibility === 1;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasAuthor(User $user): bool
    {
       return $this->user_id === $user->id;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function sharedWith(User $user): bool
    {
       return $this->note_users()->where('id', $user->id)->exists();
    }

    /**
     * @param User $targetUser
     */
    public function share(User $targetUser): void
    {
        $this->note_users()->attach($targetUser->id);
        $targetUser->notify(new GetAccess($this));
    }
}
