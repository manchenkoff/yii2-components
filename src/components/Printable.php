<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\components;

use yii\db\BaseActiveRecord;

/**
 * Trait Printable for overload __toString method
 * @package manchenkov\yii\components
 */
trait Printable
{
    /**
     * Returns object presentation as a string
     * @return string
     */
    public function __toString()
    {
        $className = static::class;

        if ($this instanceof BaseActiveRecord) {
            $data = [
                'attributes' => $this->attributes,
                'errors' => $this->errors,
                'isSaved' => $this->isNewRecord,
            ];
        } else {
            $data = get_object_vars($this);
        }

        $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return "{$className}: {$data}" . PHP_EOL;
    }
}