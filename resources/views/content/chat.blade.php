
@extends('layouts/contentLayoutMaster')

@section('title', 'Chat Application')
@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">

@endsection
@section('page-style')
    <!-- Page css files -->
    <style>
        .chat.outbound {
            /* Styles for outbound (sent) messages */
            display: flex;
            justify-content: flex-end;
            text-align: right;
        }
        .user-chats {
            overflow-y: scroll;
        }
        #users-list {
            overflow-y: scroll;
        }
        .chat.inbound {
            /* Styles for inbound (received) messages */
            display: flex;
            justify-content: flex-start;
            text-align: left;
        }

    </style>
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-chat.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-chat-list.css')) }}">
@endsection

@section('content-sidebar')
    @include('content/chat-sidebar')
@endsection

@section('content')
    <div class="body-content-overlay"></div>
    <!-- Main chat area -->
    <section class="chat-app-window">
        <!-- To load Conversation -->
        <div class="start-chat-area">
            <div class="mb-1 start-chat-icon">
                <i data-feather="message-square"></i>
            </div>
            <h4 class="sidebar-toggle start-chat-text">Start Conversation</h4>
        </div>
        <!--/ To load Conversation -->

        <!-- Active Chat -->
        <div class="active-chat d-none">
            <!-- Chat Header -->
            <div class="chat-navbar">
                <header class="chat-header">
                    <div class="d-flex align-items-center">
                        <div class="sidebar-toggle d-block d-lg-none me-1">
                            <i data-feather="menu" class="font-medium-5"></i>
                        </div>
                        <div class="avatar avatar-border user-profile-toggle m-0 me-1">
                            <img  alt="avatar" height="36" width="36" />
                            <span class="avatar"></span>
                        </div>
                        <h6 class="mb-0">[USER_NAME]</h6>
                    </div>
                </header>
            </div>
            <!--/ Chat Header -->

            <!-- User Chat messages -->
            <div class="user-chats">
                <div class="chats">
                    <!-- Chat messages will be dynamically filled here -->
                </div>
            </div>
            <!-- User Chat messages -->

            <!-- Submit Chat form -->
            <form class="chat-app-form"  >
                <div class="input-group input-group-merge me-1 form-send-message">

                    <input type="text" class="form-control message" id="message_to_send" placeholder="Type your message or use speech to text" />
                    <span class="input-group-text">
            </span>
                </div>
                <button type="button" class="btn btn-primary send" onclick="enterChat();">
                    <i data-feather="send" class="d-lg-none"></i>
                    <span class="d-none d-lg-block">Send</span>
                </button>
            </form>
        </div>

        <!--/ Active Chat -->
    </section>
    <!--/ Main chat area -->

    <!-- User Chat profile right area -->
    <div class="user-profile-sidebar">
        <header class="user-profile-header">
    <span class="close-icon">
      <i data-feather="x"></i>
    </span>
            <!-- User Profile image with name -->
            <div class="header-profile-sidebar">
                <div class="avatar box-shadow-1 avatar-border avatar-xl">
                    <img src="{{ route('getProfilePicture', ['filename' => str_replace('images/', '', Auth::user()->profile_picture)]) }}" alt="user_avatar" height="70" width="70" />
                    <span class="avatar-status-busy avatar-status-lg"></span>
                </div>
                <h4 class="chat-user-name">Kristopher Candy</h4>
                <span class="user-post">UI/UX Designer 👩🏻‍💻</span>
            </div>
            <!--/ User Profile image with name -->
        </header>

    </div>
    <!--/ User Chat profile right area -->
@endsection

