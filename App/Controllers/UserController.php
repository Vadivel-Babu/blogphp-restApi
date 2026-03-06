<?php 

namespace App\Controllers;
use App\Helpers\Response;

class UserController
{
  public function name()
  {
    Response::json(["data"=>"hello world!"]);
  }

  public function show($id)
  {
   Response::json(["data"=>"$id"]);
  }
}