<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\WeekPubliction
 *
 * @property int $publication_id
 * @property string|null $齐呈祥
 * @property string|null $周菁涛
 * @property string|null $肖金
 * @property string|null $曹茳
 * @property string|null $高树韬
 * @property string|null $张芊
 * @property string $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction wherePublicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction where周菁涛($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction where张芊($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction where曹茳($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction where肖金($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction where高树韬($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction where齐呈祥($value)
 * @mixin \Eloquent
 * @property string|null $period
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPubliction wherePeriod($value)
 */
class WeekPubliction extends Model
{
    public $table="week_publication";
    public $primaryKey="publication_id";
    public $fillable=["period"];
    public $timestamps=false;
}
