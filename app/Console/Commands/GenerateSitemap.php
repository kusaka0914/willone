<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Model\Blog;
use App\Model\Company;
use App\Model\Kaitou;
use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use App\Model\ParameterMaster;
use App\Model\Staff;
use App\Model\StaffExample;
use App\Model\WoaAreaConditionAggregate;
use App\Model\WoaOpportunity;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * サイトマップ生成
 */
class GenerateSitemap extends Command
{

    // 駅から徒歩5分以内 ルート名、出力基準の求人数
    private const AREA_STATE_SELECT_EKICHIKA5 = 'AreaStateSelectEkichika5';
    private const JOB_AREA_SELECT_EKICHIKA5 = 'JobAreaSelectEkichika5';
    private const JOB_AREA_STATE_SELECT_EKICHIKA5 = 'JobAreaStateSelectEkichika5';
    // ブラウザ上でのサイトマップのURL
    private const SITEMAP_BROWSER_ACCESS_URL = 'https://static.jinzaibank.com/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GenerateSitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'サイトマップ生成';

    // 引数(出力タイプ)
    private $logger;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WoaOpportunity $woaOpportunity, MasterAddr1Mst $mstAddr1, MasterAddr2Mst $mstAddr2, ParameterMaster $parameter, Company $company, Blog $blog, Kaitou $kaitou, Staff $staff, WoaAreaConditionAggregate $woaAreaConditionAggregate)
    {
        parent::__construct();
        $this->woaOpportunity = $woaOpportunity;
        $this->mstAddr1 = $mstAddr1;
        $this->mstAddr2 = $mstAddr2;
        $this->parameter = $parameter;
        $this->company = $company;
        $this->blog = $blog;
        $this->kaitou = $kaitou;
        $this->staff = $staff;
        $this->woaAreaConditionAggregate = $woaAreaConditionAggregate;
    }

    /**
     * 初期化
     *
     * @return void
     */
    private function init()
    {
        $className = class_basename(get_class());
        $classNameSnake = Str::snake($className);
        $this->logger = new BatchLogger($className, "{$classNameSnake}.log");
        $this->ekichika5List = [
            self::AREA_STATE_SELECT_EKICHIKA5     => [],
            self::JOB_AREA_SELECT_EKICHIKA5       => [],
            self::JOB_AREA_STATE_SELECT_EKICHIKA5 => [],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 初期化
        $this->init();

        // サイトマップフォルダが存在しなければ作成
        $path_sitemap = base_path('public/sitemap/');
        if (!is_dir($path_sitemap)) {
            mkdir($path_sitemap, 0755, true);
        }

        $this->logger->info('処理開始');
        // サイトマップ作成
        $this->createSitemap();

        // Sentryエラー通知
        if ($this->logger->countError() > 0) {
            $this->logger->notifyErrorToSentry();
            $this->logger->info('処理終了');

            return Command::FAILURE;
        }

        $this->logger->info('処理終了');

        return Command::SUCCESS;
    }

    /**
     * XML作成処理の初期化
     *
     */
    private function setUpCreateXml()
    {
        // XML定義
        $this->sitemap = new DOMDocument('1.0', 'UTF-8');
        $this->sitemap->preserveWhiteSpace = false;
        $this->sitemap->formatOutput = true;

        $this->urlset = $this->sitemap->appendChild($this->sitemap->createElement("urlset"));
        $this->urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    }

    /**
     * サイトマップ作成
     */
    private function createSitemap()
    {
        $createSitemapFile = [];
        $indexSyokusyuList = config('ini.INDEX_JOB_TYPE');
        // 1ページの表示件数（一覧ページ用）
        $limit = config('ini.DEFAULT_OFFSET');
        $this->setUpCreateXml();
        // トップ
        $this->add(['loc' => $this->generateUrl('/')]);

        // エリアトップ
        $this->add(['loc' => $this->generateUrl('/area')]);

        // 職種トップ
        $this->add(['loc' => $this->generateUrl('/job')]);

        // 新着ページ
        $this->add(['loc' => $this->generateUrl('/new')]);
        // 職種
        foreach ($indexSyokusyuList as $syokusyuName) {
            $this->add(['loc' => $this->generateUrl('/job/' . $syokusyuName)]);
        }
        $tmpFileName = 'sitemap/willone_sitemap-top.xml';
        $this->generate(public_path($tmpFileName));
        $createSitemapFile[] = $tmpFileName;
        $this->setUpCreateXml();
        // 職種別新着求人一覧
        foreach ($indexSyokusyuList as $syokusyuName) {
            $cond = ['job_type_group' => $syokusyuName];
            $count = $this->woaOpportunity->jobSearchCount($cond);
            $count = ceil($count / $limit);
            if ($count > 0) {
                for ($i = 1; $i <= $count; $i++) {
                    if ($i == 1) {
                        $this->add(['loc' => $this->generateUrl('/job', [$syokusyuName, 'new'])]);
                    }
                }
            }
        }
        $tmpFileName = 'sitemap/willone_sitemap-new.xml';
        $this->generate(public_path($tmpFileName));
        $createSitemapFile[] = $tmpFileName;

        // 求人を持つ都道府県一覧の取得
        $prefList = $this->mstAddr1->getList();

        // 職種×都道府県一覧ページ
        foreach ($indexSyokusyuList as $syokusyuName) {
            $this->setUpCreateXml();
            // 求人を持つ職種×都道府県一覧の取得
            $getPrefDataList = $this->mstAddr1->getJobCountList($syokusyuName);
            foreach ($getPrefDataList as $prefData) {
                $count = ceil($prefData->order_count / $limit);
                for ($i = 1; $i <= $count; $i++) {
                    if ($i == 1) {
                        $this->add(['loc' => $this->generateUrl('/job', [$syokusyuName, $prefData->addr1_roma])]);
                    }
                }
            }
            $tmpFileName = 'sitemap/willone_sitemap-prefecture-' . $syokusyuName . '.xml';
            $this->generate(public_path($tmpFileName));
            $createSitemapFile[] = $tmpFileName;
        }

        // 職種×都道府県×市区町村一覧ページ
        foreach ($indexSyokusyuList as $syokusyuName) {
            $this->setUpCreateXml();
            // 求人を持つ職種×都道府県一覧の取得
            $getPrefDataList = $this->mstAddr1->getJobCountList($syokusyuName);
            foreach ($getPrefDataList as $prefData) {
                // 求人を持つ職種×市区町村一覧の取得
                $stateDataList = $this->mstAddr2->getJobCountList($prefData['id'], $syokusyuName);
                foreach ($stateDataList as $stateData) {
                    $count = ceil($stateData->order_count / $limit);
                    for ($i = 1; $i <= $count; $i++) {
                        if ($i == 1) {
                            $this->add(['loc' => $this->generateUrl('/job', [$syokusyuName, $prefData->addr1_roma, $stateData->addr2_roma])]);
                        }
                    }
                }
            }
            $tmpFileName = 'sitemap/willone_sitemap-prefecture-states-' . $syokusyuName . '.xml';
            $this->generate(public_path($tmpFileName));
            $createSitemapFile[] = $tmpFileName;
        }

        // 配列に格納
        $prefListArray = [];
        foreach ($prefList as $one) {
            $prefListArray[$one->id] = $one->addr1_roma;
        }

        // 職種×都道府県×勤務形態
        foreach ($indexSyokusyuList as $syokusyuName) {
            $this->setUpCreateXml();
            // 勤務形態
            $params = ['conditions' => 'employ', 'job_type' => $syokusyuName];
            $employData = $this->woaAreaConditionAggregate->getAggregateData($params);

            foreach ($employData as $one) {
                if (!empty($one->addr2_roma)) {
                    // 次の改修で下記が必要になるので今はコメントアウト
                    // $this->add(['loc' => $this->generateUrl('/job', [$one->job_type, $prefListArray[$one->addr1], $one->addr2_roma, $one->conditions . '_' . $one->search_key])]);
                } else {
                    $this->add(['loc' => $this->generateUrl('/job', [$one->job_type, $prefListArray[$one->addr1], $one->conditions . '_' . $one->search_key])]);
                }
            }
            $tmpFileName = 'sitemap/willone_sitemap-prefecture-employment-' . $syokusyuName . '.xml';
            $this->generate(public_path($tmpFileName));
            $createSitemapFile[] = $tmpFileName;
        }
        // 職種×都道府県×施設形態
        foreach ($indexSyokusyuList as $syokusyuName) {
            $this->setUpCreateXml();
            $params = ['conditions' => 'business', 'job_type' => $syokusyuName];
            $businessData = $this->woaAreaConditionAggregate->getAggregateData($params);

            foreach ($businessData as $one) {
                if (!empty($one->addr2_roma)) {
                    // $this->add(['loc' => $this->generateUrl('/job', [$one->job_type, $prefListArray[$one->addr1], $one->addr2_roma, $one->conditions . '_' . $one->search_key])]);
                } else {
                    $this->add(['loc' => $this->generateUrl('/job', [$one->job_type, $prefListArray[$one->addr1], $one->conditions . '_' . $one->search_key])]);
                }
            }
            $tmpFileName = 'sitemap/willone_sitemap-prefecture-business-' . $syokusyuName . '.xml';
            $this->generate(public_path($tmpFileName));
            $createSitemapFile[] = $tmpFileName;
        }
        // 資格X都道府県X駅ちか5分
        foreach ($indexSyokusyuList as $syokusyuName) {
            $this->setUpCreateXml();
            $params = ['conditions' => 'ekichika5', 'job_type' => $syokusyuName];
            $ekichika5Data = $this->woaAreaConditionAggregate->getAggregateData($params);

            foreach ($ekichika5Data as $one) {
                if (empty($one->addr2_roma)) {
                    $this->add(['loc' => $this->generateUrl('/job', [$one->job_type, $prefListArray[$one->addr1], 'ekichika5'])]);
                }
            }
            $tmpFileName = 'sitemap/willone_sitemap-prefecture-ekichika5-' . $syokusyuName . '.xml';
            $this->generate(public_path($tmpFileName));
            $createSitemapFile[] = $tmpFileName;
        }

        // 資格X市区町村X駅ちか5分
        foreach ($indexSyokusyuList as $syokusyuName) {
            $this->setUpCreateXml();
            $params = ['conditions' => 'ekichika5', 'job_type' => $syokusyuName];
            $ekichika5Data = $this->woaAreaConditionAggregate->getAggregateData($params);

            foreach ($ekichika5Data as $one) {
                if (!empty($one->addr2_roma)) {
                    $this->add(['loc' => $this->generateUrl('/job', [$one->job_type, $prefListArray[$one->addr1], $one->addr2_roma, 'ekichika5'])]);
                }
            }
            $tmpFileName = 'sitemap/willone_sitemap-prefecture-states-ekichika5-' . $syokusyuName . '.xml';
            $this->generate(public_path($tmpFileName));
            $createSitemapFile[] = $tmpFileName;
        }

        // 会社詳細、求人なしの事業所はサイトマップから削除
        $this->setUpCreateXml();
        $companyList = $this->company->getJobCountList();
        foreach ($companyList as $company) {
            $this->add(['loc' => $this->generateUrl('/company', [$company->id])]);
        }
        $tmpFileName = 'sitemap/willone_sitemap-company.xml';
        $this->generate(public_path($tmpFileName));
        $createSitemapFile[] = $tmpFileName;

        $this->setUpCreateXml();
        // 解答速報関連
        $this->add(['loc' => $this->generateUrl('/kaitousokuhou')]);

        $kaitou_data = $this->kaitou->where('del_flg', 0)->get();
        foreach ($kaitou_data as $value) {
            // URLでなければ、XMLに出力する
            if (!preg_match('/https/', $value->kaitouurl)) {
                $this->add(['loc' => $this->generateUrl('/kaitousokuhou', [$value->kaitouurl])]);
            }
        }

        // ウィルワンを利用された就職・転職者の方々
        $this->add(['loc' => $this->generateUrl('/taikendan/list')]);
        $this->add(['loc' => $this->generateUrl('/taikendan/cp_list')]); // マッチング事例（顧客の声）を集めたページ
        $params = [
            'type'     => 2,
            'open_flg' => 1,
        ];
        $blogdata = $this->blog->getExperienceData($params);
        foreach ($blogdata as $value) {
            $this->add(['loc' => $this->generateUrl('/taikendan', [$value->id])]);
        }

        // 固定ページ
        $this->add(['loc' => $this->generateUrl('/knowhow')]);

        // ノウハウページ
        $knowhow_cate = $this->parameter->where('genre_id', config('const.genre_knowhow'))->get();
        foreach ($knowhow_cate as $value) {
            $this->add(['loc' => $this->generateUrl('/knowhow', [$value->value3])]);
        }

        // スタッフ
        $this->add(['loc' => $this->generateUrl('/staff')]);

        $staff_data = $this->staff->where('del_flg', 0)->get();

        foreach ($staff_data as $value) {
            $this->add(['loc' => $this->generateUrl('/staff', [$value->id])]);
        }

        // スタッフ 事例URL
        $StaffExamples = StaffExample::get();
        foreach ($staff_data as $staffRecord) {
            // urlの"/case{x}"はレコードの順番なので、values()で配列のキーを0にしてからcase1は($keyNum + 1)で実現
            foreach ($StaffExamples->where('staff_id', $staffRecord->id)->values() as $keyNum => $StaffExample) {
                $this->add(['loc' => $this->generateUrl('/staff', [$StaffExample->staff_id, 'case' . ($keyNum + 1)])]);
            }
        }

        // その他ページ
        $this->add(['loc' => $this->generateUrl('/guide')]);
        $this->add(['loc' => $this->generateUrl('/rule')]);
        $this->add(['loc' => $this->generateUrl('/privacy')]);
        $this->add(['loc' => $this->generateUrl('/service')]);
        $this->add(['loc' => $this->generateUrl('/service/ac')]);
        $this->add(['loc' => $this->generateUrl('/service/free')]);
        $this->add(['loc' => $this->generateUrl('/service/feature')]);
        $this->add(['loc' => $this->generateUrl('/service/find')]);
        $this->add(['loc' => $this->generateUrl('/recruit')]);

        $this->add(['loc' => $this->generateUrl('/recommended')]);
        $this->add(['loc' => $this->generateUrl('/contact')]);
        $this->add(['loc' => $this->generateUrl('/access')]);
        $this->add(['loc' => $this->generateUrl('/interview-fti-1')]);
        $this->add(['loc' => $this->generateUrl('/interview-fti-2')]);

        $tmpFileName = 'sitemap/willone_sitemap-etc.xml';
        $this->generate(public_path($tmpFileName));
        $createSitemapFile[] = $tmpFileName;

        $this->setUpCreateXml();
        // 求人詳細
        $jobList = $this->woaOpportunity->getJobIdList();
        foreach ($jobList as $job) {
            $updateDate = Carbon::parse($job->last_confirmed_datetime)->format('Y-m-d');
            $this->add(['loc' => $this->generateUrl('/detail', [$job->job_id . '.html']), 'lastmod' => $updateDate]);
        }

        $tmpFileName = 'sitemap/willone_sitemap-detail.xml';
        $this->generate(public_path($tmpFileName));
        $createSitemapFile[] = $tmpFileName;
        $createSitemapFile = $this->generateSiteMapIndex($createSitemapFile);

        foreach ($createSitemapFile as $one) {
            $this->s3Upload($one);
        }
    }

    /**
     * 全サイトマップを記載したサイトマップを生成する
     * @param array $createSitemapFile
     * @return Array
     */
    public function generateSiteMapIndex(array $createSitemapFile): array
    {
        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $content .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        $url = self::SITEMAP_BROWSER_ACCESS_URL;
        if (!empty($createSitemapFile)) {
            foreach ($createSitemapFile as $one) {
                $content .= "<sitemap>\n";
                $content .= "<loc>" . $url . $one . "</loc>\n";
                $content .= "</sitemap>\n";
            }
        }
        $content .= "</sitemapindex>\n";
        $sitemapName = (public_path('sitemap/willone_sitemap.xml'));
        $file = @fopen($sitemapName, 'w');
        fwrite($file, $content);
        fclose($file);
        $createSitemapFile[] = 'sitemap/willone_sitemap.xml';

        return $createSitemapFile;
    }

    private function add($params)
    {
        $url = $this->urlset->appendChild($this->sitemap->createElement('url'));
        foreach ($params as $key => $value) {
            if (strlen($value)) {
                $url->appendChild($this->sitemap->createElement($key, $value));
            }
        }
    }

    private function generate($file = null)
    {
        if (is_null($file)) {
            header("Content-Type: text/xml; charset=utf-8");
            echo $this->sitemap->saveXML();
        } else {
            $this->sitemap->save($file);
        }
    }

    /**
     * S3アップロード
     * @param string $outputFilePath
     */
    private function s3Upload($outputFilePath)
    {
        // S3アップロードパスを作成
        $s3UploadPath = $outputFilePath;
        // アップロード対象ファイルのパスを作成
        $outputFilePathFull = base_path("public/{$outputFilePath}");

        // S3にファイルをアップロード
        $s3put = \Storage::disk('s3_static')->put($s3UploadPath, file_get_contents($outputFilePathFull));
        if (!$s3put) {
            $this->logger->error("{$outputFilePathFull}をS3にアップロードできませんでした。");
        } else {
            $this->logger->info('S3アップロードOK');
        }
    }

    private function generateUrl($baseString, $pathArray = [])
    {
        foreach ($pathArray as $path) {
            $baseString .= "/" . urlencode($path);
        }

        return url($baseString);
    }
}
