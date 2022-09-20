<?php


namespace MennoVanHout\JMS;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'JMS\Serializer\Serializer';
    }
}
