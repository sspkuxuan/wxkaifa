<?php
namespace app\index\controller;
 
use think\Controller;

class Weather extends Controller
{
    public function index()
    {
    	return $this->fetch();
    }   
}