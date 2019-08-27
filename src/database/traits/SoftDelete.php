<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\database\traits;

use manchenkov\yii\database\ActiveRecord;

/**
 * Trait SoftDelete for safe deleting model records (deleted_at timestamp)
 *
 * @property int $deleted_at
 * @property-read bool $isDeleted
 *
 * @mixin ActiveRecord
 */
trait SoftDelete
{
    /**
     * @return bool Current deleted status
     */
    public function getIsDeleted()
    {
        return !is_null($this->deleted_at);
    }

    /**
     * Restore record from deleted
     * @return bool
     */
    public function restore()
    {
        $this->deleted_at = null;

        return $this->save();
    }

    /**
     * Full removing record
     */
    public function destroy()
    {
        parent::delete();
    }

    /**
     * Soft delete record
     * @return bool
     */
    public function delete()
    {
        $this->deleted_at = time();

        return $this->save();
    }
}