title: 【OCR/机器学习】基于 Tesseract的图文识别搜索引擎
date: 2016-3-1 19:37:09

tags:

- 机器学习
- 神经网络
- 图像识别
- 搜索引擎

categories:

- 原创博文
- 自己的开源项目

---
# 前言：

这是一篇图像识别OCR技术、机器学习、以及简易搜索引擎构建相关的技术Blog，是自己在做毕设的同时，每天不断记录研究成果和心得的地方。

<!--more-->

# 选题背景

OCR（Optical Character Recognition 光学字符识别）技术，是指电子设备（例如扫描仪或数码相机）检查纸上打印的字符，通过检测暗、亮的模式确定其形状，然后用字符识别方法将形状翻译成计算机文字的过程。 

Tesseract的OCR引擎最先由HP实验室于1985年开始研发，至1995年时已经成为OCR业内最准确的三款识别引擎之一。然而，HP不久便决定放弃OCR业务，Tesseract也从此尘封。 
数年以后，HP意识到，与其将Tesseract束之高阁，不如贡献给开源软件业，让其重焕新生－－2005年，Tesseract由美国内华达州信息技术研究所获得，并求诸于Google对Tesseract进行改进、消除Bug、优化工作。 Tesseract目前已作为开源项目发布在Google Project，其最新版本3.0已经支持中文OCR。

在这样成熟的技术背景下，我很想利用这项OCR技术，再结合当下热门的移动互联网的开发技术和信息检索技术，实现一个能将图片中文字成功识别的移动Web搜索引擎，旨在为更多朋友能更加快捷、准确地从图片中获取想要的信息。。

# 需求分析




