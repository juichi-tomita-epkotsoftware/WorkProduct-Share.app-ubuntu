<nav id="sidebarMenu" class="col-md-2 d-md-block sidebar collapse" style="background-color: #1a5c38;">
  <div class="sidebar-sticky pt-3">
  <ul class="nav flex-column">

      {{-- Home --}}
    <li class="nav-item">
      <a class="nav-link{{ request()->route()->named('user.index') ? ' active' : '' }}"
        href="{{ route('user.index') }}">
        <span data-feather="home"></span>Home</a>
    </li>

    {{-- Residents --}}
    <li class="nav-item">
      <a class="nav-link{{ request()->route()->named('user.residents.index') ? ' active' : '' }}"
        href="{{ route('user.residents.index') }}">
        <span data-feather="user"></span>Residents</a>
    </li>

    {{-- Remaind --}}
    <li class="nav-item">
        <a class="nav-link{{ request()->route()->named('user.reminds.index') ? ' active' : '' }}"
          href="{{ route('user.reminds.index') }}">
          <span data-feather="bell"></span>Remind</a>
    </li>

    {{-- Q&A(LLM機能) --}}
    <li class="nav-item">
        <a class="nav-link{{ request()->route()->named('user.house_qa.index') ? ' active' : '' }}"
          href="{{ route('user.house_qa.index') }}">
          <span data-feather="book-open"></span>Rules</a>
    </li>


    <!--
    ・hrefはGETのURLの指定　route()内の引数へクリックしたら移動する。
    ・named()は現状の受信済みの現状のリクエストがどのルートでマッチしたかを紹介している
    -->
  </ul>
</div>
</nav>