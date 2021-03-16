<?php

declare(strict_types=1);

namespace manchenkov\yii\database;

use manchenkov\yii\collections\Collection;
use RuntimeException;
use Yii;

final class ActiveCollection extends Collection implements ActiveCollectionInterface
{
    public function save(): bool
    {
        Yii::$app->db->beginTransaction();

        try {
            foreach ($this->elements as $item) {
                if (!$item->save()) {
                    throw new RuntimeException(
                        sprintf('Unable to save record: %s', get_class($item))
                    );
                }
            }

            Yii::$app->db->transaction->commit();

            return true;
        } catch (RuntimeException $exception) {
            Yii::$app->db->transaction->rollBack();
        }

        return false;
    }

    public function delete(): bool
    {
        Yii::$app->db->beginTransaction();

        try {
            foreach ($this->elements as $item) {
                if (!$item->delete()) {
                    throw new RuntimeException(
                        sprintf('Unable to delete record: %s', get_class($item))
                    );
                }
            }

            Yii::$app->db->transaction->commit();

            return true;
        } catch (RuntimeException $exception) {
            Yii::$app->db->transaction->rollBack();
        }

        return false;
    }

    public function validate(): bool
    {
        $isValid = true;

        foreach ($this->elements as $item) {
            if (!$item->validate()) {
                $isValid = false;
            }
        }

        return $isValid;
    }

    public function errors(): array
    {
        $errors = [];

        foreach ($this->elements as $element) {
            if ($element->errors) {
                $errors[$element->id] = $element->errors;
            }
        }

        return $errors;
    }
}
