<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use App\Services\ChatService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ChatController extends Controller
{

    public ChatService $chatService;
    public UserService $userService;

    /**
     * @param ChatService $chatService
     */
    public function __construct(
        ChatService $chatService,
        UserService $userService
    )
    {
        $this->chatService = $chatService;
        $this->userService = $userService;
    }

    public function show()
    {
        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'pageClass' => 'chat-application',
        ];


        return view('content.chat', [
            'pageConfigs' => $pageConfigs,
            'id' => Auth::user()->id,
        ]);
    }

    public function getChatContacts()
    {
        $input = [
            'pageIndex' => 1,
            'pageSize' => 100,
            'search' => null,
            'method' => 'chatList',
            'type' => 'chatList'
        ];
        $messageList = $this->chatService->list($input)->where('sender_id', Auth::user()->id)
            ->orWhere('receiver_id', Auth::user()->id)->get()->toArray();

        $chats = [];
        foreach ($messageList as $message) {
            $senderKey = $message['sender_id'];
            $receiverKey = $message['receiver_id'];

            if ($senderKey != Auth::user()->id) {
                $chats[$senderKey]['data']['name'] = $message['sender']['name'];
                $chats[$senderKey]['data']['profile_picture'] = str_replace('images/','',$message['sender']['profile_picture']);
                $chats[$senderKey]['data']['role'] = $message['sender']['role']['role_name'];
                $chats[$senderKey]['messages'][$message['id']] = $message;
            } elseif ($receiverKey != Auth::user()->id) {
                $chats[$receiverKey]['data']['name'] = $message['receiver']['name'];
                $chats[$receiverKey]['data']['profile_picture'] = str_replace('images/','',$message['receiver']['profile_picture']);
                $chats[$receiverKey]['data']['role'] = $message['receiver']['role']['role_name'];
                $chats[$receiverKey]['messages'][$message['id']] = $message;
            }
        }


        $input = [
            'pageIndex' => 1,
            'pageSize' =>  1500,
            'search' => null,
            'method' => 'listUsers',
            'type' => 'all'
        ];
        $contacts = $this->userService->list($input)->where('id', '!=',Auth::user()->id)->get()->toArray();

        foreach ($contacts as $contact_id => $contact_values){
            $contacts[$contact_id]['profile_picture'] = str_replace('images/','',$contact_values['profile_picture']);

        }

        return [
          'status'=>'200',
          'data' => [
              'chats' => $chats,
              'contacts' => $contacts
          ]
        ];
    }

    public function getMessages()
    {

        $userID = Auth::user()->id;
        $otherId = request()->input('sender_id');

        if($userID == $otherId){
            $otherId = request()->input('receiver_id');
        }

        $input = [
            'pageIndex' => 1,
            'pageSize' => 100,
            'search' => null,
            'method' => 'chatList',
            'type' => 'chatList'
        ];
        $messageList = $this->chatService->list($input)->where(function ($query) use ($userID, $otherId) {
            $query->where('sender_id', $userID)
                ->where('receiver_id', $otherId);
        })->orWhere(function ($query) use ($userID, $otherId) {
            $query->where('sender_id', $otherId)
                ->where('receiver_id', $userID);
        })->get()->toArray();


        $chats = [];

        foreach ($messageList as $message) {
            $senderKey = $message['sender_id'];
            $receiverKey = $message['receiver_id'];

            if ($senderKey != Auth::user()->id) {
                $chats[$senderKey]['data']['name'] = $message['sender']['name'];
                $chats[$senderKey]['data']['profile_picture'] = str_replace('images/','',$message['sender']['profile_picture']);
                $chats[$senderKey]['data']['role'] = $message['sender']['role']['role_name'];
                $chats[$senderKey]['messages'][$message['id']] = $message;
                $chats[$senderKey]['messages'][$message['id']]['channel'] = 'Inbound';
            } elseif ($receiverKey != Auth::user()->id) {
                $chats[$receiverKey]['data']['name'] = $message['receiver']['name'];
                $chats[$receiverKey]['data']['profile_picture'] = str_replace('images/','',$message['receiver']['profile_picture']);
                $chats[$receiverKey]['data']['role'] = $message['receiver']['role']['role_name'];
                $chats[$receiverKey]['messages'][$message['id']] = $message;
                $chats[$receiverKey]['messages'][$message['id']]['channel'] = 'Outbound';
            }
        }



        if(empty($messageList)){
            $otherPerson = User::with('role')->where('id',$otherId)->first();

            $chats[$otherId]['data']['name'] = $otherPerson->name;
            $chats[$otherId]['data']['profile_picture'] = str_replace('images/','',$otherPerson->profile_picture);
            $chats[$otherId]['data']['role'] = $otherPerson->role->role_name;
        }

        return $chats;

    }
    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {


        $validatedData = $request->validate([
            'senderId' => 'required|numeric',
            'receiverId' => 'required|numeric',
            'message_to_send' => 'required|string',
        ]);

        $senderId = Auth::user()->id;
        $receiverId = $validatedData['receiverId'];
        $message = $validatedData['message_to_send'];

        if ($receiverId == $senderId) {
            $receiverId = $validatedData['senderId'];
        }



        $messageRecord = new Message();
        $messageRecord->sender_id = $senderId;
        $messageRecord->receiver_id = $receiverId;
        $messageRecord->content = $message;
        $messageRecord->timestamp = Carbon::now();


        if($messageRecord->save()){
            broadcast(new MessageSent(auth()->user(), $messageRecord))->toOthers();
        }


        return Response::json([
            'success' => true,
            'message' => 'Appointment completed successfully.',
        ], 200);
    }
}
