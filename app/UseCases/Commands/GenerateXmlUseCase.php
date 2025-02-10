<?php

namespace App\UseCases\Commands;

use App\Services\GenerateFeedXmlService;
use Carbon\Carbon;
use Exception;

class GenerateXmlUseCase
{
    private $logger;

    /** @var GenerateFeedXmlService フィード生成サービス */
    private $generateFeedXmlService;

    /** @var array 行数のカウント情報 */
    private $count;

    /** @var string サイト名 */
    private $siteMeisyou;

    /** @var Carbon 現在の日付 */
    private $date;

    /** @var array 設定情報 */
    private $config;

    /**
     * constructor
     *
     * @param object $logger
     */
    public function __construct(object $logger)
    {
        $this->logger = $logger;
        $this->generateFeedXmlService = new GenerateFeedXmlService($logger);
        $categories = ['indeed', 'stanby_org', 'stanby_ad', 'Kyujinbox'];

        foreach ($categories as $category) {
            $this->count[$category] = [
                'normal'   => 0,
                'abnormal' => 0,
                'eligible' => 0,
            ];
        }
        $this->siteMeisyou = config('ini.SITE_MEISYOU');
        $this->date = Carbon::now();
        $this->config = config("batch.generate_feed_xml");
    }

    /**
     * フィード生成処理の実行
     *
     * @param array $orders
     * @return bool
     */
    public function __invoke(array $orders): bool
    {
        $timeStart = microtime(true);

        if (empty($orders)) {
            $this->logger->error('Error: オーダー配列が空です。処理を中止しました。');

            return false;
        }

        // ディレクトリのパス
        $pathFeed = $this->generateFeedXmlService->prepareFeedDirectory();
        if (!$pathFeed) {
            return false;
        }

        $this->logger->info('Start generate WOA_indeed.xm file:');

        try {
            // indeed前準備処理
            $filteredData = $this->makeFeedIndeed($orders);
            // Feed共通処理
            $retIndeed = $this->generateJobFeedData($pathFeed, $filteredData, 'indeed');
            unset($filteredData);
            // stanby前準備処理
            $filteredDataStanby = $this->makeFeedStanbySmall($orders);
            // Feed共通処理
            $retStanby = $this->generateJobFeedData($pathFeed, $filteredDataStanby, 'stanby_org');
            unset($filteredDataStanby);
            // stanby前準備処理（複数）
            $filteredDataStanby = $this->makeFeedStanbyOrKyujinbox($orders, 'stanby');
            // Feed共通処理
            $retStanby = $this->generateJobFeedData($pathFeed, $filteredDataStanby, 'stanby_ad');
            unset($filteredDataStanby);
            // kyujinbox前準備処理
            $filteredDataKyujinbox = $this->makeFeedStanbyOrKyujinbox($orders, 'kyujinbox');
            // Feed共通処理、実際のファイル名なのでKが大文字
            $retKyujinbox = $this->generateJobFeedData($pathFeed, $filteredDataKyujinbox, 'Kyujinbox');
            unset($filteredDataKyujinbox);
            if ($retIndeed === false || $retStanby === false || $retKyujinbox === false) {
                throw new Exception("求人フィードデータの生成に失敗しました。");
            }

            // アップロード処理
            $this->generateFeedXmlService->uploadToFtp($this->config['ftp_upload']['indeed']);
            $this->generateFeedXmlService->uploadToFtp($this->config['ftp_upload']['stanby']);
            $this->generateFeedXmlService->uploadToFtp($this->config['ftp_upload']['stanby_ad']);
            $this->generateFeedXmlService->uploadToFtp($this->config['ftp_upload']['kyujinbox']);
            $this->logger->info('End generate WOA_indeed.xm file:');
        } catch (Exception $e) {
            $this->logger->error("エラーが発生しました: {$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}", true);

            return false;
        }

        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;
        $this->logger->info('Processed in ' . number_format($time, 3, '.', ',') . ' seconds');

        $categories = ['indeed', 'stanby_org', 'stanby_ad', 'Kyujinbox'];
        $statuses = ['normal', 'abnormal', 'eligible'];
        foreach ($categories as $category) {
            foreach ($statuses as $status) {
                $this->logger->info("{$category}_{$status}：" . $this->count[$category][$status] . '件');
            }
        }

        return true;
    }

