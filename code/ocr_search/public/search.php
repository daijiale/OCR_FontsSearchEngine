<?php

    require __DIR__.'/../vendor/autoload.php';

    use Symfony\Component\HttpFoundation\Request;

    $app = new Silex\Application();

    $app->register(new Silex\Provider\TwigServiceProvider(), [
        'twig.path' => __DIR__.'/../views',
    ]);

    $app['debug'] = true;

    //处理public/search.php下的get访问请求
    $app->get('/', function() use ($app) {

        return $app['twig']->render('index.twig');

    });

    //处理public/search.php下的post访问请求

    $app->post('/',function(Request $request)use($app) {

        $SolrQuery = $request->get('ocr_text');

        $url = 'http://localhost:8983/solr/collection1/select';

        //这里的SolrQuery为中文时候,需要转化为十六进制进行接口访问,否则solr引擎会报错

        $url = $url . '?' .'q='.urlencode($SolrQuery).'&wt=json&indent=true';

        $ch = curl_init();

        //这是你想用PHP取回的URL地址

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        //如果成功只将结果返回，不自动输出任何内容,失败只返回false

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);

        curl_close($ch);

        $data = json_decode($data,true);

        $doc = $data['response']['docs'];

        //解析权重分数最高的前十条结果数据

        for($i=0;$i<10;$i++){

            $index_time[$i] = $doc[$i]['tstamp'];

        }

        for($i=0;$i<10;$i++){

            $title[$i] = $doc[$i]['title'];

        }

        for($i=0;$i<10;$i++){

            $solr_url[$i] = $doc[$i]['url'];

        }

        for($i=0;$i<10;$i++){

            $content[$i] = $doc[$i]['content'];

        }

        for($i=0;$i<10;$i++){

            $boost[$i] = $doc[$i]['boost'];

        }
        //这样渲染不优雅,打算后面好好学一下twig,重构一下

        return $app['twig']->render(
            'search_results.twig',
            [
                'keyword' => $SolrQuery,
                'index_time0'  =>  $index_time[0],
                'title0'  =>  $title[0],
                'url0'  =>  $solr_url[0],
                'content0'  =>  $content[0],
                'boost0'  =>  $boost[0],

                'index_time1'  =>  $index_time[1],
                'title1'  =>  $title[1],
                'url1'  =>  $solr_url[1],
                'content1'  =>  $content[1],
                'boost1'  =>  $boost[1],

                'index_time2'  =>  $index_time[2],
                'title2'  =>  $title[2],
                'url2'  =>  $solr_url[2],
                'content2'  =>  $content[2],
                'boost2'  =>  $boost[2],

                'index_time3'  =>  $index_time[3],
                'title3'  =>  $title[3],
                'url3'  =>  $solr_url[3],
                'content3'  =>  $content[3],
                'boost3'  =>  $boost[3],

                'index_time4'  =>  $index_time[4],
                'title4'  =>  $title[4],
                'url4'  =>  $solr_url[4],
                'content4'  =>  $content[4],
                'boost4'  =>  $boost[4],

                'index_time5'  =>  $index_time[5],
                'title5'  =>  $title[5],
                'url5'  =>  $solr_url[5],
                'content5'  =>  $content[5],
                'boost5'  =>  $boost[5],

                'index_time6'  =>  $index_time[6],
                'title6'  =>  $title[6],
                'url6'  =>  $solr_url[6],
                'content6'  =>  $content[6],
                'boost6'  =>  $boost[6],

                'index_time7'  =>  $index_time[7],
                'title7'  =>  $title[7],
                'url7'  =>  $solr_url[7],
                'content7'  =>  $content[7],
                'boost7'  =>  $boost[7],

                'index_time8'  =>  $index_time[8],
                'title8'  =>  $title[8],
                'url8'  =>  $solr_url[8],
                'content8'  =>  $content[8],
                'boost8'  =>  $boost[8],

                'index_time9'  =>  $index_time[9],
                'title9'  =>  $title[9],
                'url9'  =>  $solr_url[9],
                'content9'  =>  $content[9],
                'boost9'  =>  $boost[9],


            ]
            );
        });

        $app->run();