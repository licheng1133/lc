<?php  

// 连接FTP服务器  
$conn = ftp_connect(www.example.com);  

// 使用username和password登录  
ftp_login($conn, "username", "password");  

// 获取远端系统类型  
ftp_systype($conn);  

// 列示文件  
$filelist = ftp_nlist($conn, ".");  

// 下载文件  
ftp_get($conn, "data.zip", "data.zip", FTP_BINARY);  

// 关闭联接  
ftp_quit($conn);  

//初结化一个FTP联接，PHP提供了ftp_connect()这个函数，它使用主机名称和端口作为参数。在上面的例子里，主机名字为 "ftp.server.com"；如果端口没指定，PHP将会使用"21"作为缺省端口来建立联接。  

//联接成功后ftp_connect()传回一个handle句柄；这个handle将被以后使用的FTP函数使用。  
$conn = ftp_connect(ftp.server.com);  

//一旦建立联接，使用ftp_login()发送一个用户名称和用户密码。你可以看到，这个函数ftp_login()使用了 ftp_connect()函数传来的handle，以确定用户名和密码能被提交到正确的服务器。  
ftp_login($conn, "username", "username");  

// close connection  
ftp_quit($conn);  

//登录了FTP服务器，PHP提供了一些函数，它们能获取一些关于系统和文件以及目录的信息。  
ftp_pwd();

//获取当前所在的目录  
 $here = ftp_pwd($conn);  

//获取服务器端系统信息ftp_systype()  
$server_os = ftp_systype($conn);  

//被动模式（PASV）的开关，打开或关闭PASV（1表示开）  
ftp_pasv($conn, 1);  

//进入目录中用ftp_chdir()函数，它接受一个目录名作为参数。  
ftp_chdir($conn, "public_html");  

//回到所在的目录父目录用ftp_cdup()实现  
ftp_cdup($conn);  

//建立或移动一个目录，这要使用ftp_mkdir()和ftp_rmdir()函数；注意：ftp_mkdir()建立成功的话，就会返回新建立的目录名。  
ftp_mkdir($conn, "test");  

ftp_rmdir($conn, "test");  

//上传文件，ftp_put()函数能很好的胜任，它需要你指定一个本地文件名，上传后的文件名以及传输的类型。比方说：如果你想上传 "abc.txt"这个文件，上传后命名为"xyz.txt"，命令应该是这样：  
ftp_put($conn, "xyz.txt", "abc.txt", FTP_ASCII);  

//下载文件：PHP所提供的函数是ftp_get()，它也需要一个服务器上文件名，下载后的文件名，以及传输类型作为参数，例如：服务器端文件为his.zip，你想下载至本地机，并命名为hers.zip，命令如下：  
ftp_get($conn, "hers.zip", "his.zip", FTP_BINARY);  

//PHP提供两种方法：一种是简单列示文件名和目录，另一种就是详细的列示文件的大小，权限，创立时间等信息。  

//第一种使用ftp_nlist()函数，第二种用ftp_rawlist().两种函数都需要一个目录名做为参数，都返回目录列做为一个数组，数组的每一个元素相当于列表的一行。  
$filelist = ftp_nlist($conn, ".");  

//函数ftp_size()，它返回你所指定的文件的大小，使用BITES作为单位。要指出的是，如果它返回的是 "-1"的话，意味着这是一个目录  
$filelist = ftp_size($conn, "data.zip");  

?> 