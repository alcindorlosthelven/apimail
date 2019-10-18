<?php
use app\DefaultApp\DefaultApp as App;
App::get("/", "default.index", "index");
App::post("/", "default.index","index_post");
App::post("v1/send","v1.send");
//App::get("v1/send","v1.sendGet");
