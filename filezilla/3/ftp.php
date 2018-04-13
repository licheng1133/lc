<?php
class FtpClient {  
   /** 
     * 上传文件根目录 
     * @var string 
     */  
    private $rootPath;  
    
    /** 
     * 本地上传错误信息 
     * @var string 
     */  
    private $error = ''; //上传错误信息  
    
    /** 
     * FTP连接 
     * @var resource 
     */  
    private $link;  
    
    private $config = array(  
        'host'     => '39.106.158.148', //服务器  
        'port'     => 22, //端口  
        'timeout'  => 90, //超时时间  
        'username' => 'root', //用户名  
        'password' => 'Admin123', //密码  
    );  
    
    
    /** 
     * 构造函数，用于设置上传根路径 
     * @param array  $config FTP配置 
     */  
    public function __construct($config){  
        /* 默认FTP配置 */  
        $this->config = array_merge($this->config, $config);  
    
        /* 登录FTP服务器 */  
        if(!$this->login()){  
            E($this->error);  
        }  
    }  
    
    /** 
     * 检测上传根目录 
     * @param string $rootpath   根目录 
     * @return boolean true-检测通过，false-检测失败 
     */  
    public function checkRootPath($rootpath){  
        /* 设置根目录 */  
        $this->rootPath = ftp_pwd($this->link) . '/' . ltrim($rootpath, '/');  
    
        if(!@ftp_chdir($this->link, $this->rootPath)){  
            $this->error = '上传根目录不存在！';  
            return false;  
        }  
        return true;  
    }  
    
    /** 
     * 检测上传目录 
     * @param  string $savepath 上传目录 
     * @return boolean          检测结果，true-通过，false-失败 
     */  
    public function checkSavePath($savepath){  
        /* 检测并创建目录 */  
        if (!$this->mkdir($savepath)) {  
            return false;  
        } else {  
            //TODO:检测目录是否可写  
            return true;  
        }  
    }  
    
    /** 
     * 保存指定文件 
     * @param  array   $file    保存的文件信息 
     * @param  boolean $replace 同名文件是否覆盖 
     * @return boolean          保存状态，true-成功，false-失败 
     */  
    public function save($file, $replace=true) {  
        $filename = $this->rootPath . $file['savepath'] . $file['savename'];  
    
        /* 不覆盖同名文件 */  
        // if (!$replace && is_file($filename)) {  
        //     $this->error = '存在同名文件' . $file['savename'];  
        //     return false;  
        // }  
    
        /* 移动文件 */  
        if (!ftp_put($this->link, $filename, $file['tmp_name'], FTP_BINARY)) {  
            $this->error = '文件上传保存错误！';  
            return false;  
        }  
        return true;  
    }  
    
    /** 
     * 创建目录 
     * @param  string $savepath 要创建的穆里 
     * @return boolean          创建状态，true-成功，false-失败 
     */  
    public function mkdir($savepath){  
        $dir = $this->rootPath . $savepath;  
        if(ftp_chdir($this->link, $dir)){  
            return true;  
        }  
    
        if(ftp_mkdir($this->link, $dir)){  
            return true;  
        } elseif($this->mkdir(dirname($savepath)) && ftp_mkdir($this->link, $dir)) {  
            return true;  
        } else {  
            $this->error = "目录 {$savepath} 创建失败！";  
            return false;  
        }  
    }  
    /** 
     * 创建目录 
     * @param  string $file     目标文件或者目录 
     * @return real          文件大小或者-1 
     */  
    public function filesize($file)  
    {  
        return @ftp_size($this->link,$file);  
    }  
    
    /** 
     * 获取最后一次上传错误信息 
     * @return string 错误信息 
     */  
    public function getError(){  
        return $this->error;  
    }  
    /** 
     * 给文件或者目录授权 
     * @param String $file 文件或者路径 
     * @param $mode 八进制权限值 1- 执行权限，2-写权限，4 - 读权限  0644->所有者可读写，其他人可读 
     * @return 设置成功的新权限或者失败时false 
     */  
    public function chmod($file,$mode)  
    {  
        if(!ftp_chmod(($this->link,$mode,$file))  
        {  
            $this->error = '授权失败';  
        }  
    }  
    /** 
     * @param string  path  必需。规定要删除的文件的路径 
     * @return true or false 
     */  
    public function delete($path)  
    {  
        if (!ftp_delete($this->link, $path)) {  
           $this->error ='删除文件：'.$path.' 失败';  
        }  
    }  
    
    /** 
     * @param string local  必需。本地文件存储路径 
     * @param string remote  必需。远程文件路径 
     * @param string mode  必需。读取模式 
     * @param string resume  读取文件大小的起始位置 
     * @return true or false 
     */  
    public function fetch($local,$remote,$mode,$resume=0)  
    {  
        $arr_mode = array(FTP_ASCII,FTP_BINARY);  
        if(!in_array($mode, $arr_mode))  
        {  
            $mode = FTP_BINARY;  
        }  
        if(!ftp_get($this->link,$local,$remote,$mode,$resume))  
        {  
            $this->error ='读取远程文件：'.$remote.' 失败';  
        }  
    }  
    
    /** 
     * 登录到FTP服务器 
     * @return boolean true-登录成功，false-登录失败 
     */  
    private function login(){  
        extract($this->config);  
        $this->link = ftp_connect($host, $port, $timeout);  
        if($this->link) {  
            if (ftp_login($this->link, $username, $password)) {  
               return true;  
            } else {  
                $this->error = "无法登录到FTP服务器：username - {$username}";  
            }  
        } else {  
            $this->error = "无法连接到FTP服务器：{$host}";  
        }  
        return false;  
    }  
    
    /** 
     * 析构方法，用于断开当前FTP连接 
     */  
    public function __destruct() {  
        ftp_close($this->link);  
    }  
}  