<?php

namespace App\UseCases\Reentry;

use App\Mail\SendMail;
use App\Managers\SlackServiceManager;
use App\Model\ParameterMaster;
use App\Model\RegistrationInquiryDetail;
use Carbon\Carbon;
use Mail;

class InquiryDataUseCase
{
    const DAY_TIME = 1; // 日付時間(カレンダー)
    const TIME = 0; // 時間(時間選択)

    public function __construct(object $sfCustomerInfo, array $allRequest)
    {
        $this->parameter = new ParameterMaster;
        $this->slackServiceMgr = new SlackServiceManager();
        $tmpNo = $allRequest['t'];

        // $tmpNo に応じて適切なメソッドを呼び出す
        if ($tmpNo === '2') {
            $this->sendContactMailT2($sfCustomerInfo, $allRequest);
        } elseif ($tmpNo === '3') {
            $this->sendContactMailAndRegister($sfCustomerInfo, $allRequest, 'mails.contact_inquiry_t' . $tmpNo, config('ini.KISOTSU_TO_MAIL'), self::DAY_TIME);
        } elseif ($tmpNo === '4') {
            $this->sendContactMailAndRegister($sfCustomerInfo, $allRequest, 'mails.contact_inquiry_t' . $tmpNo, config('ini.KISOTSU_TO_MAIL'), self::TIME);
        } elseif ($tmpNo === '5') {
            $this->sendContactMailAndRegister($sfCustomerInfo, $allRequest, 'mails.contact_inquiry_t' . $tmpNo, config('ini.SHINSOTU_TO_MAIL'), self::DAY_TIME);
        } elseif ($tmpNo === '6') {
            $this->sendContactMailAndRegisterT6($sfCustomerInfo, $allRequest);
        }
    }

    /**
     * 問い合わせ内容メール送信(t=2)
     * @param object $sfCustomerInfo SFの求職者情報
     * @param array $allRequest POSTされたデータ
     * @return void
     */
    private function sendContactMailT2(object $sfCustomerInfo, array $allRequest): void
    {
        $mailTo = $this->getMailTo(config('const.genre_seminar_mail_address'));

        $options = $this->getMailOptions('mails.contact_inquiry_t2', $mailTo);
        $reentryContactTime = $this->getReentryContactTime($allRequest);

        $emaildata = $this->getEmailData($sfCustomerInfo, $allRequest, $reentryContactTime);

        Mail::to($mailTo)->send(new SendMail($emaildata, $options));
    }

    /**
     * 問い合わせ内容メール送信と登録
     * @param object $sfCustomerInfo SFの求職者情報
     * @param array $allRequest POSTされたデータ
     * @param string $mailTemplate メールテンプレート
     * @param string $mailTo 送信先メールアドレス
     * @param int $reentryContactType テンプレートタイプ
     */
    private function sendContactMailAndRegister(object $sfCustomerInfo, array $allRequest, string $mailTemplate, string $mailTo, int $reentryContactType)
    {

        // テスト環境では「dev-woa-info@bm-sms.co.jp」送信、本番は「woa_kisotsu@bm-sms.co.jp」に送信
        if (!app()->isProduction()) {
            $mailTo = $this->getMailTo(config('const.genre_seminar_mail_address'));
        }

        $options = $this->getMailOptions($mailTemplate, $mailTo);
        $reentryContactTime = $reentryContactType ? $this->getReentryContactDayTime($allRequest) : $this->getReentryContactTime($allRequest);

        $emaildata = [
            'age'                  => $sfCustomerInfo->age ?? null,
            'reentry_contact_time' => $reentryContactTime,
            'inquiry_date'         => Carbon::now()->format('Y-m-d H:i'),
            'toiawase'             => $allRequest['toiawase'] ?? null,
        ];

        if ($allRequest['t'] === '5') {
            // hubhostデータ
            $emaildata['name'] = $sfCustomerInfo->name_kan ?? null;
            $emaildata['license'] = $sfCustomerInfo->shikaku[0] ?? null;
            $emaildata['addr1_txt'] = $sfCustomerInfo->user_addr1 ?? null;
            // HubSpot URL を生成
            $emaildata['hub_spot_url'] = $this->generateHubSpotUrl($allRequest['client_id']);
            $emaildata['contact_inquiry'] = !empty($allRequest['contact_inquiry']) ? config('ini.REENTRY_JOB_HUNTING_STATUS_LABEL')[$allRequest['contact_inquiry']] : '';
            // SFデータ
        } else {
            $emaildata['name'] = $sfCustomerInfo->name ?? null;
            $emaildata['license'] = $sfCustomerInfo->license ?? null;
            $emaildata['addr1_txt'] = $sfCustomerInfo->addr1 ?? null;
            $emaildata['url'] = $this->getSalesforceUrl($sfCustomerInfo->salesforce_id);
            $emaildata['cp_name'] = $this->getCpName($sfCustomerInfo);
        }

        $this->registrationInquiryDetailInsert($sfCustomerInfo, $allRequest, $reentryContactType);

        Mail::to($mailTo)->send(new SendMail($emaildata, $options));
    }

