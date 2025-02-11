<section class="sidebar">

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>求人管理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('Kurohon')}}"><i class="fa fa-circle-o"></i>黒本リスト</a></li>
            <li><a href="{{ route('PrOpportunity')}}"><i class="fa fa-circle-o"></i>注目枠求人</a></li>
          </ul>
        </li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>コンテンツデータ管理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('SyusyokuBlogList')}}"><i class="fa fa-circle-o"></i>就職活動ノウハウブログ</a></li>
            <li><a href="{{ route('BlogImage')}}"><i class="fa fa-circle-o"></i>画像アップロード<br>（コラム・ブログ用）</a></li>
            <li><a href="{{ route('AdminStaffList')}}"><i class="fa fa-circle-o"></i>スタッフ一覧</a></li>
            <li><a href="{{ route('AdminKaitou')}}"><i class="fa fa-circle-o"></i>解答速報ページ一覧</a></li>
            <li><a href="{{ route('KaitouImage')}}"><i class="fa fa-circle-o"></i>解答速報画像一覧</a></li>
            <li><a href="{{ route('answer')}}"><i class="fa fa-circle-o"></i>解答速報変換</a></li>
          </ul>
        </li>
        <li class="active treeview">
            <a href="#">
                <i class="fa fa-dashboard"></i> <span>HubSpot管理</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('HubSpot')}}"><i class="fa fa-circle-o"></i>アクセストークン更新</a></li>
              </ul>
        </li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>ログインユーザ管理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('AdminUser')}}"><i class="fa fa-circle-o"></i>ユーザ編集</a></li>
            <li><a href="{{ route('Register')}}"><i class="fa fa-circle-o"></i>ユーザ追加</a></li>
          </ul>
        </li>

      </ul>
    </section>
