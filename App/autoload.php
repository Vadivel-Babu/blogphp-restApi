<?php
spl_autoload_register(function($cls){
  $path = str_replace("\\", '/',$cls) . ".php";
  require $path;
});