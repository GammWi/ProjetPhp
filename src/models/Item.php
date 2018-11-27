<?php
/**
 * Created by PhpStorm.
 * User: emile
 * Date: 27/11/2018
 * Time: 10:20
 */

namespace mywhishlist\models;


class Item extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamp = false;
}