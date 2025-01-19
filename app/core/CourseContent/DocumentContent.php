<?php

namespace App\Core\CourseContent;

class DocumentContent implements CourseContentInterface
{
    public function save($courseId, $content)
    {
        $fileName = time() . '_course_' . $courseId . '.md';
        $uploadDir = BASE_PATH . '/public/uploads/';
        $filePath = 'uploads/' . $fileName;

        file_put_contents($uploadDir . $fileName, $content);
        return [
            'name' => $fileName,
            'path' => $filePath
        ];
    }

    public function display($content)
    {
        $markdown = file_get_contents(BASE_PATH . '/public/' . $content['path']);
        return "<div class='markdown-content'>
                    " . htmlspecialchars($markdown) . "
                </div>";
    }
}
