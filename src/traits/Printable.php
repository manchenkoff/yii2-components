<?php

declare(strict_types=1);

namespace manchenkov\yii\traits;

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
     * @noinspection JsonEncodingApiUsageInspection
     */
    public function __toString(): string
    {
        $className = static::class;

        if ($this instanceof ActiveRecordInterface) {
            $data = [
                'attributes' => $this->getAttributes(),
                'errors' => $this->getErrors(),
                'isNewRecord' => $this->getIsNewRecord(),
            ];
        } else {
            $data = get_object_vars($this);
        }

        $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return "{$className}: {$data}" . PHP_EOL;
    }
}
