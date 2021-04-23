# 项目介绍

这是个人开发的基于Hyperf框架之上的学习项目

# 要求

Hyperf对系统环境有一些要求，它只能在Linux和Mac环境下运行，但是由于Docker虚拟化技术的发展，适用于Windows的Docker也可以用作Windows下的运行环境。

如果您不想使用Docker作为运行环境的基础，则需要确保您的操作环境满足以下要求： 
  
  -PHP >= 7.2
  
  -Swoole   PHP扩展> = 4.4，并且禁用了“短名称”
  
  -OpenSSL  PHP扩展
  
  -JSON     PHP扩展
  
  -PDO      PHP扩展（如果需要使用MySQL客户端）
  
  -Redis    PHP扩展（如果需要使用Redis Client）
  
  -Protobuf PHP扩展（如果需要使用Client的gRPC Server）

# 使用Composer安装

创建新的Hyperf项目的最简单方法是使用Composer。 如果尚未安装，请按照文档进行安装。
要创建新的Hyperf项目，请执行以下操作：
$ composer create-project hyperf / hyperf-skeleton路径/到/安装
安装完成后，您可以使用以下命令立即运行服务器。
$ cd路径/到/安装
$ php bin / hyperf.php开始
这将在端口“ 9501”上启动cli服务器，并将其绑定到所有网络接口。 然后，您可以访问http//localhost:9501/上的站点。
这将打开Hyperf默认主页。