    /**
     * indeed前準備処理
     *
     * @param array $orders
     * @return array
     */
    private function makeFeedIndeed(array $orders): array
    {
        $filteredData = [];
        foreach ($orders as $order) {
            // 広告集客停止、募集求人、事業所名公開、オーダーPRタイトルcheck
            if ($order['ad_attract_stop_flag'] == '0' && $order['exist_order_flag'] == '1' && $order['account_name_flag'] == '公開' && !empty($order['order_pr_title'])) {
                // job_type_name の取得
                $jobTypeId = explode(',', $order['job_type'] ?? '')[0] ?? null;

                // jobTypeId が "99" でない場合に処理を続ける
                if ($jobTypeId !== "99") {
                    $jobTypeName = $this->config['job_type_mapping'][$jobTypeId] ?? '';
                    // employ_name の生成
                    $employId = explode(',', $order['employ'] ?? '')[0] ?? null;
                    $employName = $this->config['employ_mapping'][$employId] ?? '';

                    // station生成
                    $stations = array_filter([$order['station1'] ?? '', $order['station2'] ?? '', $order['station3'] ?? '']);
                    $station = implode('/', array_map(fn($station) => $station . "駅", $stations));

                    // URLの生成
                    $param1 = "job_type=0";
                    $param2 = "employ_id_" . ($employId ?? "0");
                    $param3 = ($order['publicly_flag'] == 1) ? "sec=1" : "sec=0";

                    $param = "?" . $param1 . "&" . $param2;
                    $param = ".html" . $param . "&" . $param3;

                    $url = "https://www.willone.jp/detail/indeed/{$order['job_id']}" . $param;

                    // 事業所名の生成
                    $companyNameTmp = $order['account_name_flag'] != "公開" ? "{$order['addr2_name']}の{$order['business']} 非公開求人" : ($order['company_name'] ? "{$order['company_name']}{$order['office_name']}" : $order['office_name']);

                    // 郵便番号の生成
                    $tmpPostcode = str_replace(['-', '〒'], '', $order['postal_code'] ?? '');

                    // フィルタされたデータに追加
                    $filteredData[] = array_merge($order, [
                        'job_type_name'        => $jobTypeName,
                        'employ_name'          => $employName,
                        'station'              => $station,
                        'url'                  => $url,
                        'display_company_name' => $companyNameTmp,
                        'display_postcode'     => $tmpPostcode,
                    ]);
                }
            }
        }

        return $filteredData;
    }

    /**
     * stanby前準備処理
     *
     * @param array $orders
     * @return array
     */
    private function makeFeedStanbySmall(array $orders): array
    {
        $filteredData = [];
        foreach ($orders as $order) {
            // 広告集客停止、募集求人、事業所名公開、オーダーPRタイトルcheck
            if ($order['ad_attract_stop_flag'] == '0' && $order['exist_order_flag'] == '1' && $order['account_name_flag'] == '公開') {
                $jobTypeIds = explode(',', $order['job_type']);
                $employIds = explode(',', $order['employ']);
                // station生成
                $station = $order['station1'] ? $order['station1'] . '駅' : '';
                foreach ($jobTypeIds as $jobType) {
                    foreach ($employIds as $employId) {
                        // jobTypeId が "99" の場合は処理を終了
                        if ($jobType == "99") {
                            continue;
                        }
                        $jobTypeName = $this->config['job_type_mapping'][$jobType] ?? '';
                        // employ_name の生成
                        $employName = $this->config['employ_mapping'][$employId] ?? '';
                        // URLの生成
                        $param1 = "job_type_" . $jobType;
                        $param2 = "employ_id_" . ($employId ?? "0");
                        $param3 = $order['account_name_flag'] == "公開" ? 'sec_1' : 'sec_0';
                        $param = "/" . $param1 . '/' . $param2 . '/' . $param3;
                        $url = "https://www.willone.jp/detail/stanby/{$order['job_id']}" . $param;
                        $applayParam = $order['account_name_flag'] == "公開" ? 1 : 0;
                        $applyUrl = "https://www.willone.jp/glp/stanby_01/?job_id={$order['job_id']}&action=woa_stanby_apply&utm_source=feed&utm_medium=cpc&utm_campaign=stanby&sec=" . $applayParam;
                        // 事業所名の生成
                        $companyNameTmp = $order['account_name_flag'] != "公開" ? "{$order['addr2_name']}の{$order['business']} 非公開求人" : $order['company_name'] . $order['office_name'];
                        // 郵便番号の生成
                        $tmpPostcode = str_replace(['-', '〒'], '', $order['postal_code'] ?? '');
                        $order['job_type'] = $jobType;
                        $order['employ'] = $employId;
                        // フィルタされたデータに追加
                        $filteredData[] = array_merge($order, [
                            'job_type_name'        => $jobTypeName,
                            'employ_name'          => $employName,
                            'station'              => $station,
                            'url'                  => $url,
                            'apply_url'            => $applyUrl,
                            'display_company_name' => $companyNameTmp,
                            'display_postcode'     => $tmpPostcode,
                        ]);
                    }
                }
            }
        }
        return $filteredData;
    }

