@php

use App\Models\backend\BackendMenubar;
use App\Models\backend\BackendSubMenubar;
use App\Models\backend\AdminUsers;
use App\Models\backend\UserMaster;
use Spatie\Menu\Laravel\Menu;
use Spatie\Permission\Models\Role;

$user_id = Auth()->guard('admin')->user()->id;
//dd(Auth()->guard('admin')->user()->role);
$role_id = Auth()->guard('admin')->user()->role;

$user_role = Role::where('id',$role_id)->first();

$menu_ids=explode(",",$user_role->menu_ids);
$submenu_ids=explode(",",$user_role->submenu_ids);

$backend_menubar = BackendMenubar::WhereIn('menu_id',$menu_ids)->Where(['visibility'=>1])->orderBy('sort_order')->get();
// dd($backend_menubar->toArray());
@endphp

<!-- BEGIN: Main Menu-->
<div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-fixed navbar-light navbar-without-dd-arrow navbar-shadow menu-border" role="navigation" data-menu="menu-wrapper">
  <div class="navbar-container main-menu-content container-fluid center-layout" data-menu="menu-container">
    <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
      @php
      $menu_lists = ['id'=>1,'name'=>'Dashboard'];
      @endphp
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
          <i class="feather icon-home"></i>
          <span class="menu-title" data-i18n="Dashboard">Dashboard</span>
        </a>
      </li>
      @php
          
      foreach($backend_menubar as $menu)
      {
      if($menu->has_submenu == 1)
      {
      $backend_submenubar = BackendSubMenubar::WhereIn('submenu_id',$submenu_ids)->Where(['menu_id'=>$menu->menu_id])->Where(['visibility'=>1])->get();
      // dd($backend_submenubar->toArray());
      if($backend_submenubar)
      {
      @endphp
      <li class="dropdown nav-item" data-menu="dropdown">
        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
          <i class="feather icon-{{$menu->menu_icon}}"></i>
          <span class="menu-title" data-i18n="{{$menu->menu_name}}">{{$menu->menu_name}}</span>
        </a>
        <ul class="dropdown-menu">


          @php
          foreach($backend_submenubar as $submenu)
          {
            $sub_sub_menus_ids= [];
        
              if(!empty($submenu->sub_parent_id)){
                $sub_sub_menus_ids = explode(',',$submenu->sub_parent_id);
              }
             
          $suburl = ($submenu->submenu_controller_name != "#" && $submenu->submenu_controller_name != '')?route($submenu->submenu_controller_name):'#';
          @endphp

          @if($submenu->has_sub_submenu)
          <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
            <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#" data-i18n="{{ $submenu->submenu_name }}" data-toggle="dropdown">{{ $submenu->submenu_name }}</a>
            <ul class="dropdown-menu ">
              @if(!empty($sub_sub_menus_ids))
              @foreach($sub_sub_menus_ids as $row)
              <?php
              $sub_sub_menus = BackendSubMenubar::Where(['submenu_id'=>$row])->first();

              $url_for_submneus='#';
              if(!empty($sub_sub_menus)){
                $url_for_submneus = ($sub_sub_menus->submenu_controller_name != "#" && $sub_sub_menus->submenu_controller_name != '')?route($sub_sub_menus->submenu_controller_name):'#';
              }
              ?>
              <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="{{ $url_for_submneus }}" data-i18n="{{ $sub_sub_menus->submenu_name??'' }}" data-toggle="dropdown">{{$sub_sub_menus->submenu_name??''}}</a>
              </li>
              @endforeach
              @endif
            </ul>
            </li>
          @else
          <li>
            <a class="dropdown-item" href="{{ $suburl }}" data-i18n="{{ $submenu->submenu_name }}"><i class="feather icon-arrow-right"></i>{{ $submenu->submenu_name }}</a>
          </li>

          @endif
          @php
          }
          @endphp
         
        </ul>
      </li>
      @php
      }
      }
      else
      {
      $url = ($menu->menu_controller_name != "#" && $menu->menu_controller_name != '')?route($menu->menu_controller_name):'#';
      @endphp
      <li class=" nav-item">
        <a href="{{ $url }}" class="nav-link">
          <i class="feather icon-{{$menu->menu_icon}}"></i>
          <span class="menu-title" data-i18n="{{$menu->menu_name}}">{{$menu->menu_name}}</span>
        </a>
      </li>
      @php
      }
      }
      @endphp
    </ul>
  </div>
</div>
<!-- END: Main Menu-->