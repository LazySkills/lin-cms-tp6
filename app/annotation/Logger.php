<?php
declare (strict_types = 1);

namespace app\annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * class Logger
 * @package app\annotation
 * @Annotation
 * @Target({"METHOD"})
 */
final class Logger extends Annotation
{
}
