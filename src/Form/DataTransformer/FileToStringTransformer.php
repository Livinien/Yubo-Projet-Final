<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\File;

class FileToStringTransformer implements DataTransformerInterface
{
public function transform($file)
{
    if ($file instanceof File) {
        return $file->getPathname();
    }

    return null;
}

public function reverseTransform($path)
{
    if (null === $path) {
    return null;
}

return new File($path);
}
}