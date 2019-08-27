<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace manchenkov\yii\mail;

/**
 * Interface Mailable to easily create new Mail classes
 */
interface Mailable
{
    /**
     * Returns a prepared data to compose mail view (use in `send()` method)
     * @return array
     */
    public function data(): array;

    /**
     * Returns a sender email with name
     * @return array
     */
    public function from(): array;

    /**
     * Returns a receiver email
     * @return string
     */
    public function to(): string;

    /**
     * Returns a subject string value
     * @return string
     */
    public function subject(): string;

    /**
     * Returns a view template name
     * @return string
     */
    public function view(): string;
}