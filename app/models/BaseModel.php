<?php

use Awareness\Aware\Model;

class BaseModel extends Model {

    /********************************************************************
     * Core
     *******************************************************************/

    /**
     * Make sure the uniqueId is always unique
     *
     * @return string
     */
    public static function findExistingReferences($model)
    {
        $invalid = true;
        while ($invalid == true) {
            $uniqueString = Str::random(10);

            $existingReferences = $model::where('uniqueId', '=', $uniqueString)->count();

            if ($existingReferences == 0) {
                $invalid = false;
            }
        }

        return $uniqueString;
    }

    /**
     * Use the custom collection that allows tapping
     *
     * @return Utility_Collection[]
     */
    public function newCollection(array $models = array())
    {
        return new Utility_Collection($models);
    }

    /********************************************************************
     * Scopes
     *******************************************************************/
    /**
     * Order by created_at ascending scope
     *
     * @param array $query The current query to append to
     */
    public function scopeOrderByCreatedAsc($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Order by name ascending scope
     *
     * @param array $query The current query to append to
     */
    public function scopeOrderByNameAsc($query)
    {
        return $query->orderBy('name', 'asc');
    }

    /**
     * Get only active rows
     *
     * @param array $query The current query to append to
     */
    public function scopeActive($query)
    {
        return $query->where('activeFlag', 1);
    }

    /**
     * Get only inactive rows
     *
     * @param array $query The current query to append to
     */
    public function scopeInactive($query)
    {
        return $query->where('activeFlag', 0);
    }

    /********************************************************************
     * Getters and Setters
     *******************************************************************/
    /**
     * Get human readable created_at column
     *
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return date('F jS, Y \a\t h:ia', strtotime($value));
    }

    /**
     * Strip slashes from any name
     *
     * @return string
     */
    public function getNameAttribute($value)
    {
        return stripslashes($value);
    }

    /**
     * Allow id to be called
     *
     * @return int|string
     */
    public function getIdAttribute($value)
    {
        if (isset($this->uniqueId)) {
            return $this->uniqueId;
        }

        return $value;
    }

    /********************************************************************
     * Extra Methods
     *******************************************************************/

    public function getForumTypes()
    {
        $class = get_called_class();

        switch ($class) {
            case 'Forum_Post':
                return Forum_Post_Type::orderByNameAsc()->get();
            break;
            case 'Forum_Reply':
                return Forum_Reply_Type::orderByNameAsc()->get();
            break;
        }
    }

    public function setModeration($reason)
    {
        if ($this instanceof Forum_Post || $this instanceof Forum_Reply) {
            // Set this as locked for moderation
            $this->moderatorLockedFlag = 1;
            $this->save();

            // Create the moderation record
            $report                = new Forum_Moderation;
            $report->resource_type = get_called_class();
            $report->resource_id   = $this->id;
            $report->user_id       = Auth::user()->id;
            $report->reason        = $reason;

            $report->save();
        }
    }

    public function unsetModeration($moderationId)
    {
        if ($this instanceof Forum_Post || $this instanceof Forum_Reply) {
            $this->moderatorLockedFlag = 0;
            $this->save();

            // Create the moderation log
            $moderationLog                      = new Forum_Moderation_Log;
            $moderationLog->forum_moderation_id = $moderationId;
            $moderationLog->user_id             = Auth::user()->id;
            $moderationLog->action              = Forum_Moderation::REMOVE_REPORT;

            $moderationLog->save();
        }
    }

    public function setAdminReview($moderationId)
    {
        if ($this instanceof Forum_Post || $this instanceof Forum_Reply) {
            // Set the object for admin review
            $this->adminReviewFlag = 1;
            $this->save();

            // Create the moderation log
            $moderationLog                      = new Forum_Moderation_Log;
            $moderationLog->forum_moderation_id = $moderationId;
            $moderationLog->user_id             = Auth::user()->id;
            $moderationLog->action              = Forum_Moderation::ADMIN_REVIEW;

            $moderationLog->save();
        }
    }

    public function adminDeletePost($moderationId)
    {
        if ($this instanceof Forum_Post || $this instanceof Forum_Reply) {
            // Delete the object
            $this->delete();

            // Create the moderation log
            $moderationLog                      = new Forum_Moderation_Log;
            $moderationLog->forum_moderation_id = $moderationId;
            $moderationLog->user_id             = Auth::user()->id;
            $moderationLog->action              = Forum_Moderation::DELETE_POST;

            $moderationLog->save();
        }
    }
}