@section('page-script')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>


        var activeChat;



        getActiveChats();
        function openChat(chat){

            activeChat = chat;
            var chatUsersListWrapper = $('.chat-application .chat-user-list-wrapper');


            // Add class active on click of Chat users list
            if (chatUsersListWrapper.find('ul li').length) {
                chatUsersListWrapper.find('ul li').on('click', function () {
                    var $this = $(this),
                        startArea = $('.start-chat-area'),
                        activeChat = $('.active-chat');

                    if (chatUsersListWrapper.find('ul li').hasClass('active')) {
                        chatUsersListWrapper.find('ul li').removeClass('active');
                    }

                    $this.addClass('active');
                    $this.find('.badge').remove();

                    if (chatUsersListWrapper.find('ul li').hasClass('active')) {
                        startArea.addClass('d-none');
                        activeChat.removeClass('d-none');
                    } else {
                        startArea.removeClass('d-none');
                        activeChat.addClass('d-none');
                    }
                });
            }
            let senderId = 0;
            let receiverId = 0;
            if ( typeof chat.id !== "undefined" && chat.id) {
                 senderId = "{{$id}}";
                 receiverId = chat.id;
            }else{
                let message_data = Object.values(chat.messages)[0];
                senderId = message_data.sender_id;
                receiverId = message_data.receiver_id;
            }

            console.log(senderId)
            console.log(receiverId)



            $('.active-chat').attr('data-sender', senderId);
            $('.active-chat').attr('data-receiver', receiverId);
            fetch(`/chat/getMessages?sender_id=${senderId}&receiver_id=${receiverId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if required
                },
            })
                .then(response => response.json())
                .then(data => {
                    data = Object.values(data)[0];

                    // Get the chat header elements
                    let chatHeaderAvatar = document.querySelector('.chat-navbar .avatar img');
                    const chatHeaderUserName = document.querySelector('.chat-navbar h6');




                    fetch('/images/' + data.data.profile_picture)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Image request failed');
                            }
                            return response.blob();
                        })
                        .then(blob => {
                            chatHeaderAvatar.src = URL.createObjectURL(blob);
                        })
                        .catch(error => {
                            console.error(error);
                        });



                    chatHeaderUserName.textContent = data.data.name;

                    // Get the chat container
                    const chatContainer = document.querySelector('.user-chats .chats');

// Clear the chat container
                    chatContainer.innerHTML = '';

// Iterate over the messages and create HTML elements
                    for (const key in data.messages) {
                        if (data.messages.hasOwnProperty(key)) {
                            const message = data.messages[key];
                            const chat = document.createElement('div');

                            chat.className = message.channel === 'Outbound' ? 'chat outbound' : 'chat inbound';



                            const chatBody = document.createElement('div');
                            chatBody.className = 'chat-body';

                            const chatContent = document.createElement('div');
                            chatContent.className = 'chat-content';


                            const messageText = document.createElement('p');
                            messageText.textContent = message.content;

                            chatContent.appendChild(messageText);


                            if(message.channel === 'Outbound'){

                                chatBody.appendChild(chatContent);
                                chat.appendChild(chatBody);

                                const chatAvatar = document.createElement('div');
                                chatAvatar.className = 'chat-avatar';
                                const avatarSpan = document.createElement('span');
                                avatarSpan.className = 'avatar box-shadow-1 cursor-pointer';
                                const avatarImg = document.createElement('img');
                                avatarImg.src = "{{ route('getProfilePicture', ['filename' => str_replace('images/', '', Auth::user()->profile_picture)]) }}"




                                avatarImg.alt = 'avatar';
                                avatarImg.height = 36;
                                avatarImg.width = 36;
                                avatarSpan.appendChild(avatarImg);
                                chatAvatar.appendChild(avatarSpan);


                                chat.appendChild(chatAvatar);
                            }else{
                                chatContent.style = 'background:#337a4c';
                                chatBody.appendChild(chatContent);
                                const chatAvatar = document.createElement('div');
                                chatAvatar.className = 'chat-avatar';
                                const avatarSpan = document.createElement('span');
                                avatarSpan.className = 'avatar box-shadow-1 cursor-pointer';
                                const avatarImg = document.createElement('img');
                                avatarImg.src = chatHeaderAvatar.src




                                avatarImg.alt = 'avatar';
                                avatarImg.height = 36;
                                avatarImg.width = 36;
                                avatarSpan.appendChild(avatarImg);
                                chatAvatar.appendChild(avatarSpan);

                                chat.appendChild(chatAvatar);
                                chat.appendChild(chatBody);
                            }

                            chatContainer.appendChild(chat);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

// Scroll to the end of .user-chats
            var userChats = $('.chats .chat');
            var scrollHeight = userChats.prop('scrollHeight');
            userChats.scrollTop(scrollHeight);

            $('.user-chats').scrollTop($('.chats .chat')[0].scrollHeight);

        }
        function getActiveChats(){
            fetch('/chat/getChatContacts/', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if required
                },
            })
                .then(response => response.json())
                .then(data => {
                    // Get the chat list container
                    getChats(data );
                    getContacts(data );



                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }
        function getChats(data){
            const chatList = document.getElementById('chatList');
            chatList.innerHTML = '';
            const chats = Object.values(data.data.chats);
            chats.forEach(chat => {
                // Create the list item element
                const listItem = document.createElement('li');
                listItem.addEventListener('click', function() {
                    openChat(chat);
                });


                // Create the avatar span
                const avatarSpan = document.createElement('span');
                avatarSpan.className = 'avatar';
                const avatarImg = document.createElement('img');


                fetch('/images/' + chat.data.profile_picture)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Image request failed');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        avatarImg.src = URL.createObjectURL(blob);
                    })
                    .catch(error => {
                        console.error(error);
                    });



                avatarImg.height = 42;
                avatarImg.width = 42;
                avatarImg.alt = 'Generic placeholder image';
                avatarSpan.appendChild(avatarImg);

                // Create the chat info container
                const chatInfo = document.createElement('div');
                chatInfo.className = 'chat-info flex-grow-1';
                const chatTitle = document.createElement('h5');
                chatTitle.className = 'mb-0';
                chatTitle.textContent = chat.data.name;
                const chatText = document.createElement('p');
                chatText.className = 'card-text text-truncate';
                chatText.textContent = chat.data.role;
                chatInfo.appendChild(chatTitle);
                chatInfo.appendChild(chatText);

                // Create the chat meta container
                const chatMeta = document.createElement('div');
                chatMeta.className = 'chat-meta text-nowrap';
                const chatTime = document.createElement('small');
                chatTime.className = 'float-end mb-25 chat-time';
                chatTime.textContent = '4:14 PM';
                const badge = document.createElement('span');
                badge.className = 'badge bg-danger rounded-pill float-end';
                chatMeta.appendChild(chatTime);
                chatMeta.appendChild(badge);

                // Append the elements to the list item
                listItem.appendChild(avatarSpan);
                listItem.appendChild(chatInfo);
                listItem.appendChild(chatMeta);

                // Append the list item to the chat list
                chatList.appendChild(listItem);
            });
        }
        function getContacts(data){
            const contactsList = document.getElementById('contactsList');

            contactsList.innerHTML = '';
            const contacts = Object.values(data.data.contacts);
            contacts.forEach(contact => {
                // Create the list item element
                const listItem = document.createElement('li');
                listItem.addEventListener('click', function() {
                    openChat(contact);
                });
                // Create the avatar span
                const avatarSpan = document.createElement('span');
                avatarSpan.className = 'avatar';
                const avatarImg = document.createElement('img');

                fetch('/images/' + contact.profile_picture)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Image request failed');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        avatarImg.src = URL.createObjectURL(blob);
                    })
                    .catch(error => {
                        console.error(error);
                    });




                avatarImg.height = 42;
                avatarImg.width = 42;
                avatarImg.alt = 'Generic placeholder image';
                avatarSpan.appendChild(avatarImg);

                // Create the contact info container
                const contactInfo = document.createElement('div');
                contactInfo.className = 'contact-info flex-grow-1';
                const contactName = document.createElement('h5');
                contactName.className = 'mb-0';
                contactName.textContent = contact.name;
                const contactRole = document.createElement('p');
                contactRole.className = 'card-text text-truncate';
                contactRole.textContent = contact.role.role_name;
                contactInfo.appendChild(contactName);
                contactInfo.appendChild(contactRole);

                // Create the contact meta container
                const contactMeta = document.createElement('div');
                contactMeta.className = 'contact-meta text-nowrap';
                const contactTime = document.createElement('small');
                contactTime.className = 'float-end mb-25 chat-time';
                contactTime.textContent = '4:14 PM';
                const badge = document.createElement('span');
                badge.className = 'badge bg-danger rounded-pill float-end';
                contactMeta.appendChild(contactTime);
                contactMeta.appendChild(badge);

                // Append the elements to the list item
                listItem.appendChild(avatarSpan);
                listItem.appendChild(contactInfo);
                listItem.appendChild(contactMeta);

                // Append the list item to the contacts list
                contactsList.appendChild(listItem);
            });
        }


        function enterChat(){
            let message_to_send = document.getElementById('message_to_send').value;

            const senderId =    $('.active-chat').attr('data-sender');
            const receiverId = $('.active-chat').attr('data-receiver');


            fetch('/chat/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Set the content type to JSON
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if required
                },
                body: JSON.stringify({
                    'message_to_send': message_to_send,
                    'senderId': senderId,
                    'receiverId': receiverId
                })
            })
                .then(response => {
                    // Handle the response
                    if (response.ok) {
                        getActiveChats();
                        openChat(activeChat);
                    } else {
                        // Handle error response
                        response.json().then(data => {
                            // Use SweetAlert2 to display the error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                            });
                        });
                    }
                })
                .catch(error => {
                    // Handle network or other errors
                    console.error('Error:', error);
                });
            console.log(senderId,receiverId)
        }

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('b3e43753da501597ed00', {
            cluster: 'eu'
        });

        var channel = pusher.subscribe('chat');

        channel.bind('App\\Events\\MessageSent', function(data) {
            // Call getActiveChats() to update the active chats list
            getActiveChats();

            // Open the active chat
            openChat(activeChat);
        });

    </script>
    <!-- Page js files -->
@endsection
