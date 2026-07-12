<?php

namespace App;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sofa\Eloquence\Eloquence;

/**
 * App\Models\Banner
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property int $type 1: Slider, 2: Popup
 * @property int $source 1: Website, 2: Application
 * @property int $status 1: Active, 2: Inactive
 * @property int $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $createdBy
 * @property-read string $image_url
 * @property-read \App\Models\Admin|null $updatedBy
 * @method static \Sofa\Eloquence\Builder|Banner active()
 * @method static \Sofa\Eloquence\Builder|Banner aggregate($function, array $columns = [])
 * @method static \Sofa\Eloquence\Builder|Banner application()
 * @method static \Sofa\Eloquence\Builder|Banner avg($column)
 * @method static \Sofa\Eloquence\Builder|Banner callParent($method, array $args)
 * @method static \Sofa\Eloquence\Builder|Banner count($columns = '*')
 * @method static \Sofa\Eloquence\Builder|Banner filterDate($date_range, $column = 'created_at')
 * @method static \Sofa\Eloquence\Builder|Banner getLikeOperator()
 * @method static \Sofa\Eloquence\Builder|Banner homeBannerOne()
 * @method static \Sofa\Eloquence\Builder|Banner homeBannerThree()
 * @method static \Sofa\Eloquence\Builder|Banner homeBannerTwo()
 * @method static \Sofa\Eloquence\Builder|Banner joinRelations($relations, $type = 'inner')
 * @method static \Sofa\Eloquence\Builder|Banner leftJoinRelations($relations)
 * @method static \Sofa\Eloquence\Builder|Banner lists($column, $key = null)
 * @method static \Sofa\Eloquence\Builder|Banner max($column)
 * @method static \Sofa\Eloquence\Builder|Banner min($column)
 * @method static \Sofa\Eloquence\Builder|Banner newModelQuery()
 * @method static \Sofa\Eloquence\Builder|Banner newQuery()
 * @method static \Sofa\Eloquence\Builder|Banner orWhereBetween($column, array $values)
 * @method static \Sofa\Eloquence\Builder|Banner orWhereIn($column, $values)
 * @method static \Sofa\Eloquence\Builder|Banner orWhereNotBetween($column, array $values)
 * @method static \Sofa\Eloquence\Builder|Banner orWhereNotIn($column, $values)
 * @method static \Sofa\Eloquence\Builder|Banner orWhereNotNull($column)
 * @method static \Sofa\Eloquence\Builder|Banner orWhereNull($column)
 * @method static \Sofa\Eloquence\Builder|Banner orderBy($column, $direction = 'asc')
 * @method static \Sofa\Eloquence\Builder|Banner popup()
 * @method static \Sofa\Eloquence\Builder|Banner prefixColumnsForJoin()
 * @method static \Sofa\Eloquence\Builder|Banner query()
 * @method static \Sofa\Eloquence\Builder|Banner rightJoinRelations($relations)
 * @method static \Sofa\Eloquence\Builder|Banner search($query, $columns = null, $fulltext = true, $threshold = null)
 * @method static \Sofa\Eloquence\Builder|Banner select($columns = [])
 * @method static \Sofa\Eloquence\Builder|Banner setJoinerFactory(\Sofa\Eloquence\Contracts\Relations\JoinerFactory $factory)
 * @method static \Sofa\Eloquence\Builder|Banner setParserFactory(\Sofa\Eloquence\Contracts\Searchable\ParserFactory $factory)
 * @method static \Sofa\Eloquence\Builder|Banner slider()
 * @method static \Sofa\Eloquence\Builder|Banner sum($column)
 * @method static \Sofa\Eloquence\Builder|Banner userBanner()
 * @method static \Sofa\Eloquence\Builder|Banner website()
 * @method static \Sofa\Eloquence\Builder|Banner whereBetween($column, array $values, $boolean = 'and', $not = false)
 * @method static \Sofa\Eloquence\Builder|Banner whereCreatedAt($value)
 * @method static \Sofa\Eloquence\Builder|Banner whereCreatedBy($value)
 * @method static \Sofa\Eloquence\Builder|Banner whereDate($column, $operator, $value, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|Banner whereDay($column, $operator, $value, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|Banner whereExists(\Closure $callback, $boolean = 'and', $not = false)
 * @method static \Sofa\Eloquence\Builder|Banner whereId($value)
 * @method static \Sofa\Eloquence\Builder|Banner whereImage($value)
 * @method static \Sofa\Eloquence\Builder|Banner whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static \Sofa\Eloquence\Builder|Banner whereMonth($column, $operator, $value, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|Banner whereName($value)
 * @method static \Sofa\Eloquence\Builder|Banner whereNotBetween($column, array $values, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|Banner whereNotIn($column, $values, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|Banner whereNotNull($column, $boolean = 'and')
 * @method static \Sofa\Eloquence\Builder|Banner whereNull($column, $boolean = 'and', $not = false)
 * @method static \Sofa\Eloquence\Builder|Banner whereSource($value)
 * @method static \Sofa\Eloquence\Builder|Banner whereStatus($value)
 * @method static \Sofa\Eloquence\Builder|Banner whereType($value)
 * @method static \Sofa\Eloquence\Builder|Banner whereUpdatedAt($value)
 * @method static \Sofa\Eloquence\Builder|Banner whereUpdatedBy($value)
 * @method static \Sofa\Eloquence\Builder|Banner whereYear($column, $operator, $value, $boolean = 'and')
 * @mixin \Eloquent
 */
class Banner extends Model
{
    use Eloquence;

    const BANNER = 1, POPUP = 2;//type
    const WEBSITE = 1 , HOMEBANNERONE = 2 , HOMEBANNERTWO = 3 , HOMEBANNERTHREE = 4, USERBANNER = 5;//source
    const ACTIVE = 1, INACTIVE = 2;//status

    protected $appends = [
        'image_url'
    ];


    protected $fillable = [
        'name' , 'image', 'type', 'source', 'status', 'created_by', 'updated_by'
    ];

    public static function getSource($id = null)
    {
        $source = [
            1 => 'Web-Home Slider',
            2 => 'Web-Home Banner 1',
            3 => 'Web-Home Banner 2',
            4 => 'Web-Home Banner 3',
            5 => 'User-Home Banner',
        ];

        return $id ? $source[$id] : $source;
    }


    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return env('BANNER_IMAGE_URL') . $this->image;
        }
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class,'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class,'updated_by', 'id');
    }

    public function scopeActive($q)
    {
        return $q->where('status',self::ACTIVE);
    }

    public function scopeSlider($q)
    {
        return $q->where('type',self::BANNER);
    }

    public function scopePopup($q)
    {
        return $q->where('type',self::POPUP);
    }

    public function scopeWebsite($q)
    {
        return $q->where('source',self::WEBSITE);
    }

    public function scopeUserBanner($q)
    {
        return $q->where('source',self::USERBANNER);
    }

    public function scopeHomeBannerOne($q)
    {
        return $q->where('source',self::HOMEBANNERONE);
    }
    public function scopeHomeBannerTwo($q)
    {
        return $q->where('source',self::HOMEBANNERTWO);
    }

    public function scopeHomeBannerThree($q)
    {
        return $q->where('source',self::HOMEBANNERTHREE);
    }

    public function scopeApplication($q)
    {
        return $q->where('source',self::APPLICATION);
    }
}
