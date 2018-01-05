<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Favorite;

class Post extends Model
{
    use Sluggable;

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    protected $fillable = ['title','content','date','description'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function tags()
    {
        return $this->belongsToMany(
            'App\Tag',
            'posts_tags',
            'post_id',
            'tag_id'
        );
    }

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id = Auth::user()->id;
        $post->save();

        return $post;
    }

    /**
     * edit post
     * @param $fields
     */
    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    /**
     * remove post
     * @throws \Exception
     */
    public function remove()
    {
        $this->removeImage();
        $this->delete();
    }

    /**
     * upload img in uploads dir
     * @param $image
     */
    public function uploadImage($image)
    {
        if($image == null) { return; }
        $this->removeImage();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    /**
     * get image
     * @return string
     */
    public function getImage()
    {
        if($this->image == null)
        {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->image;

    }

    /**
     * remove img from uploads dir
     */
    public function removeImage()
    {
        if($this->image != null)
        {
            Storage::delete('uploads/' . $this->image);
        }
    }

    /**
     * set category in post
     * @param $id\
     */
    public function setCategory($id)
    {
        if($id == null) {return;}
        $this->category_id = $id;
        $this->save();
    }

    /**
     * set tag for post
     * @param $id
     */
    public function setTags($ids)
    {
        if($ids == null){return;}

        $this->tags()->sync($ids);
    }


    /**
     * set no visible post
     */
    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    /**
     * set visible post
     */
    public function setPublic()
    {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    /**
     * check status for visible post
     * @param $value
     */
    public function toggleStatus($value)
    {
        if($value == null)
        {
            return $this->setDraft();
        }

        return $this->setPublic();
    }

    /**
     * set recommendations
     */
    public function setFeatured()
    {
        $this->is_featured = Post::IS_PUBLIC;
        $this->save();
    }

    /**
     * unset recommendations
     */
    public function setStandart()
    {
        $this->is_featured = Post::IS_DRAFT;
        $this->save();
    }

    /**
     * check status for recommendations
     * @param $value
     */
    public function toggleFeatured($value)
    {
        if($value == null)
        {
            return $this->setStandart();
        }

        return $this->setFeatured();
    }

    /**
     * Determine whether a post has been marked as favorite by a user.
     *
     * @return boolean
     */
    public function favorited()
    {
        return (bool) Favorite::where('user_id', Auth::id())
            ->where('post_id', $this->id)
            ->first();

    }

    public function setDateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
        $this->attributes['date'] = $date;
    }

    public function getDateAttribute($value)
    {
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');

        return $date;
    }

    public function getCategoryTitle()
    {
        return ($this->category != null)
            ?   $this->category->title
            :   'Нет категории';
    }

    public function getTagsTitles()
    {
        return (!$this->tags->isEmpty())
            ?   implode(', ', $this->tags->pluck('title')->all())
            : 'Нет тегов';
    }

    public function getCategoryID()
    {
        return $this->category != null ? $this->category->id : null;
    }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }

    /**
     * Get id post
     *
     * @return mixed
     */
    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevious()
    {
        $postID = $this->hasPrevious(); //ID
        return self::find($postID);
    }

    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNext()
    {
        $postID = $this->hasNext();
        return self::find($postID);
    }

    /**
     * all post exept this post in carousel
     * @return static
     */
    public function related()
    {
        return self::all()->except($this->id);
    }


    public function hasCategory()
    {
        return $this->category != null ? true : false;
    }

    public static function getPopularPosts()
    {
        return self::orderBy('views','desc')->take(3)->get();
    }

}
