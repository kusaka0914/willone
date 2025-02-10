<?php

namespace Tests\Feature;

use App\Model\Staff;
use App\Model\WoaOpportunity;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Tests\TestCase;

class HttpStatusTest extends TestCase
{

    private ?string $targetUrl, $targetUrlWillOne;
    private ?int $jobId, $staffNo;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        if (!($this->isLocal() || $this->isDev())) {
            $this->markTestSkipped('Http status test is not enabled.');
        }
        $this->setTargetUrl();
        $this->setDynamicData();
    }

    /**
     * @dataProvider pcPathProvider
     */
    public function testPcHttpStatus($path, $expectedStatusCode = 200)
    {
        $this->checkHttpStatus($path, $expectedStatusCode, false);
    }

    /**
     * @dataProvider spPathProvider
     */
    public function testSpHttpStatus($path, $expectedStatusCode = 200)
    {
        $this->checkHttpStatus($path, $expectedStatusCode, true);
    }

    /**
     * [PC]個別URL + 共通URL
     *
     * @return array[]
     */
    public static function pcPathProvider(): array
    {
        return array_merge(
            HttpStatusTest::commonPathProviderList(),
            HttpStatusTest::entryList('PC'),
        );
    }

    /**
     * [SP]個別URL + 共通URL
     *
     * @return array[]
     */
    public static function spPathProvider(): array
    {
        return array_merge(
            HttpStatusTest::commonPathProviderList(),
            HttpStatusTest::entryList('SP'),
        );
    }

    /**
     * [PC/SP]共通URL
     *
     * @return array[]
     */
    public static function commonPathProviderList(): array
    {
        return [
            '全体TOP、個別TOP'                                   => [''],
            '求人一覧 /'                                          => ['/search'],
            '求人一覧 /1'                                         => ['/search/1'],
            '求人一覧 /2'                                         => ['/search/2'],
            '求人一覧 /9999'                                      => ['/search/9999'],
            '新着 /'                                                => ['/new'],
            '新着 /1'                                               => ['/new/1'],
            '新着 /2'                                               => ['/new/2'],
            '新着 /9999'                                            => ['/new/9999'],
            'ブランクOK /'                                        => ['/blankok'],
            'ブランクOK /1'                                       => ['/blankok/1'],
            'ブランクOK /2'                                       => ['/blankok/2'],
            'ブランクOK /9999'                                    => ['/blankok/9999'],
            'エリア新着 /'                                       => ['/area/tokyo/new'],
            'エリア新着 /1'                                      => ['/area/tokyo/new/1'],
            'エリア新着 /2'                                      => ['/area/tokyo/new/2'],
            'エリア新着 /9999'                                   => ['/area/tokyo/new/9999'],
            'エリアブランクOK /'                               => ['/area/tokyo/blank'],
            'エリアブランクOK /1'                              => ['/area/tokyo/blank/1'],
            'エリアブランクOK /2'                              => ['/area/tokyo/blank/2'],
            'エリアブランクOK /9999'                           => ['/area/tokyo/blank/9999'],
            '職種新着 /'                                          => ['/job/judoseifukushi/new'],
            '職種新着 /1'                                         => ['/job/judoseifukushi/new/1'],
            '職種新着 /2'                                         => ['/job/judoseifukushi/new/2'],
            '職種新着 /9999'                                      => ['/job/judoseifukushi/new/9999'],
            '職種ブランクOK /'                                  => ['/job/judoseifukushi/blank'],
            '職種ブランクOK /1'                                 => ['/job/judoseifukushi/blank/1'],
            '職種ブランクOK /2'                                 => ['/job/judoseifukushi/blank/2'],
            '職種ブランクOK /9999'                              => ['/job/judoseifukushi/blank/9999'],
            'エリアから探す 都道府県市区町村/'         => ['/area/tokyo/meguroku'],
            'エリアから探す 都道府県市区町村/1'        => ['/area/tokyo/meguroku/1'],
            'エリアから探す 都道府県市区町村/2'        => ['/area/tokyo/meguroku/2'],
            'エリアから探す 都道府県市区町村/9999'     => ['/area/tokyo/meguroku/9999'],
            '都道府県の求人'                                   => ['/area/tokyo'],
            'エリアから探す'                                   => ['/area'],
            'エリア 駅ちか 都道府県市区町村 /'          => ['/area/tokyo/meguroku/ekichika5'],
            'エリア 駅ちか 都道府県市区町村 /1'         => ['/area/tokyo/meguroku/ekichika5/1'],
            'エリア 駅ちか 都道府県市区町村 2'          => ['/area/tokyo/meguroku/ekichika5/2'],
            'エリア 駅ちか 都道府県市区町村 /9999'      => ['/area/tokyo/meguroku/ekichika5/9999'],
            '職種から探すTOP'                                   => ['/job'],
            '職種から探す'                                      => ['/job/judoseifukushi'],
            '職種 都道府県 /'                                   => ['/job/harikyushi/osaka'],
            '職種 都道府県 /1'                                  => ['/job/harikyushi/osaka/1'],
            '職種 都道府県 /2'                                  => ['/job/harikyushi/osaka/2'],
            '職種 都道府県 /9999'                               => ['/job/harikyushi/osaka/9999'],
            '職種 都道府県市区町村 /'                       => ['/job/judoseifukushi/osaka/higashiosakashi'],
            '職種 都道府県市区町村 /1'                      => ['/job/judoseifukushi/osaka/higashiosakashi/1'],
            '職種 都道府県市区町村 /2'                      => ['/job/judoseifukushi/osaka/higashiosakashi/2'],
            '職種 都道府県市区町村 /9999'                   => ['/job/judoseifukushi/osaka/higashiosakashi/9999'],
            '職種 駅ちか 都道府県'                           => ['/job/judoseifukushi/tokyo/ekichika5'],
            '職種 駅ちか 都道府県市区町村 /'             => ['/job/judoseifukushi/tokyo/edogawaku/ekichika5'],
            '職種 駅ちか 都道府県市区町村 /1'            => ['/job/judoseifukushi/tokyo/edogawaku/ekichika5/1'],
            '職種 駅ちか 都道府県市区町村 /2'            => ['/job/judoseifukushi/tokyo/edogawaku/ekichika5/2'],
            '職種 駅ちか 都道府県市区町村 /9999'         => ['/job/judoseifukushi/tokyo/edogawaku/ekichika5/9999'],
            '職種 都道府県勤務形態 /'                       => ['/job/judoseifukushi/tokyo/employ_fulltime'],
            '職種 都道府県勤務形態 /1'                      => ['/job/judoseifukushi/tokyo/employ_fulltime/1'],
            '職種 都道府県勤務形態 /2'                      => ['/job/judoseifukushi/tokyo/employ_fulltime/2'],
            '職種 都道府県勤務形態 /9999'                   => ['/job/judoseifukushi/tokyo/employ_fulltime/9999'],
            '職種 都道府県市区町村勤務形態 /'           => ['/job/judoseifukushi/tokyo/edogawaku/employ_fulltime'],
            '職種 都道府県市区町村勤務形態 /1'          => ['/job/judoseifukushi/tokyo/edogawaku/employ_fulltime/1'],
            '職種 都道府県市区町村勤務形態 /2'          => ['/job/judoseifukushi/tokyo/edogawaku/employ_fulltime/2'],
            '職種 都道府県市区町村勤務形態 /9999'       => ['/job/judoseifukushi/tokyo/edogawaku/employ_fulltime/9999'],
            '求人詳細'                                            => ['/detail/{job_id}'],
            '求人詳細.html'                                       => ['/detail/{job_id}.html'],
            '運営店舗情報'                                      => ['/company/3601'],
            'コラム'                                               => ['/blog/5342'],
            'コラム /interview'                                    => ['/knowhow/interview/5342'],
            '解答速報'                                            => ['/kaitousokuhou'],
            '過去の解答速報'                                   => ['/kaitousokuhou/kako'],
            '詳細'                                                  => ['/kaitousokuhou/hari26'],
            '体験談'                                               => ['/taikendan'],
            '体験談 一覧 /'                                      => ['/taikendan/list'],
            '体験談 一覧 /1'                                     => ['/taikendan/list/1'],
            '体験談 一覧 /2'                                     => ['/taikendan/list/2'],
            '体験談 一覧 /9999'                                  => ['/taikendan/list/9999'],
            '体験談 /'                                             => ['/taikendan/category/mikeiken'],
            '体験談 /1'                                            => ['/taikendan/category/mikeiken/1'],
            '体験談 /2'                                            => ['/taikendan/category/mikeiken/2'],
            '体験談 /9999'                                         => ['/taikendan/category/mikeiken/9999'],
            '詳細'                                                  => ['/taikendan/4735'],
            '転職活動ノウハウ'                                => ['/knowhow'],
            '一覧'                                                  => ['/knowhow/jobhunting'],
            '詳細'                                                  => ['/knowhow/jobhunting/4433'],
            'スタッフ紹介'                                      => ['/staff'],
            'スタッフ詳細'                                      => ['/staff/{staff_no}'],
            '就業中(既卒)の方へ'                                      => ['/guide'],
            '利用規約'                                            => ['/rule'],
            'プライバシーポリシー'                          => ['/privacy'],
            'iframeで呼ばれている部分'                       => ['/include/ct/_rule.html'],
            'ウィルワンのここがすごい'                    => ['/service'],
            'service ac'                                              => ['/service/ac'],
            'service free'                                            => ['/service/free'],
            'service feature'                                         => ['/service/feature'],
            'service find'                                            => ['/service/find'],
            '治療院の院長・経営者さまへ'                 => ['/recruit'],
            'ウィルワンに人材紹介を申し込む（form）' => ['/recommended'],
            'お問い合わせ'                                      => ['/contact'],
            'select生成'                                            => ['/stateget?pref=26'],
            'アクセス'                                            => ['/access'],
            'メディア管理画面'                                => ['/admin'],
            'ログイン'                                            => ['/admin/login'],
            'ログアウト'                                         => ['/admin/logout'],
            '登録フォーム'                                      => ['/entry/PC_1'],
            '採用担当問合せ'                                   => ['/empinquiry'],
            'エリアの求人数取得API'                          => ['/api/getAreaOrderCount?addr1_id=26&license[]=41'],
            '404ページ about'                                      => ['/about', 404],
            '404ページ column'                                     => ['/column', 404],
            '404ページ seminar'                                    => ['/seminar', 404],
            '404ページ sitemap'                                    => ['/sitemap', 404],
            '404ページ sp/sitemap'                                 => ['/sp/sitemap', 404],
            '掘起し'                                               => ['/re?user=a2nF6000000d0BDIAY7nshi&action=woa_sms_20210707_loop&utm_source=mail&utm_medium=sms&utm_campaign=woa_sms_20210707_loop'],
            '黒本リスト'                                         => ['/re/kurohon?user=6109e8e16ceab'],
            '大阪府特集ページ'                                => ['/osaka2020'],
            'feed1'                                                   => ['{willOneUrl}/detail/stanby/{job_id}/job_type_3/employ_id_1/station_2/sec_0'],
            'feed2'                                                   => ['{willOneUrl}/detail/indeed/{job_id}/job_type_1/employ_id_1/sec_1'],
            'feed3'                                                   => ['{willOneUrl}/detail/stanby/{job_id}.html'],
        ];
    }

    /**
     * [PC/SP]共通URL
     *
     * @return array[]
     */
    public static function entryList(string $device): array
    {

        foreach (glob(__DIR__ . "/../../resources/views/{$device}/entry/*.*") as $blade) {
            $entryNo = basename($blade, '.blade.php');
            if (!is_numeric($entryNo)) {
                continue;
            }
            $data["新規登録{$entryNo}"] = ["/entry/{$device}_{$entryNo}"];
        }

        return $data ?? [];
    }

    /**
     * @param string $path
     * @param int $expectedStatusCode
     * @param bool $isSp
     * @return void
     */
    private function checkHttpStatus(string $path, int $expectedStatusCode, bool $isSp)
    {
        $path = str_replace(['{job_id}', '{staff_no}'], [$this->jobId, $this->staffNo], $path);
        if (false !== strpos($path, '{willOneUrl}')) {
            $path = str_replace('{willOneUrl}', $this->targetUrlWillOne, $path);
        } else {
            $path = $this->targetUrl . $path;
        }
        \Log::debug($path);
        $options = [
            'http_errors' => false,
            'verify'      => false,
        ];
        if ($isSp) {
            $options['headers'] = [
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1',
            ];
        }

        $client = new Client($options);
        try {
            $response = $client->request('GET', $path);
            $statusCode = $response->getStatusCode();
        } catch (GuzzleException $e) {
            var_dump($e);
            $statusCode = false;
        }

        $this->assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * @return void
     * ローカルにアクセスしたい場合はローカルに変更
     * ローカル
     *  https://dev.willoneagent.smsx.io
     * DEV
     *  jinzaibanl.comドメイン：https://development.jinzaibank.com
     *  willone.jpドメイン：https://development.willone.jp
     * edge
     *  jinzaibanl.comドメイン：https://pr-xxxxx.edge.jinzaibank.com
     *  willone.jpドメイン：https://pr-xxxxx.edge.willone.jp
     */
    private function setTargetUrl(): void
    {

        $this->targetUrl = 'https://development.jinzaibank.com/woa';
        $this->targetUrlWillOne = 'https://development.willone.jp';
    }

    private function setDynamicData(): void
    {
        $this->jobId = (new WoaOpportunity)->getJobIdList()[0]->job_id;
        $this->staffNo = Staff::where([['del_flg', 0], ['type', 1]])->get()[0]->id;
    }
}
