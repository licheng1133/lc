<!DOCTYPE HTML>

<html>

<head>

<meta charset="utf-8">

<title>拖拽上传</title>

<script src="js/jquery-1.7.1.min.js"></script>

<style>

.dashboard_target_box{width:250px;height:105px;border:3px dashed #E5E5E5;text-align:center;position:absolute;z-index:2000;top:0;left:0;cursor:pointer}

.dashboard_target_box.over{border:3px dashed #000;background:#ffa}

.dashboard_target_messages_container{display:inline-block;margin:12px 0 0;position:relative;text-align:center;height:44px;overflow:hidden;z-index:2000}

.dashboard_target_box_message{position:relative;margin:4px auto;font:15px/18px helvetica,arial,sans-serif;font-size:15px;color:#999;font-weight:normal;width:150px;line-height:20px}

.dashboard_target_box.over #dtb-msg1{color:#000;font-weight:bold}

.dashboard_target_box.over #dtb-msg3{color:#ffa;border-color:#ffa}

#dtb-msg2{color:orange}

#dtb-msg3{display:block;border-top:1px #EEE dotted;padding:8px 24px}

</style>

<script>

$().ready(function(){

 if($.browser.safari || $.browser.mozilla){

  $('#dtb-msg1 .compatible').show();

  $('#dtb-msg1 .notcompatible').hide();

  $('#drop_zone_home').hover(function(){

   $(this).children('p').stop().animate({top:'0px'},200);

  },function(){

   $(this).children('p').stop().animate({top:'-44px'},200);

  });

  //功能实现

  $(document).on({

   dragleave:function(e){

    e.preventDefault();

    $('.dashboard_target_box').removeClass('over');

   },

   drop:function(e){

    e.preventDefault();

    //$('.dashboard_target_box').removeClass('over');

   },

   dragenter:function(e){

    e.preventDefault();

    $('.dashboard_target_box').addClass('over');

   },

   dragover:function(e){

    e.preventDefault();

    $('.dashboard_target_box').addClass('over');

   }

  });

  var box = document.getElementById('target_box');

  box.addEventListener("drop",function(e){

   e.preventDefault();

   //获取文件列表

   var fileList = e.dataTransfer.files;

   var img = document.createElement('img');

   //检测是否是拖拽文件到页面的操作

   if(fileList.length == 0){

    $('.dashboard_target_box').removeClass('over');

    return;

   }

   //检测文件是不是图片

   if(fileList[0].type.indexOf('image') === -1){

    $('.dashboard_target_box').removeClass('over');

    return;

   }

   

   if($.browser.safari){

    //Chrome8+

    img.src = window.webkitURL.createObjectURL(fileList[0]);

   }else if($.browser.mozilla){

    //FF4+

    img.src = window.URL.createObjectURL(fileList[0]);

   }else{

    //实例化file reader对象

    var reader = new FileReader();

    reader.onload = function(e){

     img.src = this.result;

     $(document.body).appendChild(img);

    }

    reader.readAsDataURL(fileList[0]);

   }

   var xhr = new XMLHttpRequest();

   xhr.open("post", "test.php", true);

   xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

   xhr.upload.addEventListener("progress", function(e){

    $("#dtb-msg3").hide();

    $("#dtb-msg4 span").show();

    $("#dtb-msg4").children('span').eq(1).css({width:'0px'});

    $('.show').html('');

    if(e.lengthComputable){

     var loaded = Math.ceil((e.loaded / e.total) * 100);

     $("#dtb-msg4").children('span').eq(1).css({width:(loaded*2)+'px'});

    }

   }, false);

   xhr.addEventListener("load", function(e){

    $('.dashboard_target_box').removeClass('over');

    $("#dtb-msg3").show();

    $("#dtb-msg4 span").hide();

    var result = jQuery.parseJSON(e.target.responseText);

    alert(result.filename);

    $('.show').append(result.img);

   }, false);

   

   var fd = new FormData();

   fd.append('xfile', fileList[0]);

   xhr.send(fd);

  },false);

 }else{

  $('#dtb-msg1 .compatible').hide();

  $('#dtb-msg1 .notcompatible').show();

 }

});

</script>

</head>
	<body>

<div id="target_box" class="dashboard_target_box">

 <div id="drop_zone_home" class="dashboard_target_messages_container">

  <p id="dtb-msg2" class="dashboard_target_box_message" style="top:-44px">选择你的图片<br>开始上传</p>

  <p id="dtb-msg1" class="dashboard_target_box_message" style="top:-44px">

   <span class="compatible" style="display:inline">拖动图片到</span><span class="notcompatible" style="display:none">点</span>这里<br>开始上传图片

  </p>

 </div>

 <p id="dtb-msg3" class="dashboard_target_box_message">选择网络图片</p>

 <p id="dtb-msg4" class="dashboard_target_box_message" style="position:relative">

  <span style="display:none;width:200px;height:2px;background:#ccc;left:-25px;position:absolute;z-index:1"></span>

  <span style="display:none;width:0px;height:2px;background:#09F;left:-25px;position:absolute;z-index:2"></span>

 </p>

</div>

<div class="show" style="float:left;width:300px;height:150px;border:1px solid red;margin-top:200px;overflow:hidden;"></div>

</body>

</html>