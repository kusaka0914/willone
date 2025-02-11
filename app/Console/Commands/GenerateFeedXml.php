<?php
namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Managers\S3Manager;
use App\UseCases\Commands\GenerateXmlUseCase;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use SplFileObject;

class GenerateFeedXml extends Command
{
    // 返却用
    const SUCCESS = 0;
    const ERROR = -1;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'GenerateFeedXml';

    /**
     * Feed用CSV出力先のS3ディレクトリ
     */
    private const S3_PATH_FOR_FEED = 'export/woa/';

    /**
     * Feed用CSVのファイル名
     */
    private const CSV_FOR_FEED_FILE_NAME = 'woa_opportunity_for_feed.csv';

    /**
     * CSVのヘッダーの位置（行）
     */
    private const CSV_HEADER_POSITION = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 初期化
     *
     * @return void
     */
    private function init()
    {
        // ログ設定
        $className = class_basename(get_class());
        $classNameSnake = Str::snake($className);
        $this->logger = new BatchLogger($className, "{$classNameSnake}.log");
        $this->config = config("batch.generate_feed_xml");

        $this->generateXmlUseCase = new GenerateXmlUseCase($this->logger);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // 初期化
        $this->init();

        // 設定
        mb_internal_encoding('UTF-8');
        mb_language('Japanese');

        // メモリサイズ変更
        ini_set('memory_limit', $this->config["memory_limit"]);

        $this->logger->info('処理開始');

        // FeedのCSVを取得
        $orders = $this->getCsvFeedOrders();
        if (!empty($orders)) {
            // XML作成処理
            $result = ($this->generateXmlUseCase)($orders);
            if (!$result) {
                $this->logger->error("Feed作成の処理を中断しました。ログファイルを確認してください。");
            }
        } else {
            $this->logger->error('対象データが0件のため処理を終了します。');
        }

        // Sentryエラー通知
        if ($this->logger->countError() > 0) {
            $this->logger->notifyErrorToSentry();

            return self::ERROR;
        }

        $this->logger->info('処理終了');

        return self::SUCCESS;
    }

/**
 * 加工されてないCSVのオーダー一覧を取得
 *
 * @return array CSVオーダー一覧
 */
    private function getCsvFeedOrders(): array
    {
        // S3からCSVデータをダウンロード
        $orderStr = (new S3Manager('s3'))->downloadFile(self::S3_PATH_FOR_FEED . self::CSV_FOR_FEED_FILE_NAME);

        $tempFile = tmpfile();
        $meta = stream_get_meta_data($tempFile);

        fwrite($tempFile, $orderStr);

        $fileObj = new SplFileObject($meta['uri']);
        $fileObj->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);

        // ダウンロードデータはここで開放
        unset($orderStr);

        $header = [];
        $orders = [];
        foreach ($fileObj as $index => $data) {
            // CSVヘッダーの処理
            if ($index == self::CSV_HEADER_POSITION) {
                $header = $data;
            } else {
                // データ行が空でないかチェック
                if (!empty($data)) {
                    // データの要素数がヘッダーと異なる場合に、足りない分を空文字列で埋める
                    if (count($header) > count($data)) {
                        $data = array_pad($data, count($header), '');
                    }

                    // ヘッダーの項目名を配列のキー、本体部分の値を配列の値として連想配列型式に変換
                    $order = array_combine($header, $data);
                    if ($order !== false) {
                        $orders[] = $order;
                    } else {
                        // array_combine が失敗した場合のエラーログ
                        $logMessage = sprintf(
                            "array_combine ヘッダーとデータの結合に失敗しました。Line number: %d, Header: %s, Data: %s",
                            $index + 1,
                            implode(', ', $header),
                            implode(', ', $data)
                        );
                        $this->logger->error($logMessage);

                        // スキップして次の行の処理へ
                        continue;
                    }
                }
            }
        }

        return $orders;
    }
}
