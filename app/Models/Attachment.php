<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Nonstandard\Uuid;

/**
 * App\Models\Attachment
 *
 * @property int $id
 * @property string $file_name
 * @property int $note_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $file_original_name
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newQuery()
 * @method static \Illuminate\Database\Query\Builder|Attachment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Attachment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Attachment withoutTrashed()
 * @mixin \Eloquent
 */
class Attachment extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'file_name',
        'file_original_name',
        'ext',
        'note_id'
    ];

    public function attachment_note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public static function saveFile(UploadedFile $file): string
    {
        $fileName = self::makeUniqueName($file->getClientOriginalExtension());
        $file->storeAs('attachments/', $fileName);
        return $fileName;
    }

    /**
     * @param $ext
     * @return string
     */
    public static function makeUniqueName($ext): string
    {
        do {
            $file_name = Uuid::uuid4() . '.' . $ext;
        } while (self::query()->where('file_name', $file_name)->exists());
        return $file_name;
    }

    public function deleteFile(): void
    {
        $this->delete();
        $path = 'attachments/' . $this->file_name;
        Storage::delete($path);
    }

    /**
     * @return string
     */
    public function filePath(): string
    {
        return public_path('storage/attachments/' . $this->file_name);
    }

}
