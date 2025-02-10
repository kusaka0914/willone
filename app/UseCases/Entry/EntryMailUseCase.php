<?php

namespace App\UseCases\Entry;

use App\Mail\MailSender;
use App\Mail\SendMail;
use App\Model\ParameterMaster;
use Mail;

class EntryMailUseCase
{

    final public function __invoke(array $args): void
    {

        $allRequest = $args['allRequest'];
        $kurohonLp = $args['kurohonLp'];
        $kurohonLpStudent = $args['kurohonLpStudent'];

        // アンケート回答サンクスメール送信
        if ($allRequest['mail']) {
            // メールアドレスが入力されている場合のみ、メール送信
            $this->sendEntryFinMailToCustomer($allRequest, $kurohonLp, $kurohonLpStudent);
        }

        $this->sendEntryFinMailToManagement($allRequest);

        if (strpos($allRequest['action'], 'line-chat') !== false) {
            $this->sendEntryFinMailToManagementLine($allRequest);
        }
    }

    /**
     * 登録完了時の自動返信メール
     *
     * @param array $allRequest
     * @param array $kurohonLp
     * @param array $kurohonLpStudent
     * @return void
     */
    private function sendEntryFinMailToCustomer(array $allRequest, array $kurohonLp, array $kurohonLpStudent): void
    {
        $sendData = [
            'name' => $allRequest['name_kan'],
        ];
        $options = [
            'to'        => $allRequest['mail'],
            'from'      => config('ini.FROM_MAIL'),
            'from_name' => 'willone',
            'subject'   => 'ご登録ありがとうございます 【' . config('ini.SITE_MEISYOU') . '】',
            'template'  => 'mails.entryToCustomer',
        ];
        if (in_array($allRequest['t'], $kurohonLp)) {
            $options = [
                'to'        => $allRequest['mail'],
                'from'      => 'kurohon@bm-sms.co.jp',
                'from_name' => '国試黒本',
                'subject'   => 'ご登録ありがとうございます 【国試黒本】',
                'template'  => 'mails.entryKurohonToCustomer',
            ];
        } elseif (in_array($allRequest['t'], $kurohonLpStudent)) {
            $options = [
                'to'        => $allRequest['mail'],
                'from'      => config('ini.FROM_MAIL'),
                'from_name' => 'willone',
                'subject'   => 'ご登録ありがとうございます 【ウィルワンエージェント】',
                'template'  => 'mails.entryKurohonStudentToCustomer',
            ];
        }
        Mail::to($options['to'])->send(new MailSender($options, $sendData));
    }

    /**
     * 運営側へのメール通知
     *
     * @param array $allRequest
     * @return void
     */
    private function sendEntryFinMailToManagement(array $allRequest): void
    {

        // 運営側へのメール通知 (既存のApplyController.phpより)
        $maildata = ParameterMaster::where('genre_id', config('const.genre_seminar_mail_address'))->where('key_value', 1)->first();

        $options = [
            'from'     => config('ini.FROM_MAIL'),
            'from_jp'  => 'willone',
            'to'       => $maildata->value1,
            'subject'  => '求人検索の登録がありました',
            'template' => 'mails.entry',
        ];
        $sendData = [
            'mail_to'               => $options['to'],
            'name_kan'              => $allRequest['name_kan'],
            'name_cana'             => $allRequest['name_cana'],
            'birth'                 => $allRequest['birth'],
            'zip'                   => $allRequest['zip'],
            'addr1'                 => $allRequest['addr1_text'],
            'addr2'                 => $allRequest['addr2_text'],
            'addr3'                 => $allRequest['addr3'],
            'tel'                   => $allRequest['mob_phone'],
            'mail'                  => $allRequest['mail'],
            'license'               => $allRequest['license_text'],
            'req_emp_type'          => $allRequest['req_emp_type_text'],
            'req_date'              => $allRequest['req_date_text'],
            'birth_year'            => $allRequest['birth_year'],
            'retirement_intention'  => $allRequest['retirement_intention_text'],
            'graduation_year'       => $allRequest['graduation_year'],
            'entry_order'           => $allRequest['entry_order'],
            'action'                => $allRequest['action'],
            'action_first'          => $allRequest['action_first'],
            'entry_category_manual' => $allRequest['entry_category_manual'],
            'template_id'           => $allRequest['t'],
            'ip'                    => $allRequest['ip'],
            'ua'                    => $allRequest['ua'],
            'entry_memo'            => $allRequest['entry_memo'],
            'agreement_flag'        => $allRequest['agreement_flag'],
        ];

        Mail::to($options['to'])->send(new SendMail($sendData, $options));
    }

    /**
     * 黒本公式LINE→WOA登録→登録を知らせるメールを送信
     *
     * @param array $allRequest
     * @return void
     */
    private function sendEntryFinMailToManagementLine(array $allRequest): void
    {

        $inputNameKan = $allRequest['name_kan'];
        $options = [
            'from'     => config('ini.FROM_MAIL'),
            'from_jp'  => 'willone',
            'to'       => config('ini.WOA_TELEMA'),
            'subject'  => "【LINE送客】{$inputNameKan}さんの登録が完了しました",
            'template' => 'mails.registFromLine',
        ];
        $sendData = [
            'name_kan'        => $inputNameKan,
            'graduation_year' => $allRequest['graduation_year'],
            'license_text'    => $allRequest['license_text'],
        ];

        Mail::to($options['to'])->send(new SendMail($sendData, $options));
    }
}
