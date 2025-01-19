<?php

namespace App\Core\CourseContent;

class VideoContent implements CourseContentInterface
{
    public function save($courseId, $content)
    {
        $fileName = time() . '_' . $content['name'];
        $uploadDir = BASE_PATH . '/public/uploads/';
        $filePath = 'uploads/' . $fileName;

        move_uploaded_file($content['tmp_name'], $uploadDir . $fileName);
        return [
            'name' => $content['name'],
            'path' => $filePath
        ];
    }

    public function display($content)
    {
        return "<video controls class='w-full'>
                    <source src='" . asset_url($content['path']) . "' type='video/mp4'>
                </video>";
    }
}
