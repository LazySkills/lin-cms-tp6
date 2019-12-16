<?php
declare (strict_types = 1);

namespace app\annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * class Auth
 * @package app\annotation
 * @Annotation
 * @Target({"METHOD"})
 */
final class Auth extends Annotation
{
    /** @var string */
    public $group;

    /**
     * @var string
     * @Enum({"false","true"})
     */
    public $hide;
}
