<!-- Admin user profile area -->
<div class="chat-profile-sidebar">
    <header class="chat-profile-header">
    <span class="close-icon">
      <i data-feather="x"></i>
    </span>
        <!-- User Information -->
        <div class="header-profile-sidebar">
            <div class="avatar box-shadow-1 avatar-xl avatar-border">
                <img src="{{ route('getProfilePicture', ['filename' => str_replace('images/', '', Auth::user()->profile_picture)]) }}" alt="user_avatar"/>
                <span class="avatar-status-online avatar-status-xl"></span>
            </div>
            <h4 class="chat-user-name">John Doe</h4>
            <span class="user-post">Admin</span>
        </div>
        <!--/ User Information -->
    </header>
</div>
<!--/ Admin user profile area -->

<!-- Chat Sidebar area -->
<div class="sidebar-content">
  <span class="sidebar-close-icon">
    <i data-feather="x"></i>
  </span>
    <!-- Sidebar header start -->
    <div class="chat-fixed-search">
        <div class="d-flex align-items-center w-100">
            <div class="sidebar-profile-toggle">
                <div class="avatar avatar-border">
                    <img
                        src="{{ route('getProfilePicture', ['filename' => str_replace('images/', '', Auth::user()->profile_picture)]) }}"
                        alt="user_avatar"
                        height="42"
                        width="42"
                    />
                    <span class="avatar-status-online"></span>
                </div>
            </div>
            <div class="input-group input-group-merge ms-1 w-100">
                <span class="input-group-text round"><i data-feather="search" class="text-muted"></i></span>
                <input
                    type="text"
                    class="form-control round"
                    id="chat-search"
                    placeholder="Search or start a new chat"
                    aria-label="Search..."
                    aria-describedby="chat-search"
                />
            </div>
        </div>
    </div>
    <!-- Sidebar header end -->

    <!-- Sidebar Users start -->
    <div id="users-list" class="chat-user-list-wrapper list-group">
        <h4 class="chat-list-title">Chats</h4>
        <ul class="chat-users-list chat-list media-list">

            <ul id="chatList" class="list-unstyled">
            </ul>

            <li class="no-results">
                <h6 class="mb-0">No Chats Found</h6>
            </li>
        </ul>
        <h4 class="chat-list-title">Contacts</h4>
        <ul class="chat-users-list contact-list media-list" >
            <ul id="contactsList"></ul>
            <li class="no-results">
                <h6 class="mb-0">No Contacts Found</h6>
            </li>
        </ul>
    </div>
    <!-- Sidebar Users end -->
</div>
<!--/ Chat Sidebar area -->
