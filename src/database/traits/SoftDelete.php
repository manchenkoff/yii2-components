<?php

declare(strict_types=1);

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
    public function getIsDeleted(): bool
    {
        return $this->deleted_at !== null;
    }

    /**
     * Restore record from deleted
     * @return bool
     */
    public function restore(): bool
    {
        $this->deleted_at = null;

        return $this->save();
    }

    /**
     * Full removing record
     *
     * @return int|bool
     */
    public function destroy()
    {
        return parent::delete();
    }

    /**
     * Soft delete record
     * @return bool
     */
    public function delete(): bool
    {
        $this->deleted_at = time();

        return $this->save();
    }
}
