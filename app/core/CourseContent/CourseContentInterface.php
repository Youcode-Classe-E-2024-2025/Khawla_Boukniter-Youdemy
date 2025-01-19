<?php

namespace App\Core\CourseContent;

interface CourseContentInterface
{
    public function save($courseId, $content);
    public function display($content);
}
