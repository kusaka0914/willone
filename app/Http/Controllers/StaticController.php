<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Managers\UtilManager;
use App\Model\Inquiry;
use App\Model\ParameterMaster;
use App\UseCases\Entry\IntroduceUseCase;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Mail;
use Validator;

class StaticController extends Controller
{
    private $inquiry;
    private $parameter;
    private $utilManager;

    // 入力フォームValidate設定
    private $recruit_rules = [
        'inc_name'        => 'required|ctrl_emoji_char|max:64', // 会社名
        'staff_name'      => 'required|ctrl_emoji_char|max:64', // 担当者名
        'staff_name_kana' => 'required|hiraganakatakana|max:64', // 担当者名フリガナ
        'addr'            => 'required|ctrl_emoji_char|max:64', // 住所
        'mail'            => 'required|custom_email|max:80', // メールアドレス
        'tel'             => 'required|custom_tel|custom_tel_format|custom_tel_length|custom_tel_exist', // 電話番号
        'req_work_type'   => 'required', // 希望職種
        'inquiry'         => 'ctrl_emoji_char|max:1000', // 問い合わせ内容
    ];

    private $contact_rules = [
        'type'            => 'required', // ご用件
        'name'            => 'required|ctrl_emoji_char|max:64', //  お名前
        'kana_name'       => 'required|hiraganakatakana|max:64', // ふりがな
        'email'           => 'required|custom_email|max:80', // メールアドレス
        'postcode'        => 'required|regex:/^[0-9]{7,7}$/', // 郵便番号
        'address'         => 'required|ctrl_emoji_char|max:64', // 住所
        'tel'             => 'required|custom_tel|custom_tel_format|custom_tel_length|custom_tel_exist', // 電話番号
        'seibetsu'        => 'required', //性別
        'toiawase'        => 'ctrl_emoji_char|max:1000', // 問い合わせ内容
    ];

    // 年齢のValidate設定
    private $rulesAge = [
        'age'             => 'regex:/^[0-9]+$/|age_area', // 年齢
    ];

    public function __construct(Inquiry $inquiry, ParameterMaster $parameter, UtilManager $utilManager)
    {
        $this->inquiry = $inquiry;
        $this->parameter = $parameter;
        $this->utilManager = $utilManager;
    }

    public function guideindex()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "就業中(既卒)の方へ";

        $data['headtitle'] = "就業中(既卒)の方へ｜柔道整復師・鍼灸師・あん摩マッサージ指圧師の求人【ウィルワン】";

