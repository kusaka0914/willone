<!-- modal footer -->
<div id="footer">
    <div class="foot_nav footLink">
        <ul>
            <li class="show"><a href="{{ route('Rule') }}" rel="dialog nofollow" id="kiyaku" data-modal="rule" data-transition="pop" data-modal="rule">利用規約</a></li>
            <li class="show"><a href="{{ route('Privacy') }}" rel="nofollow" id="kojin_joho" data-modal="privacy">個人情報の取り扱いについて</a></li>
            <li class="show"><a id="company" data-modal="access">運営会社</a></li>
        </ul>
    </div>

    <div class="innerfooter">
        <small>(C) SMS CO., LTD. All Rights Reserved.</small>
    </div>
    <div class="modal rule">
        <div class="modalBody">
            <div class="scroll_box" id="rule"></div>
            <p class="close">×close</p>
        </div>
        <div class="modalBK"></div>
    </div>
    <div class="modal privacy">
        <div class="modalBody">
            <div class="scroll_box" id="privacy"></div>
            <p class="close">×close</p>
        </div>
        <div class="modalBK"></div>
    </div>
    <div class="modal access">
        <div class="modalBody">
            <div class="scroll_box" id="access"></div>
            <p class="close">×close</p>
        </div>
        <div class="modalBK"></div>
    </div>
</div>
