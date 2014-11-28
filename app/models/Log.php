<?php

class Log extends Eloquent{
    protected $table = 'action_log';
    public static $unguarded = true;
}