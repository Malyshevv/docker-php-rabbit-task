<?php
require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../Entity/Consumers.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueManager
{
    private $connection;
    private $channel;
    private $consumers;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'suerpadmin', '123456');
        $this->channel = $this->connection->channel();
        $this->consumers = new Consumers();
    }

    public function logWrite($filename, $text) {
        $date = date("F j, Y, g:i a");

        $fileName = __DIR__.'/../../log/'.$filename;
        $fileLog = file_get_contents($fileName);
        $fileLog .= '[ '.$date.' ] '.$text;

        file_put_contents($fileName, $fileLog);
    }

    public function handle()
    {
        #$timeout = 30;
        while (count($this->channel->callbacks)) {
            #$this->channel->wait(null, false, $timeout);
            $this->channel->wait();
        }
    }

    public function getResponse($site)
    {
        return get_headers($site);
    }

    public  function getContent($site)
    {
        return file_get_contents($site) ?? '';
    }

    public function resultSiteData($site)
    {
        $res = [];
        $dom = new DOMDocument();
        $dom->loadHTML($this->getContent($site));

        foreach($dom->getElementsByTagName('*') as $el){
            $res[] = ["type" => $el->tagName, "value" => $el->nodeValue];
        }

        return json_encode($res, JSON_UNESCAPED_UNICODE) ?? '';
    }

    public function sendQueue($msg,$queueName)
    {
        if (empty($queueName) && empty($msg)) {
            $this->logWrite('queue-producer.log', 'Error data: queueName='.$queueName.' msg='.$msg);
            return false;
        }

        $this->channel->queue_declare($queueName, false, false, false, true);

        $text = new AMQPMessage($msg);
        $this->channel->basic_publish($text, '', $queueName);

        $result = "[X] Sent ".$msg."\n";
        $this->logWrite('queue-producer.log',$result);

        return $result;
    }

    public function consumerQueue($queueName)
    {
        if (empty($queueName)) {
            $this->logWrite('queue-consumer.log', 'Error data: queueName='.$queueName);
            return false;
        }

        $this->channel->queue_declare($queueName, false, false, false, true);

        print " [*] Waiting for msg. To exit press CTRL+C\n";

        $callback = function ($msg) {
            $result = ' [X] Received '. $msg->body. "\n";

            preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $msg->body, $match);

            $data = array(
                'content' => null,
                'code' => null,
                'msg' => $msg->body
            );

            if (isset($match) && !empty($match)) {
                for ($i = 0; $i < count($match); $i++) {
                    $url = $match[$i][0];
                    if (str_starts_with($url, "http")) {
                        $getResponse = $this->getResponse($url);
                        $code = $getResponse ? substr($getResponse[0], 9, 3) : null;
                        $data['msg'] = $url;
                        if ($code == 200) {
                            $resultSiteData = $this->resultSiteData($url);
                            $data['code'] = $code;
                            $data['content'] = $resultSiteData ?? null;
                            $this->consumers->insertData($data);
                        } else {
                            $data['code'] = $code;
                            $data['content'] = $msg->body;
                            $this->consumers->insertData($data);
                        }
                    }
                }
            } else {
                $this->consumers->insertData($data);
            }

            $this->logWrite('queue-consumer.log',
                "=========================== \n
                text: ${result} \n 
                data: ${$data}  \n
                =========================== \n
            ");

            print $result;
        };

        $this->channel->basic_consume($queueName, '', false, true, false, false, $callback);

        $this->handle();
    }

}
