<?php 

use Brychtaj\Taggy\TaggableTrait;
use Illuminate\Database\Eloquent\Model;


class TagStub extends Model
{
    use TaggableTrait;

    protected $connection = "testbench";

    public $table = "tags";
}
