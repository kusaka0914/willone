<div class="p-staff-head">
    <div class="p-staff-head-detail">
        <p class="p-staff-head-job">エージェント</p>
        <p class="p-staff-head-comment">{{ $detail->catchcopy }}</p>
        <p class="p-staff-head-name">{{ $detail->name }}</p>
        <table class="p-staff-head-info">
            <tr>
                <th>出身地</th>
                <td>{{ $detail->from_place }}</td>
            </tr>
            <tr>
                <th>座右の銘</th>
                <td>{{ $detail->zayuu }}</td>
            </tr>
            <tr>
                <th>尊敬する人</th>
                <td>{{ $detail->sonkei }}</td>
            </tr>
        </table>
    </div>
    <div class="p-staff-head-img"
        style="background-image:url({{ getS3ImageUrl($detail->staff_image_path) }})"></div>
</div>

<p>{{ $detail->caption }}</p>

@if (!empty($detail->question1))
    <dl class="p-staff-textbox">
        <dt class="p-staff-textbox-title">キャリアカウンセリングで心がけていること</dt>
        <dd class="p-staff-textbox-text">{{ $detail->question1 }}</dd>
    </dl>
@endif

@if (!empty($detail->question2))
    <dl class="p-staff-textbox">
        <dt class="p-staff-textbox-title">仕事で一番嬉しかった事</dt>
        <dd class="p-staff-textbox-text">{{ $detail->question2 }}</dd>
    </dl>
@endif

@if (!empty($detail->question3))
    <dl class="p-staff-textbox">
        <dt class="p-staff-textbox-title">就職・転職を考えている方へメッセージ</dt>
        <dd class="p-staff-textbox-text">{{ $detail->question3 }}</dd>
    </dl>
@endif
