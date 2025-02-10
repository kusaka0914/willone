<?php

namespace App\UseCases\Reentry;

use App\Mail\MailSender;
use App\Mail\SendMail;
use App\Managers\WoaCustomerDigsManager;
use App\Model\ParameterMaster;
use Illuminate\Http\Request;
use Mail;

class ModalDigsRegistUseCase
{

    final public function __invoke(string $type, Request $request, object $digsSfCUstomer): array
    {
        $result = [];
        switch ($type) {
            case 'regist':
                $id = $this->regist($request, $digsSfCUstomer);
                $result['id'] = $id;
                break;
            case "mail":
                $this->sendMail($request, $digsSfCUstomer);
                break;
        }

        return $result;
    }

    /**
     * DB登録
     *
     * @param Request $request
     * @param object $digsSfCUstomer
     * @return integer
     */
    private function regist(Request $request, object $digsSfCUstomer): int
    {

        $actionFirst = $action = $request->input('action');
        if ($request->session()->has('action')) {
            $tmp = explode(',', $request->session()->get('action'));
            $actionFirst = current($tmp);
            $action = end($tmp);
        }
        $data = [
            'salesforce_id'             => $digsSfCUstomer->salesforce_id,
            'req_emp_type_text'         => $request->input('req_emp_type_recent'),
            'req_date_text'             => $request->input('req_date_recent'),
            'retirement_intention_text' => $request->input('retirement_intention'),
            'entry_route'               => config('ini.ETNRY_ROUTE_REENTRY'),
            'mail'                      => $digsSfCUstomer->mail,
            'action_first'              => $actionFirst,
            'action'                    => $action,
            't'                         => $request->input('t'),
            'ip'                        => $request->ip(),
            'ua'                        => $request->header('User-Agent'),
            'web_customer_id'           => $digsSfCUstomer->web_customer_id,
        ];

        // 求職者掘起し登録

        return (new WoaCustomerDigsManager)->registWoaCustomerDigs($data);
    }

    /**
     * メール送信
     *  求職者と運営
     *
     * @param Request $request
     * @param object $digsSfCUstomer
     * @return void
     */
    private function sendMail(Request $request, object $digsSfCUstomer): void
    {

        if (!empty($digsSfCUstomer->mail)) {
            // 掘起し登録した求職者へメール送信
            Mail::to($digsSfCUstomer->mail)->send(new MailSender(
                [
                    'from'      => config('ini.FROM_MAIL'),
                    'from_name' => 'willone',
                    'subject'   => 'ご回答ありがとうございます 【' . config('ini.SITE_MEISYOU') . '】',
                    'template'  => 'mails.reentry_fin_mail',
                ],
                ['name' => $digsSfCUstomer->name]
            ));
        }

        // 運営側へのメール送信
        $maildata = ParameterMaster::where('genre_id', config('const.genre_seminar_mail_address'))->where('key_value', 1)->first();
        Mail::to($maildata->value1)->send(new SendMail(
            [
                'salesforceId'        => $digsSfCUstomer->salesforce_id,
                'webCustomerId'       => $digsSfCUstomer->web_customer_id,
                'name'                => $digsSfCUstomer->name,
                'mail'                => $digsSfCUstomer->mail,
                'reqEmpType'          => $request->input('req_emp_type_recent'),
                'reqDate'             => $request->input('req_date_recent'),
                'retirementIntention' => $request->input('retirement_intention'),
                'ip'                  => $request->ip(),
                'ua'                  => $request->header('User-Agent'),
            ],
            [
                'from'     => config('ini.FROM_MAIL'),
                'from_jp'  => 'willone',
                'subject'  => '掘り起こしの登録がありました',
                'template' => 'mails.reEntryToManagement',
            ]
        ));
    }
}
