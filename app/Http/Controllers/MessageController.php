<?php
namespace App\Http\Controllers;
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

use Elasticsearch\ClientBuilder;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    private $client;
    private $params;

    /**
     * MessageController constructor.
     */
    public function __construct()
    {
        $hosts = [
            'localhost:9243'q
        ];
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
        $this->params = [
            'index' => 'sbmessage',
            'type' => 'messages',
        ];
    }

    public function index()
    {
        $result = app('db')->select("select * from users u inner join user_messages m on u.id = m.user_id");
        $viewData['users'] = $result;
        $viewData['messages'] = $result;

        return view('index', $viewData);
    }

    public function search(Request $request)
    {
        $searchText = $request->input('searchText');

        // ダブルクオートの文字取得
        // \".+?\"
        // 複数スペースを一つへ
        // preg_replace('/\s(?=\s)/', '', $str);
        // 文字を取得
        // 前後に*をつける
        // 配列を文字列に変換


        $this->params['body'] = [
            'query' => [
                'query_string' => [
                    'query' => "user*",
                    'fields' => ['message', 'user_name']
                ]
            ]
        ];

        $message_id_array = [];
        $result = $this->client->search($this->params);
        if ($result['hits']['total'] > 0) {
            foreach ($result['hits']['hits'] as $hit) {
                $message_id_array[] = $hit['_source']['message_id'];
            }
        }

        if ($message_id_array) {
            $message_id = implode(",", $message_id_array);
            $result = app('db')->select("select * from users u inner join user_messages m on u.id = m.user_id where m.id in(" . $message_id . ")");
        } else {
            $result = null;
        }
        $viewData['users'] = $result;
        $viewData['messages'] = $result;

        return view('index', $viewData);
    }
}
