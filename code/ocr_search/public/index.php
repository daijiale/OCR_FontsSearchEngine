<?php 

    //如果是在WAMP等其他集成环境下，需要重新获取环境变量的PATH，不然无法调用Tesseract

    $path = getenv('PATH');

    // putenv("PATH=$path:/usr/local/bin");

    putenv("PATH=$path:/opt/local/bin");

    require __DIR__.'/../vendor/autoload.php';

    use Symfony\Component\HttpFoundation\Request;

    $app = new Silex\Application();

    $app->register(new Silex\Provider\TwigServiceProvider(), [
        'twig.path' => __DIR__.'/../views',
    ]);

    $app['debug'] = true;

    //处理public/index.php下的get访问请求
    $app->get('/', function() use ($app) {

    return $app['twig']->render('index.twig');

    });

    //处理index.twig的post请求
    $app->post('/', function(Request $request) use ($app) {

    // Grab the uploaded file
    $file = $request->files->get('upload');
  
    // Extract some information about the uploaded file
    $info = new SplFileInfo($file->getClientOriginalName());
  
    // Create a quasi-random filename
    $filename = sprintf('%d.%s', time(), $info->getExtension());

    // Copy the file
    $file->move(__DIR__.'/../uploads', $filename);

    // Instantiate the Tessearct library
    $tesseract = new TesseractOCR(__DIR__ . '/../uploads/' . $filename);

    // Perform OCR on the uploaded image

    //set language
    $tesseract->lang('chi_sim','eng','kor');

    $text1 = $tesseract->run();

    return $app['twig']->render(
        'results.twig',
            [
                'text'  =>  $text1,
            ]
        );

    });


    $app->run();