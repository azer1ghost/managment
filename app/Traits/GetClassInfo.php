<?php

namespace App\Traits;

trait GetClassInfo
{
    protected string $className;
    protected string $classNamePlural;
    protected string $classNameSingular;

    public function getClassShortName($type = null): string
    {
        $this->className = strtolower(
            array_values(
                array_filter(
                    preg_split('/(?=[A-Z])/',
                        (new \ReflectionClass(get_called_class()))->getShortName()
                    )
                )
            )[0]
        );

        $this->classNamePlural = \Str::plural($this->className);
        $this->classNameSingular = \Str::singular($this->className);

        if ($type == 'p')
        {
            return $this->classNamePlural;
        }
        elseif ($type == 's')
        {
            return $this->classNameSingular;
        }
        else {
            return $this->className;
        }
    }

    public function getClassRealName(): string
    {
        return (new \ReflectionClass(get_called_class()))->getShortName();
    }
}