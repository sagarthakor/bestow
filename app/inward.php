<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inward extends Model
{
    protected $table="inward";
    public $timestamps=false;
    /**
     * @var false|mixed|string
     */
    private $inward_date;
    /**
     * @var int|mixed
     */
    private $inward_no;
    /**
     * @var mixed|string
     */
    private $inward_number;
    /**
     * @var mixed
     */
    private $vendor;
    /**
     * @var mixed
     */
    private $purchase;
    /**
     * @var mixed
     */
    private $subject;
    /**
     * @var mixed
     */
    private $remark;
    /**
     * @var false|mixed|string
     */
    private $created_time;
    /**
     * @var mixed
     */
    private $user_id;
    /**
     * @var mixed
     */
    private $website_id;
}
