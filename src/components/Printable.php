<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

declare(strict_types=1);

namespace manchenkov\yii\components;

use yii\db\ActiveRecordInterface;

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
    public function __toString(): string
    {
        $className = static::class;

        if ($this instanceof ActiveRecordInterface) {
            $data = [
                'attributes' => $this->getAttributes(),
                'errors' => $this->getErrors(),
                'isSaved' => $this->getIsNewRecord(),
            ];
        } else {
            $data = get_object_vars($this);
        }

        $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return "{$className}: {$data}" . PHP_EOL;
    }
}