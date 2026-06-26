<nav id="sidebarMenu" class="col-md-2 d-md-block sidebar collapse" style="background-color: #1a5c38;">
  <div class="sidebar-sticky pt-3">
  <ul class="nav flex-column">

      {{-- Home --}}
    <li class="nav-item">
      <a class="nav-link{{ request()->route()->named('admin.index') ? ' active' : '' }}"
        href="{{ route('admin.index') }}">
        <span data-feather="home"></span>Home</a>
    </li>

    {{-- Residents --}}
    <li class="nav-item">
      <a class="nav-link{{ request()->route()->named('admin.residents.index') ? ' active' : '' }}"
        href="{{ route('admin.residents.index') }}">
        <span data-feather="file-text"></span>Residents</a>
    </li>

    {{-- Remaind --}}
    <li class="nav-item">
        <a class="nav-link{{ request()->route()->named('admin.reminds.index') ? ' active' : '' }}"
          href="{{ route('admin.reminds.index') }}">
          <span data-feather="bell"></span>Remind</a>
    </li>

    {{-- Q&A(LLM機能) --}}
    <li class="nav-item">
        <a class="nav-link{{ request()->route()->named('admin.house_qa.index') ? ' active' : '' }}"
          href="{{ route('admin.house_qa.index') }}">
          <span data-feather="bell"></span>Rules</a>
    </li>

  </ul>
</div>
</nav>