    /**
     * stanby前準備処理
     *
     * @param array $orders
     * @param string $feedType
     * @return array
     */
    private function makeFeedStanbyOrKyujinbox(array $orders, string $feedType): array
    {
        $filteredData = [];
        foreach ($orders as $order) {
            // 条件チェック：広告集客停止、募集求人、事業所名公開
            if ($order['ad_attract_stop_flag'] !== '0' || $order['exist_order_flag'] !== '1' || $order['account_name_flag'] !== '公開') {
                continue;
            }
            $jobTypeIds = explode(',', $order['job_type']);
            $employIds = explode(',', $order['employ']);
            // station生成
            $stations = array_filter([$order['station1'] ?? '', $order['station2'] ?? '', $order['station3'] ?? '']);
            // 配列の各要素に「駅」を追加する
            $withStations = array_map(function ($value) {
                return $value . '駅';
            }, $stations);
            $stationString = implode('/', $withStations);
            // 配列の先頭に結合した文字列を追加
            array_unshift($withStations, $stationString);
            $publiclyFlag = [1, 0];
            if (empty($withStations)) {
                $withStations = [''];
            }
            // jobTypeId が "99" の場合は処理をスキップ
            $jobTypeIds = array_filter(explode(',', $order['job_type']), function ($jobType) {
                return $jobType !== "99";
            });
            // 駅名生成
            $stations = array_filter([$order['station1'] ?? '', $order['station2'] ?? '', $order['station3'] ?? '']);
            $withStations = array_map(fn($station) => $station . '駅', $stations);
            $stationString = implode('/', $withStations);
            array_unshift($withStations, $stationString);
            foreach ($jobTypeIds as $jobType) {
                foreach ($employIds as $employId) {
                    foreach ($withStations as $stationCnt => $station) {
                        foreach ($publiclyFlag as $publiclyOne) {
                            $jobTypeName = $this->config['job_type_mapping'][$jobType] ?? '';
                            // employ_name の生成
                            $employName = $this->config['employ_mapping'][$employId] ?? '';
                            // URLの生成
                            $params = [
                                "job_type_" . $jobType,
                                "employ_id_" . ($employId ?? "0"),
                                "station_" . $stationCnt,
                                $publiclyOne == 1 ? "sec_1" : "sec_0"
                            ];
                            $paramString = '/' . implode('/', $params);
                            $applayParam = $publiclyOne == 1 ? 1 : 0;
                            if ($feedType == 'stanby') {
                                $urlBase = "https://www.willone.jp/detail/stanby/";
                                $url = $urlBase . $order['job_id'] . $paramString;
                                $applyUrl = "https://www.willone.jp/glp/stanby_01/?job_id={$order['job_id']}&action=woa_stanby_apply&utm_source=feed&utm_medium=cpc&utm_campaign=stanby&sec=" . $applayParam;
                            } else {
                                $url = "https://www.willone.jp/glp/kyujinbox_01/?job_id={$order['job_id']}&utm_source=feed&utm_medium=cpc&utm_campaign=kyujinbox&sec=" . $applayParam;
                                $applyUrl = $url;
                            }
                            // 事業所名の生成
                            $companyNameTmp = $order['account_name_flag'] != "公開" ? "{$order['addr2_name']}の{$order['business']} 非公開求人" : $order['company_name'] . $order['office_name'];
                            // 郵便番号の生成
                            $tmpPostcode = str_replace(['-', '〒'], '', $order['postal_code'] ?? '');
                            $order['job_type'] = $jobType;
                            $order['employ'] = $employId;
                            // フィルタされたデータに追加
                            $filteredData[] = array_merge($order, [
                                'job_type_name'        => $jobTypeName,
                                'employ_name'          => $employName,
                                'station'              => $station,
                                'url'                  => $url,
                                'apply_url'            => $applyUrl,
                                'display_company_name' => $companyNameTmp,
                                'display_postcode'     => $tmpPostcode,
                                'station_cnt'          => $stationCnt,
                            ]);
                        }
                    }
                }
            }
        }
        return $filteredData;
    }