    /**
     * registrationInquiryDetailへのinsert
     *
     * @param object $sfCustomerInfo
     * @param array $allRequest
     * @param integer $reentryContactType
     * @return int
     */
    private function registrationInquiryDetailInsert(object $sfCustomerInfo, array $allRequest, int $reentryContactType): int
    {
        $reentryContactTime = $reentryContactType ? $this->getReentryContactDayTime($allRequest) : $this->getReentryContactTime($allRequest);

        try {
            \DB::beginTransaction();

            $now = Carbon::now()->format('Y-m-d H:i:s');
            $inquirydata = [
                'age'                  => $sfCustomerInfo->age ?? null,
                'reentry_contact_time' => $reentryContactTime,
                'inquiry_date'         => $now,
                'toiawase'             => $allRequest['toiawase'] ?? null,
                'template_id'          => $allRequest['t'] ?? null,
                'contact_inquiry'      => !empty($allRequest['contact_inquiry']) ? config('ini.REENTRY_JOB_HUNTING_STATUS_LABEL')[$allRequest['contact_inquiry']] : null,
                'graduation_year'      => $allRequest['graduation_year'] ?? null,
                'input_birth_year'     => $allRequest['input_birth_year'] ?? null,
                'mob_phone'            => $allRequest['mob_phone'] ?? null,
                'mob_mail'             => $allRequest['mob_mail'] ?? null,
                'regist_date'          => $now,
                'update_date'          => $now,
                'delete_date'          => null,
                'delete_flag'          => 0,
            ];

            if ($allRequest['t'] === '5') {
                // hubhostデータ
                $inquirydata['hub_spot_url'] = !empty($allRequest['client_id']) ? $this->generateHubSpotUrl($allRequest['client_id']) : null;
                $inquirydata['name'] = $sfCustomerInfo->name_kan ?? null;
                $inquirydata['license'] = $sfCustomerInfo->shikaku[0] ?? null;
                $inquirydata['addr1_txt'] = $sfCustomerInfo->user_addr1 ?? null;
            } else {
                // SFデータ
                $inquirydata['cp_name'] = $this->getCpName($sfCustomerInfo);
                $inquirydata['url'] = $this->getSalesforceUrl($sfCustomerInfo->salesforce_id);
                $inquirydata['name'] = $sfCustomerInfo->name ?? null;
                $inquirydata['license'] = $sfCustomerInfo->license ?? null;
                $inquirydata['addr1_txt'] = $sfCustomerInfo->addr1 ?? null;
            }

            $inquiryId = (new RegistrationInquiryDetail)->insertGetId($inquirydata);

            if (!$inquiryId) {
                \DB::rollBack();
                throw new \Exception('【問い合わせ内容登録失敗】DB登録エラー');
            }

            \DB::commit();

            return $inquiryId;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /**
     * t=6の処理
     *
     * @param object $sfCustomerInfo
     * @param array $allRequest
     * @return void
     */
    private function sendContactMailAndRegisterT6(object $sfCustomerInfo, array $allRequest): void
    {

        $this->registrationInquiryDetailInsert($sfCustomerInfo, $allRequest, self::TIME);

        $mailTo = config('ini.KISOTSU_TO_MAIL');

        // テスト環境では「dev-woa-info@bm-sms.co.jp」送信、本番は「woa_kisotsu@bm-sms.co.jp」に送信
        if (!app()->isProduction()) {
            $mailTo = $this->getMailTo(config('const.genre_seminar_mail_address'));
        }

        $options = $this->getMailOptions('mails.contact_inquiry_t6', $mailTo);
        $reentryContactTime = $this->getReentryContactTime($allRequest);

        $emaildata = $this->getEmailData($sfCustomerInfo, $allRequest, $reentryContactTime);

        Mail::to($mailTo)->send(new SendMail($emaildata, $options));
    }

    /**
     * メール送信先を取得
     * @param int $genreId ジャンルID
     * @return string
     */
    private function getMailTo(int $genreId): string
    {
        $maildata = $this->parameter->where('genre_id', $genreId)->where('key_value', 1)->first();

        return $maildata->value1;
    }

    /**
     * メールオプションを取得
     * @param string $template メールテンプレート
     * @param string $mailTo 送信先メールアドレス
     * @return array
     */
    private function getMailOptions(string $template, string $mailTo): array
    {
        return [
            'from'     => config('ini.FROM_MAIL'),
            'from_jp'  => 'willone',
            'to'       => $mailTo,
            'subject'  => 'お問い合わせがありました',
            'template' => $template,
        ];
    }

    /**
     * SalesforceのURLを取得
     * @param string|null $salesforceId SalesforceのID
     * @return string
     */
    private function getSalesforceUrl(?string $salesforceId): string
    {
        if (empty($salesforceId)) {
            return '';
        }
        $url = app()->isProduction() ? 'https://smsc001.lightning.force.com/lightning/r/kjb_customer__c/salesforce_id/view' : 'https://smsc001--test.sandbox.lightning.force.com/lightning/r/kjb_customer__c/salesforce_id/view';

        return str_replace('salesforce_id', $salesforceId, $url);
    }

    /**
     * 連絡希望時間帯を取得
     * @param array $allRequest POSTされたデータ
     * @return string
     */
    private function getReentryContactTime(array $allRequest): string
    {
        $reentryContactTime = '';
        if (!empty($allRequest['reentry_contact_time'])) {
            foreach ($allRequest['reentry_contact_time'] as $one) {
                if (isset(config('ini.REENTRY_CONTACT_TIME')[$one])) {
                    $reentryContactTime .= config('ini.REENTRY_CONTACT_TIME')[$one] . ',';
                }
            }
            $reentryContactTime = rtrim($reentryContactTime, ',');
        }

        return $reentryContactTime;
    }

    /**
     * 連絡希望時間帯カレンダーを取得
     * @param array $allRequest POSTされたデータ
     * @return string
     */
    private function getReentryContactDayTime(array $allRequest): string
    {
        $reentryContactTime = implode(',', array_filter($allRequest, function ($key) use ($allRequest) {
            return strpos($key, 'reentry_contact_time_') === 0 && !is_null($allRequest[$key]);
        }, ARRAY_FILTER_USE_KEY)) ?: '';

        return $reentryContactTime;
    }

    /**
     * メールデータを取得
     * @param object $sfCustomerInfo SFの求職者情報
     * @param array $allRequest POSTされたデータ
     * @param string $reentryContactTime 再エントリー連絡時間
     * @return array
     */
    private function getEmailData(object $sfCustomerInfo, array $allRequest, string $reentryContactTime): array
    {
        return [
            'mail'                 => $sfCustomerInfo->mail,
            'name'                 => $sfCustomerInfo->name,
            'license'              => $sfCustomerInfo->license,
            'tel'                  => $sfCustomerInfo->tel,
            'addr'                 => $sfCustomerInfo->addr1 . $sfCustomerInfo->addr2,
            'url'                  => $this->getSalesforceUrl($sfCustomerInfo->salesforce_id),
            'toiawase'             => $allRequest['toiawase'] ?? null,
            'contact_inquiry'      => config('ini.REENTRY_CONTACT_INQUIRY')[($allRequest['contact_inquiry'] ?? null)] ?? null,
            'reentry_contact_time' => $reentryContactTime,
        ];
    }

    /**
     * CP名を取得
     * @param object $sfCustomerInfo SFの求職者情報
     * @return string
     */
    private function getCpName(object $sfCustomerInfo): string
    {
        return !empty($sfCustomerInfo->cp_name) ? $sfCustomerInfo->cp_name : (!empty($sfCustomerInfo->cp_correction) ? $sfCustomerInfo->cp_correction : ($sfCustomerInfo->status === "掘起し対象" ? "掘起し対象" : ''));
    }

    /**
     * HubSpotURLを生成するメソッド
     *
     * @param string|null $clientId
     * @return string
     */
    private function generateHubSpotUrl(?string $clientId): string
    {
        $hubSpotUrl = '';

        if (!empty($clientId)) {
            // HubSpotURLの生成ロジックをここに記述
            $hubSpotBaseUrl = 'https://app.hubspot.com/contacts/5662439/record/0-1';
            $hubSpotUrl = "{$hubSpotBaseUrl}/{$clientId}";
        }

        return $hubSpotUrl;
    }
}
