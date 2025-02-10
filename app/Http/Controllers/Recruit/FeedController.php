<?php
namespace App\Http\Controllers\Recruit;

use App\Http\Controllers\Controller;
use App\Managers\FeedLpManager;
use App\Managers\SelectBoxManager;
use App\Managers\UtilManager;
use App\Managers\WoaOpportunityManager;
use Illuminate\Http\Request;
use Validator;

class FeedController extends Controller
{
    private $view_data = [];

    // 入力フォームValidate設定
    public $rules = [
        'id'        => 'required|Integer',
        'job_type'  => 'Integer',
        'employ_id' => 'Integer',
        'sec'       => 'Integer',
        'action'    => 'alpha_dash',
    ];

    // 資格（学生）のIDリスト ※DBから like検索して取得しても良いかも
    private $studentList;
    private $device;

    // 最寄駅（番号）
    private $station;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // デバイスとLPの整合性判断（PC⇔SPリダイレクト）
        $utilMgr = new UtilManager;
        $this->device = $utilMgr->getDevice();
    }

    /**
     * 駅名指定
     * @access public
     * @param Request $request リクエストパラメータ
     * @param string $feed 媒体名
     * @param string $id ジョブID
     * @param string $jobType 職種ID
     * @param string $employId 雇用形態ID
     * @param string $station 最寄駅の表示指定番号
     * @param string $sec 公開・非公開フラグ
     * @return Viewデータ
     */
    public function station(Request $request, $feed, $id, $jobType = null, $employId = null, $station, $sec = null)
    {
        $this->station = $station;
        // 入力フォームValidate設定に追加
        $this->rules['station'] = "Integer|between:0,3";

        return $this->index($request, $feed, $id, $jobType, $employId, $sec);
    }

    /**
     * アクセスした際のURLに応じて遷移URLを変更する
     * 変更する内容は、job_type / employ_id / secのクエリ状態で異なる
     * ・クエリが全てある場合はパス形式
     * ・クエリが一つでも欠けている場合はクエリ形式
     *
     * @param Request $request リクエストパラメータ
     * @param string $feed 媒体名
     * @param int $id ジョブID
     * @return 遷移先url
     */
    public function changeTransitionUrl(Request $request, $feed, $id)
    {
        $allRequest = $request->all();
        $jobType = $allRequest['job_type'] ?? null;
        $employId = $allRequest['employ_id'] ?? null;
        $sec = $allRequest['sec'] ?? null;
        $action = $allRequest['action'] ?? null;
        $station = $allRequest['station'] ?? null;

        // 駅以外の全てのパラメータがある場合は静的ページへリダイレクト
        if ($jobType !== null && $employId !== null && $sec !== null) {
            $params = [
                'feed'     => $feed,
                'id'       => $id,
                'jobType'  => $jobType,
                'employId' => $employId,
                'sec'      => $sec,
                'action'   => $action,
            ];
            // 駅名のパラメータがある場合
            if ($station !== null) {
                $params['station'] = $station;

                return redirect()->route('FeedController.station', $params, 301);
            } else {
                return redirect()->route('FeedController.index', $params, 301);
            }
        }

        // パラメータが一つでも欠けてる場合はパラメータ形式で表示
        if ($station !== null) {
            return $this->station($request, $feed, $id, $jobType, $employId, $station, $sec);
        } else {
            return $this->index($request, $feed, $id, $jobType, $employId, $sec);
        }
    }

    /**
     * メインメソッド
     *
     * @access public
     * @param Request $request リクエストパラメータ
     * @param string $feed 媒体名
     * @param string $id ジョブID
     * @param string $jobType 職種ID
     * @param string $employId 雇用形態ID
     * @param string $sec 公開・非公開フラグ
     * @return Viewデータ
     */
    public function index(Request $request, $feed, $id, $jobType = null, $employId = null, $sec = null)
    {
        $device = config('app.device');
        $returnUrl = $this->setReturnRegistUrl($device);

        // URLパラメータを格納
        $urlPath = [
            'job_type'  => $jobType,
            'employ_id' => $employId,
            'sec'       => $sec,
        ];

        if ($this->station !== null) {
            $urlPath['station'] = $this->station;
        }

        $urlParameters = [];
        $urlParameters['job_id'] = $id;
        $urlParameters['param'] = !empty($request->all()) ? $request->all() : $urlPath;

        // バリデーションチェック
        if (!$this->checkValidationParam($urlParameters)) {
            return redirect($returnUrl);
        }

        // actionがセットされていれば引継ぎ、そうでなければfeedのactionパラメータをセット
        $action = $request->input('action') ?? 'woa_' . $feed;
        $this->view_data['action'] = $action;

        // オーダー情報の取得
        $job = $this->getOrderData($id);
        if (empty($job)) {
            // オーダー情報が見つからなければ一般の登録画面へ
            return redirect($returnUrl);
        }

        // 近隣オーダー取得
        $nearJob = $this->getNearOrderData($job);
        if ($nearJob) {
            $this->view_data['near_job'] = $nearJob;
        }

        // 表示する募集職種名
        $jobTypeParam = $jobType ?? $request->input('job_type');
        if (!empty($jobTypeParam)) {
            $jobTypeName = $this->selectJobTypeToDisplay($job, $jobTypeParam);
            if (!empty($jobTypeName)) {
                $job->job_type_name = $jobTypeName;
            }
        }
        $employView = '';
        // パラメーターに応じて雇用形態を設定
        if ($urlPath['employ_id'] == 1 && preg_match('/1/', $job->employ)) {
            $employView = '常勤';
        } elseif ($urlPath['employ_id'] == 2 && preg_match('/2/', $job->employ)) {
            $employView = '非常勤';
        }
        $this->view_data['employ_view'] = $employView;

        // 公開/非公開設定
        $secParam = $sec ?? $request->input('sec');
        $isPubliclyFlag = $this->isPublicly($job, $secParam);
        $this->view_data['isPubliclyFlag'] = $isPubliclyFlag;

        // jobデータをViewに渡す
        $this->view_data['job'] = $job;
        // 応募先URLの設定
        $this->setEntryPath($feed, $id, $action, $secParam);

        // ABパターンを取得
        $abPattern = (new FeedLpManager())->getAbTestPattern($feed);
        if (!$abPattern) {
            // 取得できなかったら404
            abort(404);
        }

        $this->view_data["ab_pattern"] = $abPattern;
        $viewPath = "{$device}.recruit.{$feed}_{$abPattern}";

        return view($viewPath, $this->view_data);
    }

    /**
     * IDとリクエストパラメータのバリデーションチェック
     *
     * バリデーションチェックを行うメソッド。
     * エラーがあればfalseを返却し、
     * エラーが無ければパラメータをViewにセットし、trueを返却する。
     *
     * @access private
     * @param array $urlParameters urlのパラメータ一覧
     * @return boolean
     */
    private function checkValidationParam($urlParameters)
    {
        $checkResult = true;
        $validArray = [
            'id' => $urlParameters['job_id'],
        ];

        // urlパラメータのバリデーション設定
        $validArray = $this->setValidationValue('job_type', $urlParameters, $validArray);
        $validArray = $this->setValidationValue('employ_id', $urlParameters, $validArray);
        $validArray = $this->setValidationValue('sec', $urlParameters, $validArray);
        $validArray = $this->setValidationValue('action', $urlParameters, $validArray);
        $validArray = $this->setValidationValue('station', $urlParameters, $validArray);

        // 基本Validate
        $validator = Validator::make($validArray, $this->rules);

        // エラー時リダイレクト
        if (!empty($validator->errors()->all())) {
            $checkResult = false;
        }

        return $checkResult;
    }

    /**
     * 登録画面のURLを返却
     *
     * エラー時に遷移させる登録画面のURLを返却。
     *
     * @access private
     * @param String $device デバイス名（pc/sp/mb）
     * @return String 登録画面のURL
     */
    private function setReturnRegistUrl($device)
    {
        return '/entry/' . strtoupper($device) . '_1?action=' . $device . 'or-top_btn';
    }

    /**
     * オーダー情報取得
     *
     * ジョブIDをキーに、Managerクラスからオーダー情報を取得する。
     *
     * @access private
     * @param String $id ジョブID
     * @return Object $job 取得したオーダー情報
     */
    private function getOrderData($id)
    {
        $woaOpportunityMgr = new WoaOpportunityManager;

        // job_idをキーに、オーダー情報取得
        $job = $woaOpportunityMgr->getWoaOpportunityByJobId($id);

        if (!$job) {
            return null;
        }

        // 駅別ページの処理：JINZAI-7656 【WOA】フィードLPを駅名で分割
        if (!empty($this->station)) {
            $column = "station{$this->station}";
            // 対象となるカラムにデータがなければこのページは存在しない
            if (empty($job->{$column})) {
                return null;
            }
            // 対象となるカラム以外の最寄駅データを空にする（station1 ～ station3）
            for ($i = 1; $i <= 3; $i++) {
                $column = "station{$i}";
                if ($i != $this->station) {
                    $job->{$column} = '';
                }
            }
        }

        // 最寄駅
        $stations = $woaOpportunityMgr->setStations($job);
        if (!empty($stations)) {
            $job->station = $stations;
        }

        return $job;
    }

    /**
     * メインオーダーの近隣オーダーを取得する
     * @param  [type] $mainJob [description]
     * @return [type]          [description]
     */
    private function getNearOrderData($mainJob)
    {
        $woaOpportunityMgr = new WoaOpportunityManager;

        // job_idをキーに、オーダー情報取得
        $nearJobList = $woaOpportunityMgr->getNearOffice($mainJob, 10);
        foreach ($nearJobList as $job) {
            // 最寄駅
            $stations = $woaOpportunityMgr->setStations($job);
            if (!empty($stations)) {
                $job->station = $stations;
            }
        }

        return $nearJobList;
    }

    /**
     * バリデーションチェック対象カラムの設定
     *
     * Getパラメータで取得した値に対するバリデーションの対象項目を設定する。
     * なお、パラメータが存在しないパターンもあるため、
     * その場合はバリデーション対象項目に加えない。
     *
     * @access private
     * @param string $valueName パラメータ名
     * @param array $urlParameters urlのパラメータ一覧
     * @return string $value パラメータ値
     */
    private function setValidationValue($valueName, $urlParameters, $validArray)
    {
        $targetKey = 'param';
        // バリデーション対象項目を加える
        if (isset($urlParameters[$targetKey][$valueName]) && !empty($urlParameters[$targetKey][$valueName])) {
            $validArray[$valueName] = $urlParameters[$targetKey][$valueName];
        }

        return $validArray;
    }

    /**
     * 募集職種の変換
     *
     * パラメータとして受け取ったjob_typeを画面表示用に文字列に置き換える。
     * 受け取ったjob_typeが、該当オーダー情報の職種としてあれば
     * 変換してその値をセットし、無ければ値をセットしない（項目を非表示とする）
     *
     * @access private
     * @param Object $job オーダー情報
     * @return void
     */
    private function selectJobTypeToDisplay($job, $jobTypeId)
    {
        $selectBoxManager = new SelectBoxManager;
        $jobTypeList = explode(',', $job->job_type);
        $jobTypeName = '';

        if (in_array($jobTypeId, $jobTypeList)) {
            // job_typeに紐づく文字列はmstテーブルから取得する
            $getJobName = $selectBoxManager->getJobNameById($jobTypeId);
            $jobTypeName = $getJobName->job_type;
        }
        return $jobTypeName;
    }

    /**
     * 公開/非公開設定
     * @access private
     * @param Object $job オーダー情報
     * @param String $secParam secパラメータ
     * @return boolean
     */
    private function isPublicly($job, $secParam)
    {
        $result = false;

        if ($job->publicly_flag === 1 && $secParam === '1') {
            $result = true;
        }

        return $result;
    }

    /**
     * 応募先URLの設定
     *
     * 募集詳細画面上に表示される、応募ボタンの遷移先を制御する
     *
     * @access private
     * @param String $feed 媒体名（現状indeedのみ）
     * @param String $job_id ジョブID
     * @param String $action アクションパラメータ
     * @param String $secParam secパラメータ
     * @return void
     */
    private function setEntryPath($feed, $job_id, $action, $secParam)
    {
        $entryPath = "/glp/{$feed}_01/";
        $parameter = "?job_id={$job_id}&action={$action}&utm_source=feed&utm_medium=cpc&utm_campaign={$feed}&sec={$secParam}";
        $this->view_data['entry_path'] = $entryPath . $parameter;
        // ブラウザバックボタン押下時に表示するポップアップ画面用URL
        $parameterPopup = "?action=woa_{$feed}_popup_{$job_id}";
        $this->view_data['entry_path_popup'] = $entryPath . $parameterPopup;
        // フィードID、ポップアップ（モーダル）用パスを設定
        $this->view_data["feed_id"] = $feed;
    }
}