# 用例设计（待调整）
![](http://7xi6qz.com1.z0.glb.clouddn.com/case1.png)

# 应用领域
- 纹身设计
- 海报检索
- 广告检索
- 翻译
- 考古发现
- 名片检索



# 架构设计（待调整）


![](http://7xi6qz.com1.z0.glb.clouddn.com/%E6%AF%95%E8%AE%BE%E5%AD%97%E4%BD%93%E6%90%9C%E7%B4%A2%E5%BC%95%E6%93%8E.png)


# 技术点分析




# 工程实现



## 后端工程实现


后端的架构，主要分为四块：Tesseract-OCR-PHP中间件，PHP图片传输中间件，PHP云检索中间件，搜索引擎中间件。


### 开源库：
- 使用[Composer](http://daijiale.github.io/2016/03/08/%E3%80%90PHP%E3%80%91%20Composer%E5%85%A5%E9%97%A8%E5%AE%9E%E8%B7%B5/) 依赖；
   -  [Silex framework ](http://silex.sensiolabs.org/doc/intro.html);
   -  [thiagoalessio](https://github.com/thiagoalessio/tesseract-ocr-for-php);
-  [Nutch2.3.1+Solr](http://nutch.apache.org/downloads.html);


### Tesseract-OCR中间件实现

#### 1.先翻墙

#### 2.打开Mac OS的终端，键入

	``` shell
	brew install tesseract
	```
#### 3.如果未同意Xcode协议许可，需要先键入协议许可，同意。

	```shell
	sudo xcodebuild -license

	...

	agree
	```
	
![](http://7xi6qz.com1.z0.glb.clouddn.com/%E6%AF%95%E8%AE%BEtesseract%E5%AE%89%E8%A3%85.png)


#### 4.继续使用Homebrew安装
	``` shell
	brew install tesseract
	```

![](http://7xi6qz.com1.z0.glb.clouddn.com/%E6%AF%95%E8%AE%BEtesseract%E5%AE%89%E8%A3%852.png)

#### 5.安装成功后，进行测试，看Tesseract能否在Mac OS上正常运行,如下图所示。
![](http://7xi6qz.com1.z0.glb.clouddn.com/%E6%AF%95%E8%AE%BEtesseract%E5%AE%89%E8%A3%853.png)

#### 6.这里解释下Tesseract终端下的用法：
 	```shell
 	Usage:tesseract imagename outputbase [-l lang] [-psm pagesegmode] [configfile...]
	pagesegmode values are:
	0 = Orientation and script detection (OSD) only.
	1 = Automatic page segmentation with OSD.
	2 = Automatic page segmentation, but no OSD, or OCR
	3 = Fully automatic page segmentation, but no OSD. (Default)
	4 = Assume a single column of text of variable sizes.
	5 = Assume a single uniform block of vertically aligned text.
	6 = Assume a single uniform block of text.
	7 = Treat the image as a single text line.
	8 = Treat the image as a single word.
	9 = Treat the image as a single word in a circle.
	10 = Treat the image as a single character.
	-l lang and/or -psm pagesegmode must occur before anyconfigfile.
	```
- 其中：	
	```shell
	tesseract imagename outputbase [-l lang] [-psm pagesegmode] [configfile...]
	```
   表示 ` tesseract    图片名  输出文件名 -l 字库文件 -psm pagesegmode 配置文件`。
   
-  例如：`tesseract code.jpg result  -l chi_sim -psm 7 nobatch`
    -  ` -l chi_sim` 表示用简体中文字库（需要下载中文字库文件，解压后，存放到`tessdata`目录下去,字库文件扩展名为 ` .raineddata `简体中文字库文件名为:  `chi_sim.traineddata`）。
    - `-psm 7 `表示告诉tesseract `code.jpg`图片是一行文本  这个参数可以减少识别错误率.  默认为 `3`。
    - configfile 参数值为tessdata\configs 和  tessdata\tessconfigs 目录下的文件名。
    
#### 7.现在我们来使用测试一下，如下图	 

**英文字体测试：**
![](http://7xi6qz.com1.z0.glb.clouddn.com/%E6%AF%95%E8%AE%BEtess%E5%AE%89%E8%A3%854.png)


**中文字体测试：**
![](http://7xi6qz.com1.z0.glb.clouddn.com/%E6%AF%95%E8%AE%BEocr-php-test.png)



#### 8.现在我们来建立字体库以及字体数据的训练

##### 字体库建立的官方原版说明：

```
**font_properties (new in 3.01)**

A new requirement for training in 3.01 is a font_properties file. The purpose of this file is to provide font style information that will appear in the output when the font is recognized. The font_properties file is a text file specified by the -F filename option to mftraining.

Each line of the font_properties file is formatted as follows:

<fontname> <italic> <bold> <fixed> <serif> <fraktur>
where <fontname> is a string naming the font (no spaces allowed!), and <italic>, <bold>, <fixed>, <serif> and <fraktur> are all simple 0 or 1 flags indicating whether the font has the named property.

When running mftraining, each .tr filename must match an entry in the font_properties file, or mftraining will abort. At some point, possibly before the release of 3.01, this matching requirement is likely to shift to the font name in the .tr file itself. The name of the .tr file may be either fontname.tr or [lang].[fontname].exp[num].tr.

**Example:**

font_properties file:

timesitalic 1 0 0 1 0
shapeclustering -F font_properties -U unicharset eng.timesitalic.exp0.tr
mftraining -F font_properties -U unicharset -O eng.unicharset eng.timesitalic.exp0.tr 
Note that in 3.03, there is a default font_properties file, that covers 3000 fonts (not necessarily accurately) in training/langdata/font_properties.

**Clustering**

When the character features of all the training pages have been extracted, we need to cluster them to create the prototypes.

The character shape features can be clustered using the shapeclustering, mftraining and cntraining programs:

**shapeclustering (new in 3.02)**

shapeclustering should not be used except for the Indic languages.

shapeclustering -F font_properties -U unicharset lang.fontname.exp0.tr lang.fontname.exp1.tr ...
shapeclustering creates a master shape table by shape clustering and writes it to a file named shapetable.

**mftraining**

mftraining -F font_properties -U unicharset -O lang.unicharset lang.fontname.exp0.tr lang.fontname.exp1.tr ...
The -U file is the unicharset generated by unicharset_extractor above, and lang.unicharset is the output unicharset that will be given to combine_tessdata.

mftraining will output two other data files: inttemp (the shape prototypes) and pffmtable (the number of expected features for each character). In versions 3.00/3.01, a third file called Microfeat is also written by this program, but it is not used. Later versions don't produce this file.

NOTE: mftraining will produce a shapetable file if you didn't run shapeclustering. You must include this shapetable in your traineddata file, whether or not shapeclustering was used.

**cntraining**

cntraining lang.fontname.exp0.tr lang.fontname.exp1.tr ...
This will output the normproto data file (the character normalization sensitivity prototypes).

```
##### 如何进行机器学习，训练自定义新数据：

- [官方wiki](https://github.com/tesseract-ocr/tesseract/wiki/TrainingTesseract)


- [中文指导](http://wangjunle23.blog.163.com/blog/static/117838171201323031458171/)

##### 实践过程：

- 首先可以从Tesseract官方Github上下载官方的语言包进行参考：[传送门](https://github.com/tesseract-ocr/tessdata)


- 




### Tesseract-OCR-PHP中间件的实现

#### 1. 配置PHP和服务器环境

可以使用WAMP/MAMP，也可以用PHPStorm和其内置服务器。

#### 2.使用Composer进行PHP源工程的构建
具体操作可以参考：[传送门](http://daijiale.github.io/2016/03/08/%E3%80%90PHP%E3%80%91%20Composer%E5%85%A5%E9%97%A8%E5%AE%9E%E8%B7%B5/)

打开终端，切换到你的工程路径下：

```shell
composer require silex/silex twig/twig thiagoalessio/tesseract_ocr:dev-master
```

因为使用了PHP的微型框架`Silex Framework`，我们需要自己建立PHP源工程项目MVC（public，uploads，views）结构，如图所示：

![](http://7xi6qz.com1.z0.glb.clouddn.com/%E6%AF%95%E8%AE%BEocr-php.png)


- **public:**存放PHP脚本
- **uploads：**存放用户上传图片
- **vendor：**所有composer依赖包
- **views：**前端代码


#### 3.用Sliex库建立PHP消息中间件

#####启动初始化 
 - public/index.php

```php
<?php 
//如果是在WAMP等其他集成环境下，需要重新获取环境变量的PATH，不然无法调用Tesseract

$path = getenv('PATH');
putenv("PATH=$path:/usr/local/bin");

require __DIR__.'/../vendor/autoload.php'; 

use Symfony\Component\HttpFoundation\Request; 

$app = new Silex\Application(); 

$app->register(new Silex\Provider\TwigServiceProvider(), [
  'twig.path' => __DIR__.'/../views',
]);

$app['debug'] = true; 

$app->get('/', function() use ($app) { 

  return $app['twig']->render('index.twig');

}); 

$app->post('/', function(Request $request) use ($app) { 

    //TODP
    
}); 

$app->run();
```

##### 文件上传并唯一标识存放

```php
// Grab the uploaded file
$file = $request->files->get('upload'); 

// Extract some information about the uploaded file
$info = new SplFileInfo($file->getClientOriginalName());

// 产生随机文件名来减少文件名冲突
$filename = sprintf('%d.%s', time(), $info->getExtension());

// Copy the file
$file->move(__DIR__.'/../uploads', $filename);
```





### 搜索引擎的实现



#### Nutch和Solar在Mac下的部署与开发
![](http://7xi6qz.com1.z0.glb.clouddn.com/nutch%E5%B1%8F%E5%B9%95%E5%BF%AB%E7%85%A7%202016-04-22%2011.06.32.png)



## 前端工程实现

-views
	-





## 移动端工程实现




# 测试数据



# 心得体会

# 参考文献
- [ tesseract OCR训练新数据的详细步骤-附大量训练数据](http://blog.csdn.net/tuling_research/article/details/41091163)
- [ tesseract OCR的多语言，多字体字符识别](http://blog.csdn.net/viewcode/article/details/7917320)
- [Tesseract:安装与命令行使用](http://linusp.github.io/2015/04/17/tesseract-install-usage.html)
- [Mac下使用Vagrant打造本地开发环境](https://liuzhichao.com/p/1940.html)
- [PHP OCR实战：用Tesseract从图像中读取文字](http://www.codeceo.com/article/php-ocr-tesseract-get-text.html?url_type=39&object_type=webpage&pos=1)
- [Github—Tesseract-OCR](https://github.com/tesseract-ocr)
- [ Tesseract-OCR 字符识别---样本训练](http://blog.csdn.net/firehood_/article/details/8433077)
- [开源图文识别引擎实现验证码识别 ](http://bbs.aardio.com/forum.php?mod=viewthread&tid=12601)
- [Tesseract-OCR 3.02 训练笔记](http://www.cnblogs.com/ShineTan/archive/2013/04/15/3021523.html)
- [Tesseract 3 语言数据的训练方法](http://www.cnblogs.com/mjorcen/p/3799996.htm)
- [Tesseract 3.02中文字库训练](http://www.cnblogs.com/mjorcen/p/3800739.html)
- [Mac下Tesseract安装部署](http://holybless.iteye.com/blog/1338717)
- [基于Tesseract-OCR的名片识别系统](http://cdmd.cnki.com.cn/Article/CDMD-10561-1014065487.htm)
- [Github_Tesseract-OCR_For_PHP](https://github.com/thiagoalessio/tesseract-ocr-for-php)