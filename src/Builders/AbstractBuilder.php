<?php

namespace App\Builders;

abstract class AbstractBuilder
{
    /**
     * Is not possible to instantiate builders by using the constructor.
     * Instead to gain time we use shortcuts such as:
     *
     * $link = RelatedLinkBuilder::externalLink()->build()
     */
    protected function __construct()
    {
    }
}
