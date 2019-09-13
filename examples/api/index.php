<?php

require_once (dirname(__FILE__) . "/../../lib/rhino.php");

$app = rhino();

$app->use($jsonparse);

$courses = array(
  array("id" => 1, "name" => "Spanish Course"),
  array("id" => 2, "name" => "Mandarin Course"),
  array("id" => 3, "name" => "Portuguese Course"),
);

$app->post('/api/courses', function ($req, $res) {
  global $courses;

  $course = array(
    "id" => count($courses) + 1,
    "name" => $req->body->name
  );

  $courses[] = $course;

  $res->json($course);
});

$app->get('/api/courses', function ($req, $res) {
  global $courses;
  $res->json($courses);
});

$app->get('/api/courses/:id', function ($req, $res) {
  global $courses;

  $course = null;

  foreach ($courses as $item) {
    if ($item["id"] == $req->params->id) {
      $course = $item;
    }
  }

  if (!$course) {
    return $res->status(404)->send("Course with given id not found.");
  }

  $res->json($course);
});

$app->put('/api/courses/:id', function ($req, $res) {
  global $courses;

  $course = null;

  foreach ($courses as $item) {
    if ($item["id"] == $req->params->id) {
      $course = $item;
    }
  }

  if (!$course) {
    return $res->status(404)->send("Course with given id not found.");
  }

  $course['name'] = $req->body->name;

  $res->json($course);
});

$app->delete('/api/courses/:id', function ($req, $res) {
  global $courses;

  $course = null;

  foreach ($courses as $item) {
    if ($item["id"] == $req->params->id) {
      $course = $item;
    }
  }

  if (!$course) {
    return $res->status(404)->send("Course with given id not found.");
  }

  $index = array_search($course, $courses);
  array_splice($courses, $index, 1);

  $res->json($course);
});

$app->use(function ($req, $res) {
  $res->send("Hello from use!<br>\n");
});

$app->get('/', function ($req, $res) {
  // can not use variables outside of this closure unless they are global
  global $courses;
  print_r($courses);

  $res->send("Hello from get!<br>\n");

  foreach ($req as $key => $value) {
    print_r($key);
    print_r(" => ");
    print_r($value);
    print_r("<br>\n");
  }

});

$app->start();
