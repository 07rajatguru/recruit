<?php

namespace App\Commentable;

//use App\Attachment\HasAttachments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use BrianFaust\Likeable\Interfaces\HasLikes;
use Kalnoy\Nestedset\NodeTrait;

class Comment extends Model /*implements HasLikes*/
{
    /*use HasLikesTrait;*/
    use NodeTrait;
    //use HasAttachments;

    public $table = "comments";
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * @return mixed
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function creator()
    {
        return $this->morphTo('creator')->getResults();
    }

    /**
     * @param Model $commentable
     * @param $data
     * @param Model $creator
     *
     * @return static
     */
    public function createComment(Model $commentable, $data, Model $creator)
    {
        $comment = new static();
        $comment->fill(array_merge($data, [
            'creator_id' => $creator->id,
            'creator_type' => get_class($creator),
        ]));

        return $commentable->comments()->save($comment) ? $comment : false;
    }

    /**
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function updateComment($id, $data)
    {
        echo "here";exit;
        /*$comment = static::find($id);
        $comment->update($data);

        return $comment;*/
        $response = static::find($id)->update(['body' => $data]);
        return $response;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public static function deleteComment($id)
    {
        return static::find($id)->delete();
    }
}
