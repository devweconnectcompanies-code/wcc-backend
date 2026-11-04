<?php

namespace App\Models\Supports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $support_id
 * @property int $overall_rate
 * @property int $attention_rate
 * @property int $solution_rate
 * @property int $punctuality_rate
 * @property int|null $recommendation_rate
 * @property bool $resolved
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon $survey_datetime
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Supports\SupportModel|null $support
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereAttentionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereOverallRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel wherePunctualityRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereRecommendationRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereResolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereSolutionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereSupportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereSurveyDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportRatingModel withoutTrashed()
 * @mixin \Eloquent
 */
class SupportRatingModel extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'supports_ratings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'support_id',
        'overall_rate',
        'attention_rate',
        'solution_rate',
        'punctuality_rate',
        'recommendation_rate',
        'resolved',
        'comment',
        'survey_datetime'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'support_id' => 'integer',
        'overall_rate' => 'integer',
        'attention_rate' => 'integer',
        'solution_rate' => 'integer',
        'punctuality_rate' => 'integer',
        'recommendation_rate' => 'integer',
        'resolved' => 'boolean',
        'survey_datetime' => 'datetime:Y-m-d H:i:s'
    ];

    public function support(): BelongsTo
    {
        return $this->belongsTo(SupportModel::class, 'support_id', 'id');
    }
}
