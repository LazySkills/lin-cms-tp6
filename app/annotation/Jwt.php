<?php
declare (strict_types = 1);

namespace app\annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * class Jwt
 * @package app\annotation
 * @Annotation
 * @Target({"METHOD"})
 */
final class Jwt extends Annotation
{
}