    /**
     * Feed共通処理
     *
     * @param string $feedPath
     * @param array $filteredData
     * @param string $type
     * @return bool
     */
    private function generateJobFeedData(string $feedPath, array $filteredData, string $type): bool
    {
        $file = null;
        $xmlFileName = 'feed_' . $type;
        $uplodFileName = 'WOA_' . $type . '.xml';

        try {
            $this->generateFeedXmlService->deleteExistingFile($feedPath . $xmlFileName . ".xml");

            // 所定ディレクトリ内のファイルを削除
            $xmlFile = $feedPath . $xmlFileName . ".xml";
            if (file_exists($xmlFile)) {
                unlink($xmlFile);
            }

            $tmpFeed = $feedPath . $xmlFileName . "_temp.xml";
            $file = @fopen($tmpFeed, 'w');
            if (!$file) {
                throw new Exception("Failed to open file: {$tmpFeed}");
            }

            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content .= "\n<source>\n<publisher>" . $this->siteMeisyou . '</publisher>';
            $content .= "\n<publisherurl>" . config('app.url') . "</publisherurl>";
            $content .= "\n<lastBuildDate>{$this->date->format('Y-n-j')}</lastBuildDate>";

            fwrite($file, $content);

            if (!empty($filteredData)) {
                foreach ($filteredData as $feedData) {
                    if ($type == 'indeed') {
                        $content = $this->generateIndeedSpecificData($feedData);
                    } elseif ($type == 'stanby_org' || $type == 'stanby_ad') {
                        $content = $this->generateStanbySpecificData($feedData);
                    } elseif ($type == 'Kyujinbox') {
                        $content = $this->generateKyujinboxSpecificData($feedData);
                    }
                    // 各行をカウントし、ファイルに書き込む処理を行う
                    list($file, $this->count[$type]) = $this->generateFeedXmlService->countLine($content, $file, $this->count[$type]);
                }
            }
            fwrite($file, "\n</source>");
            fclose($file);

            if (!rename($tmpFeed, $feedPath . $uplodFileName)) {
                throw new Exception("Failed to rename XML file." . $uplodFileName);
            }
        } catch (Exception $e) {
            $this->logger->error("{$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}", true);
            $this->count[$type]['eligible']++;

            return false;
        } finally {
            if ($file && is_resource($file)) {
                fclose($file);
            }
        }

        return true;
    }

