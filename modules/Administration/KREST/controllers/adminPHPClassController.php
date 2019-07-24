<?php

namespace SpiceCRM\modules\Administration\KREST\controllers;

class adminPHPClassController
{
    public static function checkClass($req, $res, $args)
    {
        $class = base64_decode($args['class']);
        $classExists = class_exists($class);
        $publicMethods = [];
        if ($classExists) {
            $class = new \ReflectionClass($class);
            $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach($methods as $method)
                $publicMethods[] = $method->name;
        }

        return $res->write(json_encode(['classexists' => $classExists, 'methods' => $publicMethods]));
    }
}