        $data['headdescription'] = "就業中(既卒)の方へ｜柔道整復師・柔整師の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.guideindex', $data);
        } else {
            return view('pc.static.guideindex', $data);
        }
    }

    public function rule()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "利用規約";

        $data['headtitle'] = "利用規約｜柔道整復師の求人【ウィルワン】";

        $data['headdescription'] = "利用規約｜柔道整復師・柔整師の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.rule', $data);
        } else {
            return view('pc.static.rule', $data);
        }
    }

    public function privacy()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "個人情報保護方針";

        $data['headtitle'] = "利用規約｜柔道整復師の求人【ウィルワン】";

        $data['headdescription'] = "利用規約｜柔道整復師・柔整師の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.privacy', $data);
        } else {
            return view('pc.static.privacy', $data);
        }
    }

    public function recruit()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "治療院の院長・経営者さまへ";

        $data['headtitle'] = "治療院の院長・経営者様へ｜柔道整復師の求人【ウィルワン】";

        $data['headdescription'] = "治療院の院長・経営者さまへ｜柔道整復師・柔整師の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $data['req_work_type_list'] = config('ini.RECRUIT_REQ_WORK_TYPE');

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.recruit', $data);
        } else {
            return view('pc.static.recruit', $data);
        }
    }

    public function recruitcomp(Request $request)
    {
        // 全リクエスト値取得
        $allRequest = $request->all();
        // 入力置換
        $allRequest = $this->utilManager->inputReplacement($allRequest);

        // 基本Validate
        $validator = Validator::make($allRequest, $this->recruit_rules);

        // validateエラーがある場合の処理（フォーム画面に戻る）
        if (!empty($validator->errors()->all())) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $text = "";
        $data = $allRequest['req_work_type'];
        foreach ($data as $value) {
            $syokushu_data = $this->parameter->where('del_flg', 0)->where('genre_id', config('const.genre_syokusyu'))->where('key_value', $value)->first();
            $text = $text . $syokushu_data->value1 . ",";
        }

        $this->inquiry->company_name = $allRequest['inc_name'];
        $this->inquiry->type = $allRequest['type'];
        $this->inquiry->name = $allRequest['staff_name'];
        $this->inquiry->kana_name = $allRequest['staff_name_kana'];
        $this->inquiry->email = $allRequest['mail'];
        $this->inquiry->address = $allRequest['addr'];
        $this->inquiry->tel = $allRequest['tel'];
        $this->inquiry->toiawase = $allRequest['inquiry'];
        $this->inquiry->syokushu = $text;

        $this->inquiry->save();

        $maildata = $this->parameter->where('genre_id', config('const.genre_seminar_mail_address'))->where('key_value', 1)->first();
        $mail_to = $maildata->value1;

        $options = [
            'from'     => 'info@willone.jp',
            'from_jp'  => 'willone',
            'to'       => $mail_to,
            'subject'  => '人材紹介の申込がありました',
            'template' => 'mails.recruit',
        ];

        $emaildata = [
            'mail_to'      => $mail_to,
            'company_name' => $this->inquiry->company_name,
            'name'         => $this->inquiry->name,
            'kana'         => $this->inquiry->kana_name,
            'email'        => $this->inquiry->email,
            'address'      => $this->inquiry->address,
            'tel'          => $this->inquiry->tel,
            'syokushu'     => $this->inquiry->syokushu,
            'toiawase'     => $this->inquiry->toiawase,

        ];

        Mail::to($mail_to)->send(new SendMail($emaildata, $options));

        $data['breadcrump_num'] = 2;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrumpurl'][] = "/";
        $data['breadcrump'][] = "治療院の院長・経営者さまへ";
        $data['breadcrumpurl'][] = route('Recruit');
        $data['breadcrump'][] = "人材紹介申込完了";
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.recruitcomp', $data);
        } else {
            return view('pc.static.recruitcomp', $data);
        }
    }

    public function recommended()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "新卒の方にもオススメです";

        $data['headtitle'] = "新卒の方へ｜柔道整復師の求人【ウィルワン】";

        $data['headdescription'] = "新卒の方へ｜柔道整復師・柔整師の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.recommended', $data);
        } else {
            return view('pc.static.recommended', $data);
        }
    }

    public function contact()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "お問い合わせ";
        $data['noindex'] = 1;
        $data['type_list'] = config('ini.CONTACT_INQUIRY');
        $data['seibetsu_list'] = config('ini.GENDER');

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.contact', $data);
        } else {
            return view('pc.static.contact', $data);
        }
    }

    public function contactcomp(Request $request)
    {
        // 全リクエスト値取得
        $allRequest = $request->all();
        // 入力置換
        $allRequest = $this->utilManager->inputReplacement($allRequest);

        // 年齢Validate
        if (isset($allRequest['age'])) {
            $this->contact_rules = array_merge($this->contact_rules, $this->rulesAge);
        }

        // 基本Validate
        $validator = Validator::make($allRequest, $this->contact_rules);

        // validateエラーがある場合の処理（フォーム画面に戻る）
        if (!empty($validator->errors()->all())) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $errors = 0;

        // 個別Validate
        // ご用件の値が存在するのかのチェック
        $type = $allRequest['type'] ?? '';
        if (!empty($type) && !array_key_exists($type, config('ini.CONTACT_INQUIRY'))) {
            $validator->errors()->add('type', '正しいご用件を選択してください');
            $errors++;
        }

        // 性別の値が存在するかのチェック
        $seibetsu = $allRequest['seibetsu'] ?? '';
        if (!empty($seibetsu) && !array_key_exists($seibetsu, config('ini.GENDER'))) {
            $validator->errors()->add('seibetsu', '性別を選択してください');
            $errors++;
        }

        if ($errors > 0) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $inquiry_table = new Inquiry;

        $inquiry_table->type = 1;
        $inquiry_table->contact_syubetsu = $allRequest['type'];
        $inquiry_table->name = $allRequest['name'];
        $inquiry_table->kana_name = $allRequest['kana_name'];
        $inquiry_table->email = $allRequest['email'];
        $inquiry_table->address = $allRequest['postcode'] . " " . $allRequest['address'];
        $inquiry_table->tel = $allRequest['tel'];
        $inquiry_table->age = $allRequest['age'];
        $inquiry_table->seibetsu = $allRequest['seibetsu'];
        $inquiry_table->toiawase = $allRequest['toiawase'];

        $inquiry_table->save();

        $maildata = $this->parameter->where('genre_id', config('const.genre_seminar_mail_address'))->where('key_value', 1)->first();
        $mail_to = $maildata->value1;

        $options = [
            'from'     => 'info@willone.jp',
            'from_jp'  => 'willone',
            'to'       => $mail_to,
            'subject'  => '問合せがありました',
            'template' => 'mails.contact',
        ];

        if ($inquiry_table->seibetsu == 1) {
            $seibetsu_name = "男性";
        } else {
            $seibetsu_name = "女性";
        }

        switch ($inquiry_table->contact_syubetsu) {
            case 1:
                $syubetsu_name = "就職転職の相談";
                break;
            case 2:
                $syubetsu_name = "ご登録について";
                break;
            case 3:
                $syubetsu_name = "システムについて";
                break;
            case 4:
                $syubetsu_name = "その他のお問い合わせ";
                break;
            default:
                $syubetsu_name = "その他のお問い合わせ";
        }

        $emaildata = [
            'mail_to'          => $mail_to,
            'contact_syubetsu' => $syubetsu_name,
            'name'             => $inquiry_table->name,
            'kana'             => $inquiry_table->kana_name,
            'email'            => $inquiry_table->email,
            'address'          => $inquiry_table->address,
            'tel'              => $inquiry_table->tel,
            'age'              => $inquiry_table->age,
            'seibetsu'         => $seibetsu_name,
            'toiawase'         => $inquiry_table->toiawase,

        ];

        Mail::to($mail_to)->send(new SendMail($emaildata, $options));

        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "お問い合わせ完了";
        $data['noindex'] = 1;
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.contactcomp', $data);
        } else {
            return view('pc.static.contactcomp', $data);
        }
    }

    public function access()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "アクセス";
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.access', $data);
        } else {
            return view('pc.static.access', $data);
        }
    }

    /**
     * エラーページを表示
     * @access public
     * @return void
     */
    public function notfound()
    {
        abort(404);
    }

    /*
     *大阪府特集ページ
     */
    public function osaka2020()
    {
        $data['bonesetterJobsUrl'] = config('app.url') . '/job/judoseifukushi/osaka';
        $data['acupunctureJobsUrl'] = config('app.url') . '/job/harikyushi/osaka';
        $data['masseurJobsUrl'] = config('app.url') . '/job/ammamassajishiatsushi/osaka';

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.osaka2020', $data);
        } else {
            return view('pc.static.osaka2020', $data);
        }
    }

    /*
     *　LP宣伝ページ
     */
    public function jobchangeagent()
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.jobchangeagent');
        } else {
            return view('pc.static.jobchangeagent');
        }
    }

    /*
     *　新卒向けの求職者IDページ
     */
    public function findworkagent(Request $request)
    {
        $baseUrl = '/woa/glp/friend2/?action=';
        $data = [];
        $action = $request->input('action', 'sms_id');
        $cpId = $request->input('cp', '');
        $cp = !empty($cpId) ? "&cp={$cpId}" : '';
        $friendId = $request->input('friend', '');
        $friend = !empty($friendId) ? "&friend={$friendId}" : '';
        $data['findWorkAgentPath'] = $baseUrl . $action . $cp . $friend;

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.findworkagent', $data);
        } else {
            return view('pc.static.findworkagent', $data);
        }
    }

    /*
     *　友人紹介者向けページ
     */
    public function introduce(IntroduceUseCase $introduceUseCase)
    {
        $device = (new Agent)->isMobile() ? 'sp' : 'pc';

        $introduceData = $introduceUseCase();

        // メール紹介件名、本文を作成
        $data['mail_subject'] = $introduceData['mail_subject'];
        $data['mail_body'] = $introduceData['mail_body'];

        // LINE紹介本文を作成
        $data['line_text'] = $introduceData['line_text'];

        return view("{$device}.static.introduce", $data);
    }

    /*
     *　機能訓練指導員のインタビューページ1
     */
    public function interviewFti1()
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.interviewFti1');
        } else {
            return view('pc.static.interviewFti1');
        }
    }

    /*
     *　機能訓練指導員のインタビューページ2
     */
    public function interviewFti2()
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.static.interviewFti2');
        } else {
            return view('pc.static.interviewFti2');
        }
    }
}