    private function generateKyujinboxSpecificData(array $feedData)
    {
        $title = $feedData['job_type_name'] . "/" . $feedData['business'] . "/" . $feedData['addr2_name'] . "/" . $feedData['employ_name'];
        $jobId = $feedData['job_id'] ?? '';
        $jobTypeId = explode(',', $feedData['job_type'] ?? '')[0] ?? '';
        $employId = explode(',', $feedData['employ'] ?? '')[0] ?? '';
        $businessId = $feedData['business_id'] ?? '';
        $url = $feedData['url'] ?? '';
        $secFlag = strpos($url, 'sec=0') !== false ? '0' : '1';
        $referencenumber = "{$jobId}-{$jobTypeId}-{$employId}-{$businessId}-{$secFlag}-{$feedData['station_cnt']}";
        $company = "";
        if (strpos($url, 'sec=0') !== false) {
            // 'sec_0' が含まれている場合、市区町村と施設形態を結合して返す
            $company = $feedData['addr2_name'] . "の" . $feedData['business'] . '  非公開求人';
        } else {
            $company = $feedData['display_company_name'] ?? '';
        }

        $facility = strpos($feedData['business'], '施術所') !== false ? '施術所' : 'その他施設';
        // 各フィールドの追加
        $addData = [
            'state'      => $feedData['addr1_name'] ? $feedData['addr1_name'] : 'お問合せください',
            'city'      => $feedData['addr2_name'] ? $feedData['addr2_name'] : 'お問合せください',
        ];
        $imageUrl = '';
        $linefeed = "\n";
        $jobInformation = !empty($feedData['order_pr_title']) ? $feedData['order_pr_title'] : 'お問い合わせください';
        $jobInformation .= $linefeed . $linefeed . '★☆「応募画面へ進む」ボタンよりお問い合わせ頂くと、更に詳細な情報をご確認頂けます☆★' . $linefeed;
        $jobInformation .= '※事業所への応募ではないので、お気軽にお問い合わせください。';

        $description = !empty($feedData['detail']) ? $linefeed . $feedData['detail'] : 'お問い合わせください';

        $content = "\n" . "<job>";
        $content .= "\n" . "<title><![CDATA[{$title}]]></title>";
        $content .= "\n" . "<date><![CDATA[{$this->date->format('Y-m-d H:i:s')}]]></date>";
        $content .= "\n" . "<referencenumber><![CDATA[{$referencenumber}]]></referencenumber>";
        $content .= "\n" . "<url><![CDATA[{$feedData['url']}]]></url>";
        $content .= "\n" . "<company><![CDATA[{$company}]]></company>";
        $content .= "\n" . "<country><![CDATA[日本]]></country>";
        $content .= "\n" . "<state><![CDATA[{$addData['state']}]]></state>";
        $content .= "\n" . "<city><![CDATA[{$addData['city']}]]></city>";
        $content .= "\n" . "<address><![CDATA[{$feedData['addr1_name']}{$feedData['addr2_name']}]]></address>";
        $content .= "\n" . "<station><![CDATA[{$feedData['station']}]]></station>";
        $content .= "\n" . "<description><![CDATA[{$description}]]></description>";
        $content .= "\n" . "<postDate><![CDATA[{$this->date->format('Y-m-d H:i:s')}]]></postDate>";
        $content .= "\n" . "<salary><![CDATA[{$feedData['salary']}]]></salary>";
        $content .= "\n" . "<holiday><![CDATA[{$feedData['dayoff']}]]></holiday>";
        $content .= "\n" . "<agency><![CDATA[株式会社エス・エム・エス]]></agency>";
        $content .= "\n" . "<education><![CDATA[高卒以上]]></education>";
        $content .= "\n" . "<jobtype><![CDATA[{$feedData['employ_name']}]]></jobtype>";
        $content .= "\n" . "<experience><![CDATA[{$feedData['job_type_name']}]]></experience>";
        $content .= "\n" . "<workingHours><![CDATA[{$feedData['worktime']}]]></workingHours>";
        $content .= "\n" . "<jobInformation><![CDATA[{$jobInformation}]]></jobInformation>";
        $content .= "\n" . "<applyUrl><![CDATA[{$feedData['apply_url']}]]></applyUrl>";
        $content .= "\n" . "<category><![CDATA[{$facility}]]></category>";
        $content .= "\n" . "<freetext><![CDATA[詳しくは登録から]]></freetext>";
        $content .= "\n" . "<imageUrls><![CDATA[{$imageUrl}]]></imageUrls>";

        $content .= "\n" . "</job>";
        return $content;
    }

