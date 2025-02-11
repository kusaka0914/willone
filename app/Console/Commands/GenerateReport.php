<?php

namespace App\Console\Commands;

use App\Facades\Slack;
use App\Model\Company;
use App\Model\FriendReferral;
use App\Model\RegistrationInquiryDetail;
use App\Model\WoaCustomer;
use App\Model\WoaCustomerDigs;
use App\Model\WoaMatching;
use App\Model\WoaOpportunity;
use Illuminate\Console\Command;

class GenerateReport extends Command
{
    private const MEMORY_LIMIT = '512M';
    private $slackChannel = 'generate_report';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GenerateReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '当日[事業所数, 親会社数], 前日、前々日[求職者数, 友達紹介からの登録者数, マッチング数, 掘り起こし登録者数, 求職者からの問い合わせ数]をslackに出力';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', self::MEMORY_LIMIT);

        foreach ([
            ['target' => '事業所数', 'instance' => new WoaOpportunity, 'call_func' => 'totalCountText'],
            ['target' => '親会社数', 'instance' => new Company, 'del_column' => 'del_flg', 'call_func' => 'totalCountText'],
            ['target' => '求職者数', 'instance' => new WoaCustomer(), 'call_func' => 'dayCountText'],
            ['target' => '友達紹介からの登録者数', 'instance' => new FriendReferral(), 'call_func' => 'dayCountText'],
            ['target' => 'マッチング数', 'instance' => new WoaMatching(), 'call_func' => 'dayCountText'],
            ['target' => '掘り起こし登録者数', 'instance' => new WoaCustomerDigs(), 'call_func' => 'dayCountText'],
            ['target' => '求職者からの問い合わせ数（掘り起こしの一部）', 'instance' => new RegistrationInquiryDetail(), 'call_func' => 'dayCountText'],
        ] as $modelInfo) {
            $modelInfo = (object) $modelInfo;
            // $this->totalCountText($modelInfo) こんな感じ
            $message[] = $this->{$modelInfo->call_func}($modelInfo);
        }

        Slack::channel($this->slackChannel)->send('```' . implode("\n", $message) . '```');

        return 0;
    }

    /**
     * 全件カウント
     *
     * @param object $modelInfo
     * @return string
     */
    private function totalCountText(object $modelInfo): string
    {
        $count = $modelInfo->instance->where($modelInfo->del_column ?? 'delete_flag', '0')->count();
        // テキスト例) 事業所数、登録数:6757

        return "{$modelInfo->target}、登録数:{$count}";
    }

    /**
     * 範囲指定カウント
     *
     * @param object $modelInfo
     * @return string
     */
    private function dayCountText(object $modelInfo): string
    {

        foreach ([
            ['day_name' => '前日', 'past' => 1],
            ['day_name' => '前々日', 'past' => 2],
        ] as $whereInfo) {
            $whereInfo = (object) $whereInfo;
            $count = $modelInfo->instance->whereRaw("DATE(regist_date) = (CURDATE() - INTERVAL {$whereInfo->past} DAY) AND delete_flag = 0")->count();
            $message[] = "{$modelInfo->target}（{$whereInfo->day_name}登録データ）:{$count}";
        }
        // テキスト例) 求職者数（前日登録データ）:1\n求職者数（前々日登録データ）:2

        return implode("\n", $message);
    }
}
