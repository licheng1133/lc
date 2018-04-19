<?php
    if($_FILES)
    {
        $filename = $_FILES['img']['name'];
        $tmpname = $_FILES['img']['tmp_name'];
        if(move_uploaded_file($tmpname, dirname(__FILE__).'/img/'.$filename))
        {
            echo json_encode('上传成功');
        }
        else
        {
            $data = json_encode($_FILES);
            echo $data;
        }
    }
?>