    /**
     * Indeed用XMLを生成する
     *
     * @param array $indeedData
     * @return string
     */
    private function generateIndeedSpecificData(array $indeedData): string
    {
        $title = $indeedData['job_type_name'];

        // 参照番号作成
        // 必要なフィールドを取得
        $jobId = $indeedData['job_id'] ?? '';
        $jobTypeId = explode(',', $indeedData['job_type'] ?? '')[0] ?? '';
        $employId = explode(',', $indeedData['employ'] ?? '')[0] ?? '';
        $businessId = $indeedData['business_id'] ?? '';
        $url = $indeedData['url'] ?? '';
        $referencenumber = "";
        // URLの条件に基づくフラグ設定
        $secFlag = strpos($url, 'sec=0') !== false ? '0' : '1';
        $stationFlag = strpos($url, 'station=0') !== false ? '0' : (strpos($url, 'station=1') !== false ? '1' : (strpos($url, 'station=2') !== false ? '2' : '3'));
        $newFlag = strpos($url, 'new') !== false ? '1' : '0';

        // 識別子の生成
        $referencenumber = "{$jobId}-{$jobTypeId}-{$employId}-{$businessId}-{$secFlag}-{$stationFlag}-{$newFlag}";

        // apply-url
        $applyurl = strpos($url, 'sec_0') !== false ? '' : '';

        // company
        // URLに 'sec_0' が含まれているかをチェック
        $company = "";
        if (strpos($url, 'sec_0') !== false) {
            // 'sec_0' が含まれている場合、市区町村と施設形態を結合して返す
            $company = $indeedData['addr2_name'] . "の" . $indeedData['business'];
        } else {
            $company = $indeedData['display_company_name'] ?? '';
        }

        // description
        $linefeed = "\n";
        $description = "";
        // sec_0 判定
        if (strpos($url, 'sec_0') === false) {
            $description .= $linefeed . "※応募者様にはウィルワンエージェントより、選考のためのご連絡を差し上げます。" . $linefeed . $linefeed;
            $description .= "【この求人のおすすめポイント】" . $linefeed;
            $description .= !empty($indeedData['order_pr_title']) ? $indeedData['order_pr_title'] : "";
            $description .= $linefeed;
        }

        // 各フィールドの追加
        $description .= "{$linefeed}【給与】{$linefeed}" . (!empty($indeedData['salary']) ? $indeedData['salary'] : '') . "{$linefeed}{$linefeed}";
        $description .= "【募集職種】{$linefeed}" . (!empty($indeedData['job_type_name']) ? $indeedData['job_type_name'] : '') . "{$linefeed}{$linefeed}";
        $description .= "【雇用形態】{$linefeed}" . (!empty($indeedData['employ_name']) ? $indeedData['employ_name'] : '') . "{$linefeed}{$linefeed}";
        $description .= "【施設名】{$linefeed}" . (!empty($company) ? $company : '') . "{$linefeed}{$linefeed}";

        $description .= "【勤務地】{$linefeed}" . (!empty($indeedData['addr1_name']) ? $indeedData['addr1_name'] : '') . (!empty($indeedData['addr2_name']) ? $indeedData['addr2_name'] : '') . "{$linefeed}{$linefeed}";
        $description .= "【最寄り駅】{$linefeed}" . (!empty($indeedData['station']) ? $indeedData['station'] : '') . "{$linefeed}{$linefeed}";
        $description .= "【仕事内容】{$linefeed}" . (!empty($indeedData['detail']) ? $indeedData['detail'] : 'お問合せ下さい') . "{$linefeed}{$linefeed}";
        $description .= "【休日・休暇】{$linefeed}" . (!empty($indeedData['dayoff']) ? $indeedData['dayoff'] : 'お問合せ下さい') . "{$linefeed}";

        // category
        $sec = strpos($url, 'sec_0') !== false ? 'sec0' : 'sec1';
        $facility = strpos($indeedData['business'], '施術所') !== false ? '施術所' : '非施術所';
        $category = "{$sec}_{$facility}";

        $content = "\n" . "<job>";
        $content .= "\n" . "<title><![CDATA[{$title}]]></title>";
        $content .= "\n" . "<date><![CDATA[{$this->date->format('Y-m-d H:i:s')}]]></date>";
        $content .= "\n" . "<referencenumber><![CDATA[{$referencenumber}]]></referencenumber>";
        $content .= "\n" . "<url><![CDATA[{$indeedData['url']}]]></url>";
        $content .= "\n" . "<apply-url><![CDATA[{$applyurl}]]></apply-url>";
        $content .= "\n" . "<company><![CDATA[{$company}]]></company>";
        $content .= "\n" . "<city><![CDATA[{$indeedData['addr2_name']}]]></city>";
        $content .= "\n" . "<state><![CDATA[{$indeedData['addr1_name']}]]></state>";
        $content .= "\n" . "<country><![CDATA[日本]]></country>";
        $content .= "\n" . "<station><![CDATA[{$indeedData['station']}]]></station>";
        $content .= "\n" . "<postalcode><![CDATA[{$indeedData['display_postcode']}]]></postalcode>";
        $content .= "\n" . "<description><![CDATA[{$description}]]></description>";
        $content .= "\n" . "<salary><![CDATA[{$indeedData['salary']}]]></salary>";
        $content .= "\n" . "<education><![CDATA[高卒以上]]></education>";
        $content .= "\n" . "<jobtype><![CDATA[{$indeedData['employ_name']}]]></jobtype>";
        $content .= "\n" . "<experience><![CDATA[{$title}]]></experience>";
        $content .= "\n" . "<timeshift><![CDATA[{$indeedData['worktime']}]]></timeshift>";
        $content .= "\n" . "<keywords><![CDATA[G3CST,JCEKM,JREW5,PM67F]]></keywords>";
        $content .= "\n" . "<category><![CDATA[{$category}]]></category>";
        $content .= "\n" . "</job>";

        return $content;
    }

    /**
     * Indeed用XMLを生成する
     *
     * @param array $feedData
     * @return string
     */
    private function generateStanbySpecificData(array $feedData): string
    {
        $url = $feedData['url'] ?? '';
        // 施設形態
        $facility = strpos($feedData['business'], '施術所') !== false ? '施術所' : 'その他施設';
        $title = implode('/', [
            $feedData['job_type_name'],
            $feedData['business'],
            !empty($feedData['order_pr_title']) ? $feedData['order_pr_title'] : ''
        ]);
        // 必要なフィールドを取得
        $jobId = $feedData['job_id'] ?? '';
        $jobTypeId = explode(',', $feedData['job_type'] ?? '')[0] ?? '';
        $employId = explode(',', $feedData['employ'] ?? '')[0] ?? '';
        $businessId = $feedData['business_id'] ?? '';
        // URLの条件に基づくフラグ設定
        $secFlag = strpos($url, 'sec_0') !== false ? '0' : '1';
        $stationFlag = strpos($url, 'station_0') !== false ? '0' : (strpos($url, 'station_1') !== false ? '1' : (strpos($url, 'station_2') !== false ? '2' : '3'));
        // 識別子の生成
        $referencenumber = "{$jobId}-{$jobTypeId}-{$employId}-{$businessId}-{$secFlag}-{$stationFlag}";
        // URLに 'sec_0' が含まれているかをチェック
        $company = "";
        if (strpos($url, 'sec_0') !== false) {
            // 'sec_0' が含まれている場合、市区町村と施設形態を結合して返す
            $company = $feedData['addr2_name'] . "の" . $feedData['business'];
        } else {
            $company = $feedData['display_company_name'] ?? '';
        }

        // description
        $linefeed = "\n";
        $description = "";

        $url = $feedData['url'] ?? ''; // URLの取得
        $displayEmployName = $feedData['employ_name'] ?? '';
        // おすすめポイントの設定
        $displayPrTitle = '';
        if (strpos($url, 'sec_0') === false) {
            $displayPrTitle = "■この求人のおすすめポイント:" . $feedData['order_pr_title'] . $linefeed;
        }
        // 募集職種の設定
        $displayJobTypeName = empty($feedData['job_type_name']) ? '' : $feedData['job_type_name'];
        // 給与の設定
        $displaySalary = empty($feedData['salary']) ? '' : $feedData['salary'];
        // 施設形態の設定
        $displayFacility = empty($facility) ? '' : $facility;
        // 仕事内容の設定
        $tmp = $feedData['detail'] ?? '';
        $displayDetail = empty($tmp) ? 'お問合せ下さい' : $tmp;

        // メッセージの組み立て
        $description = "【求人情報】" . $linefeed .
            "■施設名：" . $company . $linefeed .
            $displayPrTitle .
            "■雇用形態：" . $displayEmployName . $linefeed .
            "■募集職種：" . $displayJobTypeName . $linefeed .
            "■給与：" . $displaySalary . $linefeed .
            "■施設形態：" . $feedData['business'] . $linefeed .
            "■仕事内容：" . $displayDetail . $linefeed . $linefeed .
            "【掲載元情報】" . $linefeed .
            "ウィルワンエージェントは、治療家（柔道整復師、鍼灸師、あん摩マッサージ指圧師）専用の転職支援サービスです。" . $linefeed .
            "お問い合わせ後、治療家専門の転職支援のプロフェッショナルが完全無料でサポートさせて頂きます。" . $linefeed .
            "掲載の求人以外にもご希望をお伺いした上で非公開求人のご紹介が可能です。" . $linefeed .
            "またお問い合わせ頂くと、ここでは記載できない情報（職場の雰囲気や残業の実態など）のお伝えも可能です。" . $linefeed .
            "応募にはなりませんので、是非お気軽にお問い合わせください。" . $linefeed;


        // 各フィールドの追加
        $stanbyAddData = [
            'contractperiod' => '求人票に記載が無い場合、内定時までに開示します',
            'insurance'     => '求人票に記載が無い場合、内定時までに開示します',
            'benefits'      => '求人票に記載が無い場合、内定時までに開示します',
            'preventsmoke' => '求人票に記載が無い場合、内定時までに開示します',
            'agency' => '株式会社エス・エム・エス',
        ];
        $imageUrl = '';

        if ($feedData['job_type_name']) {
            if (strpos($feedData['job_type_name'], '柔道') !== false) {
                $imageUrl = 'https://www.jinzaibank.com/woa/images/feed/1.jpg';
            } elseif (strpos($feedData['job_type_name'], '鍼灸') !== false) {
                $imageUrl = 'https://www.jinzaibank.com/woa/images/feed/2.jpg';
            } elseif (strpos($feedData['job_type_name'], '指圧') !== false) {
                $imageUrl = 'https://www.jinzaibank.com/woa/images/feed/3.jpg';
            }
        }

        $content = "\n" . "<job>";
        $content .= "\n" . "<title><![CDATA[{$title}]]></title>";
        $content .= "\n" . "<referencenumber><![CDATA[{$referencenumber}]]></referencenumber>";
        $content .= "\n" . "<url><![CDATA[{$feedData['url']}]]></url>";
        $content .= "\n" . "<company><![CDATA[{$company}]]></company>";
        $content .= "\n" . "<postalcode><![CDATA[{$feedData['display_postcode']}]]></postalcode>";
        $content .= "\n" . "<country><![CDATA[日本]]></country>";
        $content .= "\n" . "<state><![CDATA[{$feedData['addr1_name']}]]></state>";
        $content .= "\n" . "<city><![CDATA[{$feedData['addr2_name']}]]></city>";
        $content .= "\n" . "<station><![CDATA[{$feedData['station']}]]></station>";
        $content .= "\n" . "<worklocation><![CDATA[{$feedData['addr']}]]></worklocation>";
        $content .= "\n" . "<description><![CDATA[{$description}]]></description>";
        $content .= "\n" . "<salary><![CDATA[{$feedData['salary']}]]></salary>";
        $content .= "\n" . "<contractperiod><![CDATA[{$stanbyAddData['contractperiod']}]]></contractperiod>";
        $content .= "\n" . "<timeshift><![CDATA[{$feedData['worktime']}]]></timeshift>";
        $content .= "\n" . "<holiday><![CDATA[{$feedData['dayoff']}]]></holiday>";
        $content .= "\n" . "<education><![CDATA[高卒以上]]></education>";
        $content .= "\n" . "<jobtype><![CDATA[{$feedData['employ_name']}]]></jobtype>";
        $content .= "\n" . "<businesscontents><![CDATA[{$feedData['business']}]]></businesscontents>";
        $content .= "\n" . "<experience><![CDATA[{$feedData['job_type_name']}]]></experience>";
        $content .= "\n" . "<category><![CDATA[{$displayFacility}]]></category>";
        $content .= "\n" . "<insurance><![CDATA[{$stanbyAddData['insurance']}]]></insurance>";
        $content .= "\n" . "<benefits><![CDATA[{$stanbyAddData['benefits']}]]></benefits>";
        $content .= "\n" . "<preventsmoke><![CDATA[{$stanbyAddData['preventsmoke']}]]></preventsmoke>";
        $content .= "\n" . "<agency><![CDATA[{$stanbyAddData['agency']}]]></agency>";
        $content .= "\n" . "<applyurl><![CDATA[{$feedData['apply_url']}]]></applyurl>";
        $content .= "\n" . "<imageUrls><![CDATA[{$imageUrl}]]></imageUrls>";

        $content .= "\n" . "</job>";

        return $content;
    }
}
